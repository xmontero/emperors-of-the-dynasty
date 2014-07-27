<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\Rules;

use Xmontero\TurnBasedGame\Bundle\Model;

class GameAbortsAfterTooManyTurns implements Model\Interfaces\GameObserver
{
	private $limit;
	
	public function __construct( $limit )
	{
		$this->limit = $limit;
	}
	
	public function onGamePreOpenTurn( $game, $turnAboutToBeOpen, & $cancel )
	{
		if( $turnAboutToBeOpen > $this->limit )
		{
			$cancel = true;
			$game->abort();
		}
	}
}
