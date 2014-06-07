<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\Rules;

use Xmontero\TurnBasedGame\Bundle\Model\Interfaces\GameObserver;

class GameRequiresNumberOfPlayersToStart implements GameObserver
{
	private $comparison;
	private $numberOfRequiredPlayers;
	
	public function configureMinimumNumberOfPlayers( $numberOfRequiredPlayers )
	{
		$this->numberOfRequiredPlayers = $numberOfRequiredPlayers;
		$this->comparison = 'minimum';
	}
	
	public function configureMaximumNumberOfPlayers( $numberOfRequiredPlayers )
	{
		$this->numberOfRequiredPlayers = $numberOfRequiredPlayers;
		$this->comparison = 'maximum';
	}
	
	//-- Event handlers ---------------------------------------------------//
	
	public function onGamePreStart( $game, & $cancel )
	{
		$players = $game->getPlayers();
		
		switch( $this->comparison )
		{
			case 'minimum':
				
				$cancel = ( $players->count() < $this->numberOfRequiredPlayers );
				break;
				
			case 'maximum':
				
				$cancel = ( $players->count() > $this->numberOfRequiredPlayers );
				break;
				
			default:
				
				throw new \DomainException;
				break;
		}
	}
}
