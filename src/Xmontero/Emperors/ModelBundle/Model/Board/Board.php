<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

use Xmontero\Emperors\ModelBundle\Model\Board\IPiece;
use Xmontero\Emperors\ModelBundle\Model\Pieces\Pieces;

class Board
{
	private $tileColumns;
	private $width;
	private $height;
	
	const ALPHABET_COUNT = 26;
	
	public function __construct( $width = 0, $height = 0 )
	{
		$this->setSize( $width, $height );
	}
	
	public function getTile( $x, $y )
	{
		$tile = $this->tileColumns[ $x ][ $y ];
		
		return $tile;
	}
	
	public function getTileById( $tileId )
	{
		//
	}
	
	public function getTiles()
	{
		$tiles = new Tiles;
		
		for( $x = 1; $x <= $this->width; $x++ )
		{
			for( $y = 1; $y <= $this->height; $y++ )
			{
				$tileId = $this->buildTileIdFromCoordinates( $x, $y );
				$tile = $this->getTile( $x, $y );
				
				$tiles[ $tileId ] = $tile;
			}
		}
		
		return $tiles;
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
	
	public function loadFromJson( $jsonDocument )
	{
		$desiredBoard = json_decode( $jsonDocument );
		
		if( is_null( $desiredBoard ) )
		{
			throw new \InvalidArgumentException( 'Invalid json.' );
		}
		
		$this->loadFromObjectDocument( $desiredBoard );
	}
	
	public function saveToJson()
	{
		$board = new \StdClass;
		
		$board->width = $this->width;
		$board->height = $this->height;
		
		$tiles = $this->saveTilesObject();
		if( count( $tiles ) > 0 )
		{
			$board->tiles = $tiles;
		}
		
		$jsonDocument = json_encode( $board );
		
		return $jsonDocument;
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
		
		$newColumn = $this->getEmptyColumn();
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
	
	public function getTileName( $x, $y )
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
		
		if( $visible )
		{
			$tile->attachVisiblePiece( $piece );
		}
		else
		{
			$tile->attachHiddenPiece( $piece );
		}
	}
	
	public function buildTileIdFromCoordinates( $x, $y )
	{
		$this->assertCoordinatesInBoard( $x, $y );
		
		$tileId = ( ( string )$x ) . '-' . ( ( string ) $y );
		return $tileId;
	}
	
	public function splitTileIdInCoordinates( $tileId )
	{
		$parts = explode( '-', $tileId );
		
		$result = array();
		$result[ 'x' ] = ( integer )$parts[ 0 ];
		$result[ 'y' ] = ( integer )$parts[ 1 ];
		
		return $result;
	}
	
	// -- PRIVATE ------------------------------------------
	
	// Loading.
	
	private function loadFromObjectDocument( $desiredBoard )
	{
		$this->loadSizeFromObjectDocument( $desiredBoard );
		$this->loadTilesFromObjectDocument( $desiredBoard );
	}
	
	private function loadSizeFromObjectDocument( $desiredBoard )
	{
		$this->assertIsSet( isset( $desiredBoard->width ), 'width' );
		$this->assertIsSet( isset( $desiredBoard->height ), 'height' );
		
		$this->setSize( $desiredBoard->width, $desiredBoard->height );
	}
	
	private function loadTilesFromObjectDocument( $desiredBoard )
	{
		if( isset( $desiredBoard->tiles ) )
		{
			$this->loadTilesFromExistingObjectDocument( $desiredBoard->tiles );
		}
		else
		{
			$this->setEmptyBoard();
		}
	}
	
	private function loadTilesFromExistingObjectDocument( $tilesObjectDocument )
	{
		$this->assertType( $tilesObjectDocument, "object" );
		
		for( $y = 1; $y <= $this->height; $y++ )
		{
			for( $x = 1; $x <= $this->width; $x++ )
			{
				$tileId = $this->buildTileIdFromCoordinates( $x, $y );
				
				if( isset( $tilesObjectDocument->{ $tileId } ) )
				{
					$tile = $this->getTile( $x, $y );
					$tileObjectDocument = $tilesObjectDocument->{ $tileId };
					
					$tile->loadFromObjectDocument( $tileObjectDocument );
				}
			}
		}
	}
	
	private function saveTilesObject()
	{
		$tiles = array();
		
		for( $x = 1; $x <= $this->width; $x++ )
		{
			for( $y = 1; $y <= $this->height; $y++ )
			{
				$tile = $this->getTile( $x, $y );
				
				if( ! $tile->isInResetState() )
				{
					$tileId = $this->buildTileIdFromCoordinates( $x, $y );
					$tiles[ $tileId ] = $tile->saveToObjectDocument();
				}
			}
		}
		
		//var_dump($tiles);
		
		/*
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
		*/
		
		return $tiles;
	}
	
	// Size.
	
	private function setSize( $width, $height )
	{
		$this->assertType( $width, "integer" );
		$this->assertType( $height, "integer" );
		
		$this->width = $width;
		$this->height = $height;
		
		$this->setEmptyBoard();
	}
	
	// Assertions.
	
	private function assertIsSet( $setValue, $setKey )
	{
		if( ! $setValue )
		{
			throw new \RuntimeException( 'Error processing object. "' . $setKey . '" not set.' );
		}
	}
	
	private function assertType( $value, $expectedType )
	{
		$currentType = gettype( $value );
		if( $currentType != $expectedType )
		{
			throw new \InvalidArgumentException( 'Expecting "' . $expectedType . '", got "' . $currentType . '".' );
		}
	}
	
	private function assertCoordinatesInBoard( $x, $y )
	{
		$this->assertType( $x, "integer" );
		$this->assertType( $y, "integer" );
		
		if( ( $x < 1 ) || ( $x > $this->width ) || ( $y < 1 ) || ( $y > $this->height ) )
		{
			$msg = sprintf( 'Coordinates ( %d, %d ) out of bounds in a board of ( [ 1...%d ], [ 1...%d ] ).', $x, $y, $this->width, $this->height );
			throw new \DomainException( $msg );
		}
	}
	
	// Row and column management.
	
	private function setEmptyBoard()
	{
		$tileColumns = array();
		for( $x = 1; $x <= $this->width; $x++ )
		{
			$tileColumns[ $x ] = $this->getEmptyColumn();
		}
		
		$this->tileColumns = $tileColumns;
	}
	
	private function getEmptyColumn()
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
	
	// Utils.
	
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
