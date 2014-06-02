<?php

namespace Xmontero\Emperors\ClientBundle\Model\Board;

class BoardConverter
{
	public function convert( $board )
	{
		$clientBoard = array();
		
		$clientBoard[ 'width' ] = $board->getWidth();
		$clientBoard[ 'height' ] = $board->getHeight();
		$clientBoard[ 'columnIds' ] = $this->getColumnIds( $board );
		$clientBoard[ 'rowIds' ] = $this->getRowIds( $board );
		$clientBoard[ 'tiles' ] = $this->getTiles( $board );
		
		return $clientBoard;
	}
	
	public function getColumnIds( $board )
	{
		$columnIds = array();
		for( $x = 1; $x <= $board->getWidth(); $x++ )
		{
			$columnIds[ $x ] = $board->getColumnId( $x );
		}
		
		return $columnIds;
	}
	
	public function getRowIds( $board )
	{
		$rowIds = array();
		for( $y = 1; $y <= $board->getHeight(); $y++ )
		{
			$rowIds[ $y ] = $board->getRowId( $y );
		}
		
		return $rowIds;
	}
	
	public function getTiles( $board )
	{
		$tiles = array();
		for( $x = 1; $x <= $board->getWidth(); $x++ )
		{
			for( $y = 1; $y <= $board->getHeight(); $y++ )
			{
				$tileId = $board->buildTileIdFromCoordinates( $x, $y );
				$tileName = $board->getTileName( $x, $y );
				$tile = $board->getTile( $x, $y );
				$tiles[ $tileId ] = $this->getTile( $tile, $tileName, $x, $y );
			}
		}
		
		return $tiles;
	}
	
	public function getTile( $modelTile, $tileName, $x, $y )
	{
		$clientTile = array();
		$clientTile[ 'class' ] = 'hidden';
		$clientTile[ 'caption' ] = '';
		
		$clientTile[ 'object' ] = $this->getTileObject( $modelTile, $tileName, $x, $y );
		
		if( $modelTile->isOffBoard() )
		{
			$clientTile[ 'class' ] = 'border';
		}
		else
		{
			$clientTile = $this->getOnBoardTile( $modelTile, $clientTile );
		}
		
		$clientTile[ 'json' ] = json_encode( $clientTile[ 'object' ] );
		
		return $clientTile;
	}
	
	//-- Private ----------------------------------------------------------//
	
	private function getOnBoardTile( $modelTile, & $clientTile )
	{
		if( $modelTile->isInResetState() )
		{
			$clientTile[ 'class' ] = 'free';
		}
		else
		{
			$clientTile = $this->getNonResetOnBoardTile( $modelTile, $clientTile );
		}
		
		return $clientTile;
	}
	
	private function getNonResetOnBoardTile( $modelTile, & $clientTile )
	{
		if( $modelTile->propertyExists( 'class' ) )
		{
			$clientTile = $this->getLegacyClassTile( $modelTile, $clientTile );
		}
		else
		{
			$modelPieces = $modelTile->getVisiblePieces();
			if( $modelPieces->count() > 0 )
			{
				if( $modelPieces->count() > 1 )
				{
					throw new \RuntimeException( 'The current version of the software does not support to represent 2 visible items in a tile.' );
				}
				
				$modelPieces->rewind();
				$modelPiece = $modelPieces->current();
				
				$pieceType = $modelPiece->getType();
				
				switch( $pieceType )
				{
					case 'emperor':
						
						$clientTile[ 'class' ] = $pieceType . '-' . $modelPiece->getId();
						break;
						
					case 'pawn':
						
						$clientTile[ 'class' ] = $pieceType . '-' . $modelPiece->getWorkingForEmperorId();
						break;
						
					default:
						
						$clientTile[ 'class' ] = $pieceType;
						break;
				}
				
				$clientTile[ 'caption' ] = $modelPiece->getName();
			}
			else
			{
				$clientTile[ 'class' ] = 'free';
			}
		}
		
		if( $modelTile->propertyExists( 'text' ) )
		{
			$clientTile = $this->getLegacyTextTile( $modelTile, $clientTile );
		}
		
		return $clientTile;
	}
	
	private function getLegacyClassTile( $modelTile, & $clientTile )
	{
		$clientTile[ 'class' ] = $modelTile->getProperty( 'class' );
		return $clientTile;
	}
	
	private function getLegacyTextTile( $modelTile, & $clientTile )
	{
		$clientTile[ 'caption' ] = $modelTile->getProperty( 'text' );
		return $clientTile;
	}
	
	private function getTileObject( $modelTile, $tileName, $x, $y )
	{
		$result = array();
		$result[ 'name' ] = $tileName;
		$result[ 'coords' ] = '(' . $x . ', ' . $y . ')';
		
		$onBoard = $modelTile->isOnBoard();
		$result[ 'onBoard' ] = $onBoard;
		
		if( $onBoard )
		{
			foreach( $modelTile->getVisiblePieces() as $key => $piece )
			{
				$pieceType = $piece->getType();
				
				$result[ 'piece.type' ] = $pieceType;
				$result[ 'piece.name' ] = $piece->getName();
				
				if( ( $pieceType == 'emperor' ) || ( $pieceType == 'pawn' ) )
				{
					$result[ 'piece.playerId' ] = $piece->getPlayerId();
					
					if( $pieceType == 'pawn' )
					{
						$result[ 'piece.workingForEmperorId' ] = $piece->getWorkingForEmperorId();
					}
				}
			}
		}
		
		return $result;
	}
}
