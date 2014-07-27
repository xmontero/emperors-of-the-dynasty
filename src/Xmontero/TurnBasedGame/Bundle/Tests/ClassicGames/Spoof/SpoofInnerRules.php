<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\ClassicGames\Spoof;

use Xmontero\TurnBasedGame\Bundle\Model;
use Xmontero\TurnBasedGame\Bundle\Tests\Helpers\Desire;

class SpoofInnerRules implements Model\Interfaces\GameObserver
{
	//---------------------------------------------------------------------//
	// Internal state                                                      //
	//---------------------------------------------------------------------//
	
	private $maxCoinsPerPlayer;
	
	//---------------------------------------------------------------------//
	// Constructors and destructors                                        //
	//---------------------------------------------------------------------//
	
	public function __construct( $maxCoinsPerPlayer )
	{
		$this->maxCoinsPerPlayer = $maxCoinsPerPlayer;
	}
	
	//---------------------------------------------------------------------//
	// Event handlers                                                      //
	//---------------------------------------------------------------------//
	
	public function onGamePostStart( $innerGame )
	{
		// To setup the game, prepare the bag for entering the coins.
		
		$this->createBag( $innerGame );
	}
	
	public function onGamePostOpenTurn( $innerGame, $turn )
	{
		// Right after starting each turn, empty the bag, so zero coins are left inside.
		
		$this->resetBag( $innerGame );
	}
	
	public function onGamePreAddDesire( $innerGame, $newDesire, $cancel )
	{
		// A user cannot say a number that already has been said in that turn.
		
		$verb = $newDesire->getVerb();
		
		if( $verb == 'say' )
		{
			$sayNumber = $newDesire->getArgument( 'number' );
			
			$existingDesires = $innerGame->getDesires();
			foreach( $existingDesires as $existingDesire )
			{
				if( $existingDesire->getVerb() == 'say' )
				{
					if( $sayNumber == $existingDesire->getArgument( 'number' ) )
					{
						$cancel = true;
					}
				}
			}
		}
	}
	
	public function onGamePostAddDesire( $innerGame, $desire )
	{
		// When a user picks a number of coins, they are added to the bag.
		// A user is considered a liar if he says a number that cannot
		// happen according to his pick.
		
		$verb = $desire->getVerb();
		
		if( $verb == 'pick' )
		{
			$pickNumber = $desire->getArgument( 'coins' );
			$this->addCoinsToBag( $innerGame, $pickNumber );
		}
		
		if( $verb == 'say' )
		{
			// This is assuming that "say" always goes after "pick".
			// As we control the onGameTick() we rely on this.
			
			$this->processLiar( $innerGame, $desire );
		}
	}
	
	public function onGameTick( $innerGame )
	{
		// For each turn, each player picks his coins. After each player
		// has done so, each player says his number.
		
		//echo PHP_EOL . '- inner ------------- TICK ' . $innerGame->getTurn() . ' --------' . PHP_EOL;
		
		foreach( $innerGame->getPlayers() as $playerId => $player )
		{
			$pick = $player->getPick();
			
			$desire = new Desire;
			$desire->setSubject( $player );
			$desire->setPredicate( 'pick', array( 'coins' => $pick ) );
			
			//echo( 'Player ' . $playerId . ' desires to pick ' . $pick );
			if( ! $innerGame->addDesire( $desire ) )
			{
				throw new \RuntimeException( 'Cannot add pick desire.' );
			}
			//echo( ' -- desire accepted' . PHP_EOL );
		}
		
		foreach( $innerGame->getPlayers() as $playerId => $player )
		{
			$cnt = 0;
			do
			{
				$say = $player->getSay();
				
				$desire = new Desire;
				$desire->setSubject( $player );
				$desire->setPredicate( 'say', array( 'number' => $say ) );
				
				//echo( 'Player ' . $playerId . ' desires to say ' . $say );
				$couldAdd = $innerGame->addDesire( $desire );
				//echo( ' -- desire ' . ( $couldAdd ? 'accepted' : 'rejected' ) . PHP_EOL );
				$cnt++;
				
				if( $cnt > 10 )
				{
					throw new \RuntimeException( 'Too many desire rejections.' );
				}
			}
			while( ! $couldAdd );
		}
	}
	
	public function onGamePreCloseTurn( $innerGame, $turn, $cancel )
	{
		// A player cannot pick more coins than permitted.
		
		$maxCoinsPerPlayer = $this->maxCoinsPerPlayer;
		$desires = $innerGame->getDesires();
		
		foreach( $desires as $desire )
		{
			if( $desire->getVerb() == 'pick' )
			{
				$coins = $desire->getArgument( 'coins' );
				if( $coins > $maxCoinsPerPlayer )
				{
					$innerGame->resetTurn();
					$cancel = true;
				}
			}
		}
	}
	
	public function onGamePostCloseTurn( $innerGame, $turn )
	{
		// A player who says the correct number of coins in the
		// bag is proclamed winner and the match ends.
		
		$coinsInTheBag = $innerGame->getElement( 'coinsInTheBag' );
		$desires = $innerGame->getDesires();
		
		$matchingSayDesire = $this->getSayDesireByNumber( $desires, $coinsInTheBag );
		
		if( ! is_null( $matchingSayDesire ) )
		{
			$matchingPlayer = $matchingSayDesire->getSubject();
			$matchingPlayer->setTag( 'winner' );
			$innerGame->end();
		}
	}
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
	//-- Bag --------------------------------------------------------------//
	
	private function createBag( $innerGame )
	{
		$coinsInTheBag = 0;
		$innerGame->addElement( 'coinsInTheBag', $coinsInTheBag );
	}
	
	private function resetBag( $innerGame )
	{
		$coinsInTheBag = 0;
		$innerGame->updateElement( 'coinsInTheBag', $coinsInTheBag );
	}
	
	private function addCoinsToBag( $innerGame, $coins )
	{
		$coinsInTheBag = $innerGame->getElement( 'coinsInTheBag' );
		$coinsInTheBag += $coins;
		$innerGame->updateElement( 'coinsInTheBag', $coinsInTheBag );
	}
	
	//-- Desires ----------------------------------------------------------//
	
	private function getSayDesireByNumber( $desires, $desiredNumber )
	{
		$matchingDesire = null;
		
		foreach( $desires as $desire )
		{
			$verb = $desire->getVerb();
			
			if( $verb == 'say' )
			{
				$currentNumber = $desire->getArgument( 'number' );
				
				if( $currentNumber == $desiredNumber )
				{
					$matchingDesire = $desire;
					break;
				}
			}
		}
		
		return $matchingDesire;
	}
	
	private function getPickDesireBySubject( $desires, $desiredSubject )
	{
		$matchingDesire = null;
		
		foreach( $desires as $desire )
		{
			$verb = $desire->getVerb();
			
			if( $verb == 'pick' )
			{
				$currentSubject = $desire->getSubject();
				
				if( $currentSubject == $desiredSubject )
				{
					$matchingDesire = $desire;
					break;
				}
			}
		}
		
		return $matchingDesire;
	}
	
	private function processLiar( $innerGame, $desire )
	{
		//-----------------------------------------------------------------//
		// Assume you have P=6 players, max C=3 coins                      //
		//                                                                 //
		// If nothing is known, then:                                      //
		// Maximum coins = 6 * 3 = 18                                      //
		// Minimum coins = 0                                               //
		//                                                                 //
		// If it's known that Player-i takes 3, then max = 18, min = 3     //
		// If it's known that Player-i takes 2, then max = 17, min = 2     //
		// If it's known that Player-i takes 1, then max = 16, min = 1     //
		// If it's known that Player-i takes 0, then max = 15, min = 0     //
		//                                                                 //
		// So for a known N = number of coins of player-i,                 //
		// min = N                                                         //
		// max = ( P * C ) - ( C - N )                                     //
		//-----------------------------------------------------------------//
		
		$numberOfPlayers = $innerGame->getPlayers()->count();
		$player = $desire->getSubject();
		$desires = $innerGame->getDesires();
		$pickDesire = $this->getPickDesireBySubject( $desires, $player );
		
		$p = $numberOfPlayers;
		$c = $innerGame->getElement( 'maxCoinsPerPlayer' );
		$n = $pickDesire->getArgument( 'coins' );
		
		if( $desire->getVerb() != 'say' )
		{
			throw new \RuntimeException( "Cannot use a non 'say' verb in processLiar()." );
		}
		
		$said = $desire->getArgument( 'number' );
		
		$min = $n;
		$max = ( $p * $c ) - ( $c - $n );
		
		$liarBecauseOfExceed = $said > $max;
		$liarBecauseOfDefault = $said < $min;
		
		$liar = ( $liarBecauseOfExceed || $liarBecauseOfDefault );
		
		if( $liar )
		{
			$player->setTag( 'liar' );
		}
	}
}
