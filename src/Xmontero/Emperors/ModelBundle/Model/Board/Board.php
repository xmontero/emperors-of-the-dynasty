<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

use Xmontero\Emperors\ModelBundle\Model\Board\IPiece;
use Xmontero\Emperors\ModelBundle\Model\Game\Pieces\Pieces;

class Board
{
	private $objectStorageManager;
	
	private $tiles;
	private $tileColumns;
	private $width;
	private $height;
	
	const ALPHABET_COUNT = 26;
	
	public function __construct( $width = 0, $height = 0 )
	{
		$this->setSize( $width, $height );
		//$this->createEmptyBoard();
		
		$this->oldLoad( null, null, $width, $height );
	}
	
	public function oldLoad( $objectStorageManager, $turn, $width, $height )
	{
		$this->setSize( $width, $height );
		
		if( ! is_null( $objectStorageManager ) )
		{
			// Columns
			
			switch( $turn )
			{
				case 0:
				
					$prefix = 'old';
					break;
				
				case 1:
				
					$prefix = 'current';
					break;
				
				case 2:
				
					$prefix = 'next';
					break;
			}
			
			$xName = $prefix . 'X';
			$yName = $prefix . 'Y';
			$servedDynastyName = $prefix . 'ServedDynasty';
			$experienceName = $prefix . 'Experience';
			
			// Tiles
			
			$tiles = array();
			for( $x = 0; $x <= $this->width; $x++ )
			{
				$tiles[ $x ] = array();
				for( $y = 0; $y <= $this->height; $y++ )
				{
					$tile = new \StdClass;
					$tile->class = 'free';
					$tile->content = '';
					$tiles[ $x ][ $y ] = $tile;
				}
			}
			
			$origin = $tiles[ 0 ][ 0 ];
			$origin->class = 'origin';
			
			// Top header
			for( $x = 1; $x <= $this->width; $x++ )
			{
				$tile = $tiles[ $x ][ 0 ];
				$tile->class = 'top-header';
				$tile->content = $x;
			}
			
			// Left header
			for( $y = 1; $y <= $this->height; $y++ )
			{
				$tile = $tiles[ 0 ][ $y ];
				$tile->class = 'left-header';
				$tile->content = chr( $y + ord( 'A' ) -1 );
			}
			
			// Border
			for( $i = 1; $i <= $this->width; $i++ )
			{
				if( ( $i != 5 ) && ( $i != 10 ) )
				{
					$tile = $tiles[ $i ][ 1 ]->class = 'border';
					$tile = $tiles[ $i ][ $this->height ]->class = 'border';
					$tile = $tiles[ 1 ][ $i ]->class = 'border';
					$tile = $tiles[ $this->width ][ $i ]->class = 'border';
				}
			}
			
			// Emperors
			$emperors = array();
			for( $i = 1; $i <= 8; $i++ )
			{
				$objectId = 'emperor-' . $i;
				
				$emperor = $objectStorageManager->getObjectById( $objectId );
				
				$x = $emperor[ $xName ];
				$y = $emperor[ $yName ];
				
				$tile = $tiles[ $x ][ $y ];
				$tile->class = 'emperor-' . $i;
				$tile->content = 'E' . $i;
				
				$emperors[] = $emperor;
			}
			
			// Pawns
			$pawns = array();
			for( $player = 1; $player <= 8; $player++ )
			{
				for( $piece = 1; $piece <= 4; $piece++ )
				{
					$objectId = 'pawn-' . $player . '-' . $piece;
					
					$pawn = $objectStorageManager->getObjectById( $objectId );
					
					if( $pawn[ 'blob' ] != '**' )
					{
						$x = $pawn[ $xName ];
						$y = $pawn[ $yName ];
						
						$specialClass = ( $pawn[ 'blob' ] == '*' ) ? 'special ' : '';
						
						$tile = $tiles[ $x ][ $y ];
						$tile->class = $specialClass . 'pawn-' . $pawn[ $servedDynastyName ];
						$tile->content = 'P' . $player . $piece;
						
						$pawns[] = $pawn;
					}
				}
			}
			
			// Chests
			$chests = array();
			for( $i = 1; $i <= 4; $i++ )
			{
				$objectId = 'chest-' . $i;
				
				$chest = $objectStorageManager->getObjectById( $objectId );
				
				if( $chest[ 'blob' ] != '**' )
				{
					$x = $chest[ $xName ];
					$y = $chest[ $yName ];
					
					$tile = $tiles[ $x ][ $y ];
					$tile->class = 'chest-' . $i;
					$tile->content = 'C' . chr( $i + ord( 'A' ) - 1 );
					
					$chests[] = $chest;
				}
			}
			
			// Store
			
			$this->objectStorageManager = $objectStorageManager;
			$this->emperors = $emperors;
			$this->pawns = $pawns;
			$this->chests = $chests;
			$this->tiles = $tiles;
			
		}
		else
		{
			$tileColumns = array();
			for( $x = 0; $x <= $this->width; $x++ )
			{
				$tileColumns[ $x ] = array();
				for( $y = 0; $y <= $this->height; $y++ )
				{
					$tile = new Tile;
					$tileColumns[ $x ][ $y ] = $tile;
				}
			}
			
			$this->tileColumns = $tileColumns;
		}
	}
	
	public function getTileOld( $x, $y )
	{
		return $this->tiles[ $x ][ $y ];
	}
	
	public function getTile( $x, $y )
	{
		return $this->tileColumns[ $x ][ $y ];
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	
	public function getPieces()
	{
		$result = new Pieces;
		
		foreach( $this->tileColumns as $column )
		{
			foreach( $column as $tile )
			{
				$pieces = $tile->getPieces();
				foreach( $pieces as $piece )
				{
					$result->attach( $piece );
				}
			}
		}
		
		return $result;
	}
	
	public function getVisiblePieces()
	{
		$result = new Pieces;
		
		foreach( $this->tileColumns as $column )
		{
			foreach( $column as $tile )
			{
				$pieces = $tile->getVisiblePieces();
				foreach( $pieces as $piece )
				{
					$result->attach( $piece );
				}
			}
		}
		
		return $result;
	}
	
	public function getHiddenPieces()
	{
		$result = new Pieces;
		
		foreach( $this->tileColumns as $column )
		{
			foreach( $column as $tile )
			{
				$pieces = $tile->getHiddenPieces();
				foreach( $pieces as $piece )
				{
					$result->attach( $piece );
				}
			}
		}
		
		return $result;
	}
	
	public function load( $document )
	{
		$desiredBoard = json_decode( $document );
		
		if( is_null( $desiredBoard ) )
		{
			throw new \InvalidArgumentException( 'Invalid json.' );
		}
		
		$this->setSize( $desiredBoard->width, $desiredBoard->height );
	}
	
	public function save()
	{
		$board = new \StdClass;
		
		$board->width = $this->width;
		$board->height = $this->height;
		$board->tiles = $this->saveTilesObject();
		
		$document = json_encode( $board );
		
		return $document;
	}
	
	private function saveTilesObject()
	{
		$tiles = array();
		
		$headerColumn = true;
		foreach( $this->tileColumns as $column )
		{
			if( $headerColumn )
			{
				$headerColumn = false;
			}
			else
			{
				$headerRow = true;
				//$tiles[] = $column->saveObject();
				foreach( $column as $tile )
				{
					if( $headerRow )
					{
						$headerRow = false;
					}
					else
					{
						$tileSaveObject = $tile->saveToObject();
						$tiles[] = $tileSaveObject;
					}
				}
			}
		}
		
		return $tiles;
	}
	
	public function addColumnAt( $desiredX )
	{
		$width = $this->width;
		
		if( ( $desiredX < 1 ) || ( $desiredX > $width + 1 ) )
		{
			throw new \DomainException( 'addColumn cannot add column at position ' . $desiredX . ' in a board of width ' . $width );
		}
		
		for( $x = $width + 1; $x > $desiredX; $x-- )
		{
			$this->tileColumns[ $x ] = $this->tileColumns[ $x - 1 ];
		}
		
		$newColumn = $this->createEmptyColumn();
		$this->tileColumns[ $desiredX ] = $newColumn;
		
		$this->width++;
	}
	
	public function addRowAt( $desiredY )
	{
		$height = $this->height;
		
		if( ( $desiredY < 1 ) || ( $desiredY > $height + 1 ) )
		{
			throw new \DomainException( 'addColumn cannot add row at position ' . $desiredY . ' in a board of height ' . $height );
		}
		
		foreach( $this->tileColumns as & $column )
		{
			$this->addRowInColumnAt( $column, $desiredY );
		}
		
		$this->height++;
	}
	
	public function getTileId( $x, $y )
	{
		$columnId = $this->getColumnId( $x );
		$rowId = $this->getRowId( $y );
		
		$tileId = $columnId . $rowId;
		
		return $tileId;
	}
	
	public function getColumnId( $x )
	{
		if( ( $x < 1 ) || ( $x > $this->width ) )
		{
			throw new \DomainException;
		}
		
		$zeroBasedX = $x - 1;
	
		$prefixNumeric = floor( $zeroBasedX / self::ALPHABET_COUNT );
		$postfixNumeric = $zeroBasedX % self::ALPHABET_COUNT;

		$prefixString = $this->base27ToString( $prefixNumeric );
		$postfixString = $this->base26ToString( $postfixNumeric );
		
		$result = $prefixString . $postfixString;
		
		return $result;
	}
	
	public function getRowId( $y )
	{
		if( ( $y < 1 ) || ( $y > $this->height ) )
		{
			throw new \DomainException;
		}
		
		return ( string )$y;
	}
	
	public function placePiece( $x, $y, IPiece $piece, $visible )
	{
		$tile = $this->getTile( $x, $y );
		$piece->placeInTile( $tile, $visible );
	}
	
	// -- PRIVATE ------------------------------------------
	
	private function setSize( $width, $height )
	{
		$this->width = $width;
		$this->height = $height;
	}
	
	/*
	private function createEmptyBoard()
	{
		for( $x = 1; $x <= $this->width; $x++ )
		{
			$column = $this->createEmptyColumn();
			$this->tileColumns[] = $column;
		}
	}
	*/
	
	private function createEmptyColumn()
	{
		$column = array();
		
		for( $y = 1; $y <= $this->height; $y++ )
		{
			$tile = new Tile;
			$column[ $y ] = $tile;
		}
		
		return $column;
	}
	
	private function addRowInColumnAt( & $column, $desiredY )
	{
		$height = $this->height;
		
		for( $y = $height + 1; $y > $desiredY; $y-- )
		{
			$column[ $y ] = $column[ $y - 1 ];
		}
		
		$column[ $desiredY ] = new Tile;
	}
	
	private function base26ToString( $x )
	{
		// @codeCoverageIgnoreStart
		if( ( $x < 0 ) || ( $x > self::ALPHABET_COUNT - 1 ) )
		{
			throw new \DomainException( 'Called with $x = ' . $x );
		}
		// @codeCoverageIgnoreStop
		
		$result = chr( 0x41 + $x );
		
		return $result;
	}
	
	private function base27ToString( $x )
	{
		if( $x == 0 )
		{
			$result = '';
		}
		else
		{
			if( $x > self::ALPHABET_COUNT )
			{
				// @codeCoverageIgnoreStart
				throw new \RuntimeException( 'Not implemented for columns bigger or equal than AAA' );
				// @codeCoverageIgnoreStop
				
				/*
				$prefixNumeric = floor( $x / ( self::ALPHABET_COUNT + 1 ) );
				$postfixNumeric = $x % ( self::ALPHABET_COUNT + 1 );
				
				$prefixString = $this->base27ToString( $prefixNumeric );
				$postfixString = $this->base27ToString( $postfixNumeric );
				
				$result = $prefixString . $postfixString;
				*/
			}
			else
			{
				$result = $this->base26ToString( $x - 1 );
			}
		}
		
		return $result;
	}
}
