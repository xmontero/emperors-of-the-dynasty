<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Board\Board;

class Game
{
	private $id;
	private $boardManager;
	private $pieceManager;
	private $objectStorageManager;
	private $turn;
	private $board;
	
	public function __construct( $id, $boardManager, $pieceManager, $objectStorageManager )
	{
		if( $id != 1 )
		{
			throw new \RuntimeException( 'Game not found' );
		}
		
		$this->id = $id;
		$this->boardManager = $boardManager;
		$this->pieceManager = $pieceManager;
		$this->objectStorageManager = $objectStorageManager;
		$this->turn = 0;
		$this->board = $boardManager->loadBoardFromTemplate( 'uukhumaki' );
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
	
	public function setTurn( $newTurn )
	{
		$this->assertTurn( 0 );
		
		for( $i = 1; $i <= $newTurn; $i++ )
		{
			$this->advanceTurn();
		}
	}
	
	public function getTurn()
	{
		return $this->turn;
	}
	
	public function getLastOpenTurn()
	{
		return 4;
	}
	
	public function advanceTurn()
	{
		$turn = $this->turn;
		$board = $this->applyDesires( $turn );
		$this->applyRestrictions( $board );
		$this->turn++;
	}
	
	public function getBoard()
	{
		return $this->board;
	}
	
	public function getBoard2( $turn )
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
	
	//-- Private ----------------------------------------------------------//
	
	private function assertTurn( $turnId )
	{
		if( $this->turn != $turnId )
		{
			throw new \RuntimeException( 'Cannot change turn in case of a non-zero turn.' );
		}
	}
	
	private function applyDesires( $turn )
	{
		$desires = $this->getDesires( $turn );
		
		$board = $this->boardManager->cloneBoard( $this->board );
		foreach( $desires as $desire )
		{
			$this->applyDesire( $board, $desire );
		}
		
		return $board;
	}
	
	private function applyRestrictions( $board )
	{
		$this->board = $board;
	}
	
	private function getDesires( $turn )
	{
		$desires = array();
		
		switch( $turn )
		{
			case 0:
				
				$emperors = array();
				
				for( $player = 1; $player <= 8; $player++ )
				{
					$emperor = $this->pieceManager->createNewTokenFromScratch( 'emperor', $player );
					$emperor->setId( $player );
					$emperors[ $player ] = $emperor;
				}
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 1 ], 'tileId' => '5-1' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 2 ], 'tileId' => '10-1' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 3 ], 'tileId' => '14-5' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 4 ], 'tileId' => '14-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 5 ], 'tileId' => '10-14' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 6 ], 'tileId' => '5-14' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 7 ], 'tileId' => '1-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $emperors[ 8 ], 'tileId' => '1-5' );
				
				$pawns = array();
				
				$ctr = 1;
				for( $player = 9; $player <= 16; $player++ )
				{
					for( $pawnNumber = 1; $pawnNumber <= 4; $pawnNumber++ )
					{
						$pawn = $this->pieceManager->createNewTokenFromScratch( 'pawn', $player );
						$emperorId = $player - 8;
						$pawn->setWorkingForEmperorId( $emperorId );
						$pawn->setId( $ctr );
						$pawns[ $ctr ] = $pawn;
						$ctr++;
					}
				}
				
				$ctr = 1;
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '4-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '5-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '6-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '5-3' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '9-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '10-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '11-2' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '10-3' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-4' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '12-5' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-5' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-6' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-9' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '12-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '13-11' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '10-12' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '9-13' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '10-13' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '11-13' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '5-12' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '4-13' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '5-13' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '6-13' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-9' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '3-10' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-11' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-4' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-5' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '3-5' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $pawns[ $ctr++ ], 'tileId' => '2-6' );
				
				$chestA = $this->pieceManager->createNewItemFromScratch( 'chest' );
				$chestA->setId( 'A' );
				
				$chestB = $this->pieceManager->createNewItemFromScratch( 'chest' );
				$chestB->setId( 'B' );
				
				$chestC = $this->pieceManager->createNewItemFromScratch( 'chest' );
				$chestC->setId( 'C' );
				
				$chestD = $this->pieceManager->createNewItemFromScratch( 'chest' );
				$chestD->setId( 'D' );
				
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $chestA, 'tileId' => '6-6' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $chestB, 'tileId' => '9-6' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $chestC, 'tileId' => '9-9' );
				$desires[] = ( object ) array( 'subject' => 'game', 'action' => 'place', 'piece' => $chestD, 'tileId' => '6-9' );
				
				break;
				
			case 1:
				
				break;
				
			case 2:
				
				break;
				
			case 3:
				
				break;
				
			default:
				
				throw new \DomainException();
				break;
		}
		
		return $desires;
	}
	
	private function applyDesire( $board, $desire )
	{
		switch( $desire->action )
		{
			case 'place':
				
				$tileCoords = $board->splitTileIdInCoordinates( $desire->tileId );
				$tile = $board->getTile( $tileCoords[ 'x' ], $tileCoords[ 'y' ] );
				$tile->attachVisiblePiece( $desire->piece );
				break;
				
			default:
				
				throw new \DomainException;
				break;
		}
	}
}
