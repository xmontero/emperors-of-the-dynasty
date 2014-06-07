<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests;

use Xmontero\TurnBasedGame\Bundle\Model;

class GameManagerTest extends \PHPUnit_Framework_TestCase
{
	public function testCreateNewGame()
	{
		$sutGameManager = new Model\GameManager;
		$game = $sutGameManager->createNewGame();
		$this->assertInstanceOf( 'Xmontero\TurnBasedGame\Bundle\Model\Game', $game );
	}
}
