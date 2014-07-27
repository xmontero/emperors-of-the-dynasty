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
		$this->createBag( $innerGame );
	}
	
	public function onGamePostOpenTurn( $innerGame, $turn )
	{
		$this->resetBag( $innerGame );
	}
	
	public function onGamePreAddDesire( $innerGame, $newDesire, $cancel )
	{
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
		$verb = $desire->getVerb();
		
		if( $verb == 'pick' )
		{
			$pickNumber = $desire->getArgument( 'coins' );
			$this->addCoinsToBag( $innerGame, $pickNumber );
		}
	}
	
	public function onGameTick( $innerGame )
	{
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
			do
			{
				$say = $player->getSay();
				
				$desire = new Desire;
				$desire->setSubject( $player );
				$desire->setPredicate( 'say', array( 'number' => $say ) );
				
				//echo( 'Player ' . $playerId . ' desires to say ' . $say );
				$couldAdd = $innerGame->addDesire( $desire );
				//echo( ' -- desire ' . ( $couldAdd ? 'accepted' : 'rejected' ) . PHP_EOL );
			}
			while( ! $couldAdd );
		}
	}
	
	public function onGamePreCloseTurn( $innerGame, $turn, $cancel )
	{
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
		$coinsInTheBag = $innerGame->getElement( 'coinsInTheBag' );
		$desires = $innerGame->getDesires();
		
		$matchingSayDesire = $this->getMatchingSayDesire( $coinsInTheBag, $desires );
		
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
	
	private function getMatchingSayDesire( $coinsInTheBag, $desires )
	{
		$matchingDesire = null;
		
		foreach( $desires as $desire )
		{
			$verb = $desire->getVerb();
			
			if( $verb == 'say' )
			{
				$number = $desire->getArgument( 'number' );
				
				if( $number == $coinsInTheBag )
				{
					$matchingDesire = $desire;
					break;
				}
			}
		}
		
		return $matchingDesire;
	}
}
