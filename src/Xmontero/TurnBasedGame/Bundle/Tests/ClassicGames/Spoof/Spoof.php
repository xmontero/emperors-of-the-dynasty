<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\ClassicGames\Spoof;

use Xmontero\TurnBasedGame\Bundle\Model;
use Xmontero\TurnBasedGame\Bundle\Tests\Rules;

class Spoof
{
	//---------------------------------------------------------------------//
	// Internal state                                                      //
	//---------------------------------------------------------------------//
	
	private $gameManager = null;
	private $players = null;
	private $looserId = null;
	private $endOfGameTurn = null;
	private $outerGame = null;
	
	//---------------------------------------------------------------------//
	// Constructors and destructors                                        //
	//---------------------------------------------------------------------//
	
	public function __construct( $gameManager )
	{
		$this->gameManager = $gameManager;
		$this->players = new \ArrayObject;
	}
	
	//---------------------------------------------------------------------//
	// Properties                                                          //
	//---------------------------------------------------------------------//
	
	public function getLooserId()
	{
		return $this->looserId;
	}
	
	public function getEndOfGameTurn()
	{
		return $this->endOfGameTurn;
	}
	
	//---------------------------------------------------------------------//
	// Public methods                                                      //
	//---------------------------------------------------------------------//
	
	public function run( $numberOfPlayers, $maxCoinsPerPlayer, $gameDesires )
	{
		//-----------------------------------------------------------------//
		// The spoof game contains two step-engines.                       //
		// If you have 5 players, the "outer game" is one step per player  //
		// reduction. Ie: It will have 4 steps, and in step 1 will play    //
		// 5 players, one be marked as winner, so 4 will be left to play   //
		// again. In step 2, the outer game will have 4 players. In step   //
		// 3 will have 3 players. In step 4 will have 2 players. Once the  //
		// step 4 finishes, again a player will be marked as winner, and   //
		// removed from the pool of players. The game will see there is    //
		// only one player left, who cannot play against anyone, so will   //
		// be marked as looser.                                            //
		// For each "external step", an "inner game" will be created and   //
		// initialized. The inner game is the one that at each step allows //
		// a user to "say" a number and "pick" a nbumber of coins per turn.//
		//-----------------------------------------------------------------//
		
		$this->initOuterGameRulesAndPlayers( $maxCoinsPerPlayer, $numberOfPlayers, $gameDesires );
		$this->outerGame->run();
		$this->processOuterResults();
	}
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
	//-- Pre-run ----------------------------------------------------------//
	
	private function initOuterGameRulesAndPlayers( $maxCoinsPerPlayer, $numberOfPlayers, $gameDesires )
	{
		$this->initOuterGame();
		$this->initOuterRules( $maxCoinsPerPlayer );
		$this->initOuterPlayers( $numberOfPlayers, $gameDesires );
	}
	
	private function initOuterGame()
	{
		$this->outerGame = $this->gameManager->createNewGame();
		$this->outerGame->addElement( 'gameManager', $this->gameManager );
	}
	
	private function initOuterRules( $maxCoinsPerPlayer )
	{
		$outerLimitRule = new Rules\GameAbortsAfterTooManyTurns( 6 );
		$spoofOuterRules = new SpoofOuterRules;
		
		$this->outerGame->addRule( $outerLimitRule );
		$this->outerGame->addRule( $spoofOuterRules );
		
		$this->outerGame->addElement( 'maxCoinsPerPlayer', $maxCoinsPerPlayer );
	}
	
	private function initOuterPlayers( $numberOfPlayers, $gameDesiresOfAllPlayers )
	{
		for( $i = 1; $i <= $numberOfPlayers; $i++ )
		{
			$this->initOuterPlayer( $i, $gameDesiresOfAllPlayers[ $i ] );
		}
	}
	
	private function initOuterPlayer( $playerId, $gameDesiresOfASinglePlayer )
	{
		$player = new SpoofPlayer( $gameDesiresOfASinglePlayer );
		$this->players[ $playerId ] = $player;
		$this->outerGame->addPlayer( $playerId, $player );
	}
	
	//-- Post-run ---------------------------------------------------------//
	
	private function processOuterResults()
	{
		$this->doStoreLooser();
		$this->doStoreGameTurn();
	}
	
	private function doStoreLooser()
	{
		$looserIds = $this->getLoosers();
		$loosersCount = $looserIds->count();
		
		if( $loosersCount == 1 )
		{
			$this->looserId = $looserIds->offsetGet( 0 );
		}
		else
		{
			throw new \RuntimeException( 'Found ' . $loosersCount . ' instead of 1 looser.' );
		}
	}
	
	private function getLoosers()
	{
		$looserIds = new \ArrayObject;
		
		foreach( $this->players as $key => $player )
		{
			if( $player->getTag() == 'looser' )
			{
				$looserIds->append( $key );
			}
		}
		
		return $looserIds;
	}
	
	private function doStoreGameTurn()
	{
		$this->endOfGameTurn = $this->outerGame->getTurn();
	}
}
