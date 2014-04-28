<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

class Board
{
	private $objectStorageManager;
	private $emperors;
	private $pawns;
	private $chests;
	private $tiles;
	
	public function __construct( $objectStorageManager )
	{
		// Tiles
		
		$tiles = array();
		for( $x = 0; $x <= $this->getNumberOfColumns(); $x++ )
		{
			$tiles[ $x ] = array();
			for( $y = 0; $y <= $this->getNumberOfRows(); $y++ )
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
		for( $x = 1; $x <= $this->getNumberOfColumns(); $x++ )
		{
			$tile = $tiles[ $x ][ 0 ];
			$tile->class = 'top-header';
			$tile->content = $x;
		}
		
		// Left header
		for( $y = 1; $y <= $this->getNumberOfColumns(); $y++ )
		{
			$tile = $tiles[ 0 ][ $y ];
			$tile->class = 'left-header';
			$tile->content = chr( $y + ord( 'A' ) -1 );
		}
		
		// Border
		for( $i = 1; $i <= $this->getNumberOfColumns(); $i++ )
		{
			if( ( $i != 5 ) && ( $i != 10 ) )
			{
				$tile = $tiles[ $i ][ 1 ]->class = 'border';
				$tile = $tiles[ $i ][ 14 ]->class = 'border';
				$tile = $tiles[ 1 ][ $i ]->class = 'border';
				$tile = $tiles[ 14 ][ $i ]->class = 'border';
			}
		}
		
		// Emperors
		$emperors = array();
		for( $i = 1; $i <= 8; $i++ )
		{
			$objectId = 'emperor-' . $i;
			
			$emperor = $objectStorageManager->getObjectById( $objectId );
			
			$x = $emperor[ 'x' ];
			$y = $emperor[ 'y' ];
			
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
					$x = $pawn[ 'x' ];
					$y = $pawn[ 'y' ];
					
					$specialClass = ( $pawn[ 'blob' ] == '*' ) ? 'special ' : '';
					
					$tile = $tiles[ $x ][ $y ];
					$tile->class = $specialClass . 'pawn-' . $pawn[ 'servedDynasty' ];
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
			
			$x = $chest[ 'x' ];
			$y = $chest[ 'y' ];
			
			$tile = $tiles[ $x ][ $y ];
			$tile->class = 'chest-' . $i;
			$tile->content = 'C' . chr( $i + ord( 'A' ) - 1 );
			
			$chests[] = $chest;
		}
		
		// Store
		
		$this->objectStorageManager = $objectStorageManager;
		$this->emperors = $emperors;
		$this->pawns = $pawns;
		$this->chests = $chests;
		$this->tiles = $tiles;
		
	}
	
	public function getStartDate()
	{
		$result = new \DateTime();
		return $result;
	}
	
	public function getTile( $x, $y )
	{
		return $this->tiles[ $x ][ $y ];
	}
	
	public function getNumberOfRows()
	{
		return 14;
	}
	
	public function getNumberOfColumns()
	{
		return 14;
	}
}
