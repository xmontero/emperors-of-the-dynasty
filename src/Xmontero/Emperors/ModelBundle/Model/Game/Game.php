<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Board\Board;

class Game
{
	private $id;
	private $boardManager;
	private $objectStorageManager;
	
	public function __construct( $id, $boardManager, $objectStorageManager )
	{
		$this->id = $id;
		$this->boardManager = $boardManager;
		$this->objectStorageManager = $objectStorageManager;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getStartDate()
	{
		$result = new \DateTime();
		return $result;
	}
	
	public function getBoard( $turn )
	{
		$board = $this->boardManager->loadBoardFromTemplate( 'uukhumaki' );
		
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
		
		// Emperors
		for( $i = 1; $i <= 8; $i++ )
		{
			$objectId = 'emperor-' . $i;
			
			$emperor = $this->objectStorageManager->getObjectById( $objectId );
			
			$x = $emperor[ $xName ];
			$y = $emperor[ $yName ];
			
			$class = 'emperor-' . $i;
			$content = 'E' . $i;
			
			$tile = $board->getTile( $x, $y );
			$tile->setProperty( 'class', $class );
			$tile->setProperty( 'text', $content );
		}
		
		// Pawns
		for( $player = 1; $player <= 8; $player++ )
		{
			for( $piece = 1; $piece <= 4; $piece++ )
			{
				$objectId = 'pawn-' . $player . '-' . $piece;
				
				$pawn = $this->objectStorageManager->getObjectById( $objectId );
				
				if( $pawn[ 'blob' ] != '**' )
				{
					$x = $pawn[ $xName ];
					$y = $pawn[ $yName ];
					
					$specialClass = ( $pawn[ 'blob' ] == '*' ) ? 'special ' : '';
					$class = $specialClass . 'pawn-' . $pawn[ $servedDynastyName ];
					$content = 'P' . $player . $piece;
					
					$tile = $board->getTile( $x, $y );
					$tile->setProperty( 'class', $class );
					$tile->setProperty( 'text', $content );
				}
			}
		}
		
		// Chests
		for( $i = 1; $i <= 4; $i++ )
		{
			$objectId = 'chest-' . $i;
			
			$chest = $this->objectStorageManager->getObjectById( $objectId );
			
			if( $chest[ 'blob' ] != '**' )
			{
				$x = $chest[ $xName ];
				$y = $chest[ $yName ];
				
				$class = 'chest-' . $i;
				$content = 'C' . chr( $i + ord( 'A' ) - 1 );
				
				$tile = $board->getTile( $x, $y );
				$tile->setProperty( 'class', $class );
				$tile->setProperty( 'text', $content );
			}
		}
		
		return $board;
	}
	
	public function getNumberOfPlayers()
	{
		return 8;
	}
	
	public function getNumberOfPlayersAlive()
	{
		return 8;
	}
}
