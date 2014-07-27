<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\ClassicGames\Spoof;

use Xmontero\TurnBasedGame\Bundle\Model;
use Xmontero\TurnBasedGame\Bundle\Tests\Rules;

class SpoofOuterRules implements Model\Interfaces\GameObserver
{
	//---------------------------------------------------------------------//
	// Internal state                                                      //
	//---------------------------------------------------------------------//
	
	private $innerGame = null;
	private $weHaveLooser = false;
	
	//---------------------------------------------------------------------//
	// Constructors and destructors                                        //
	//---------------------------------------------------------------------//
	
	public function onGameTick( $outerGame )
	{
		//echo PHP_EOL . '- outer ------------- TICK ' . $outerGame->getTurn() . ' -------------------' . PHP_EOL;
		
		$this->initInnerGameRulesAndPlayers( $outerGame );
		$this->innerGame->run();
		$this->processInnerResults( $outerGame );
	}
	
	public function onGamePostCloseTurn( $outerGame, $turn )
	{
		if( $this->weHaveLooser )
		{
			$outerGame->end();
		}
	}
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
	//-- Pre-run ----------------------------------------------------------//
	
	private function initInnerGameRulesAndPlayers( $outerGame )
	{
		$maxCoinsPerPlayer = $outerGame->getElement( 'maxCoinsPerPlayer' );
		
		$this->initInnerGame( $outerGame );
		$this->initInnerRules( $maxCoinsPerPlayer );
		$this->initInnerPlayers( $outerGame );
	}
	
	private function initInnerGame( $outerGame )
	{
		$gameManager = $outerGame->getElement( 'gameManager' );
		$this->innerGame = $gameManager->createNewGame();
	}
	
	private function initInnerRules( $maxCoinsPerPlayer )
	{
		$innerLimitRule = new Rules\GameAbortsAfterTooManyTurns( 6 );
		$spoofInnerRules = new SpoofInnerRules( $maxCoinsPerPlayer );
		
		$this->innerGame->addRule( $innerLimitRule );
		$this->innerGame->addRule( $spoofInnerRules );
	}
	
	private function initInnerPlayersX( $numberOfPlayers, $gameDesiresOfAllPlayers )
	{
		for( $i = 1; $i <= $numberOfPlayers; $i++ )
		{
			$this->initOuterPlayer( $i, $gameDesiresOfAllPlayers[ $i ] );
		}
	}
	
	private function initInnerPlayer( $playerId, $gameDesiresOfASinglePlayer )
	{
		$player = new SpoofPlayer( $gameDesiresOfASinglePlayer );
		$this->players[ $playerId ] = $player;
		$this->outerGame->addPlayer( $playerId, $player );
	}
	
	private function initInnerPlayers( $outerGame )
	{
		$innerPlayer1 = $outerGame->getPlayer( 1 );
		$innerPlayer2 = $outerGame->getPlayer( 2 );
		
		$this->innerGame->addPlayer( 1, $innerPlayer1 );
		$this->innerGame->addPlayer( 2, $innerPlayer2 );
		
		//$innerPlayers = $this->getNonWinners( $game->getPlayers() );
		
		/*
		//echo( $innerPlayers->count() . PHP_EOL );
		foreach( $innerPlayers as $player )
		{
			$innerGame->addPlayer( $player );
		}
		
		*/
	}
	
	private function getNonWinners( $players )
	{
		$nonWinners = new \ArrayObject;
		
		foreach( $players as $key => $player )
		{
			if( $player->getTag() != 'winner' )
			{
				$nonWinners->append( $player );
			}
		}
		
		return $nonWinners;
	}
	
	//-- Post-run ---------------------------------------------------------//
	
	private function processInnerResults( $outerGame )
	{
		$outerGamePlayers = $outerGame->getPlayers();
		$outerGamePlayersCount = $outerGamePlayers->count();
		
		if( $outerGamePlayersCount == 2 )
		{
			$p1 = $outerGamePlayers[ 1 ];
			$p2 = $outerGamePlayers[ 2 ];
			
			if( $p1->getTag() == 'winner' )
			{
				$p2->setTag( 'looser' );
				$this->weHaveLooser = true;
			}
			else
			{
				if( $p2->getTag() == 'winner' )
				{
					$p1->setTag( 'looser' );
					$this->weHaveLooser = true;
				}
				else
				{
					throw new \Exception( 'No winner found in a terminated inner game.' );
				}
			}
		}
	}
}
