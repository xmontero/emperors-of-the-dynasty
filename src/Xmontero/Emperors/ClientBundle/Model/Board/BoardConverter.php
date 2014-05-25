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
				$tile = $board->getTile( $x, $y );
				$tiles[ $tileId ] = $this->getTile( $tile );
			}
		}
		
		return $tiles;
	}
	
	public function getTile( $modelTile )
	{
		$clientTile = array();
		$clientTile[ 'class' ] = 'hidden';
		$clientTile[ 'caption' ] = '';
		
		if( $modelTile->isOffBoard() )
		{
			$clientTile[ 'class' ] = 'border';
		}
		else
		{
			if( $modelTile->propertyExists( 'class' ) )
			{
				$clientTile[ 'class' ] = $modelTile->getProperty( 'class' );
			}
			else
			{
				$clientTile[ 'class' ] = 'free';
			}
			
			if( $modelTile->propertyExists( 'text' ) )
			{
				$clientTile[ 'caption' ] = $modelTile->getProperty( 'text' );
			}
		}
		
		return $clientTile;
	}
}
