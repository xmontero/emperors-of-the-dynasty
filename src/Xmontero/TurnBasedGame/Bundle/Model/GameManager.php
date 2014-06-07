<?php

namespace Xmontero\TurnBasedGame\Bundle\Model;

class GameManager
{
	public function createNewGame()
	{
		$result = new Game;
		return $result;
	}
}
