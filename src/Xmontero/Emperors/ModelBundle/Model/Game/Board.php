<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

class Board
{
	private $objectStorageManager;
	private $emperors;
	private $pawns;
	private $chests;
	private $tiles;
	private $tileColumns;
	private $pieces;
	private $items;
	
	private $width;
	private $height;
	
	public function __construct( $objectStorageManager, $turn, $width, $height )
	{
		$this->setSize( $width, $height );
		$this->pieces = new Items;
		$this->items = new Items;
		
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
	
	public function getStartDate()
	{
		$result = new \DateTime();
		return $result;
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
	
	public function getItems()
	{
		return $this->items;
	}
	
	public function getPieces()
	{
		return $this->pieces;
	}
	
	public function getItemsAndPieces()
	{
		return $this->items;
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
		$document = json_encode( $this );
		return $document;
	}
	
	// -- PRIVATE ------------------------------------------
	
	private function setSize( $width, $height )
	{
		$this->width = $width;
		$this->height = $height;
	}
}
