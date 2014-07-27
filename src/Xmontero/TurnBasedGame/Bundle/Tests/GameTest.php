<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests;

use Xmontero\TurnBasedGame\Bundle\Model;

class GameTest extends \PHPUnit_Framework_TestCase
{
	private $sutGame;
	
	public function setup()
	{
		$this->sutGame = new Model\Game;
	}
	
	public function testAddPlayer()
	{
		$expectedPlayers = $this->initGameWithPlayers( 2 );
		$sutGame = $this->sutGame;
		
		$foundPlayers = $sutGame->getPlayers();
		
		$this->assertSame( $expectedPlayers[ 0 ], $foundPlayers[ 0 ] );
		$this->assertSame( $expectedPlayers[ 1 ], $foundPlayers[ 1 ] );
	}
	
	public function testStart()
	{
		$expectedPlayers = $this->initGameWithPlayers( 2 );
		
		$mockRule = $this->getMock( '\Xmontero\TurnBasedGame\Bundle\Model\Null\FullRule' );
		
		$mockRule->expects( $this->once() )
			->method( 'onGamePreStart' );
		
		$mockRule->expects( $this->once() )
			->method( 'onGamePostStart' );
		
		$this->sutGame->addRule( $mockRule );
		$result = $sutGame = $this->sutGame->start();
		
		$this->assertTrue( $result );
		$this->assertEquals( 1, $this->sutGame->getTurn() );
	}
	
	public function testStartFailsIfNotExpectedPlayers()
	{
		$player1 = new Helpers\NullPlayer;
		$player2 = new Helpers\NullPlayer;
		$player3 = new Helpers\NullPlayer;
		
		$sutGame = new Model\Game;
		
		// Minimum 2 players
		
		$rule = new Rules\GameRequiresNumberOfPlayersToStart;
		$rule->configureMinimumNumberOfPlayers( 2 );
		$sutGame->addRule( $rule );
		
		$expectedPlayers = $sutGame->addPlayer( 1, $player1 );
		$result = $sutGame->start( true );
		$this->assertFalse( $result );
		$this->assertEquals( 0, $sutGame->getTurn() );
		
		$result = $sutGame->start();
		$this->assertFalse( $result );
		$this->assertEquals( 0, $sutGame->getTurn() );
		
		$expectedPlayers = $sutGame->addPlayer( 2, $player2 );
		$result = $sutGame->start( true );
		$this->assertTrue( $result );
		$this->assertEquals( 0, $sutGame->getTurn() );
		
		$result = $sutGame->start();
		$this->assertTrue( $result );
		$this->assertEquals( 1, $sutGame->getTurn() );
		
		// Maximum 2 players
		
		$sutGame = new Model\Game;
		
		$rule = new Rules\GameRequiresNumberOfPlayersToStart;
		$rule->configureMaximumNumberOfPlayers( 2 );
		$sutGame->addRule( $rule );
		
		$expectedPlayers = $sutGame->addPlayer( 1, $player1 );
		$expectedPlayers = $sutGame->addPlayer( 2, $player2 );
		$expectedPlayers = $sutGame->addPlayer( 3, $player3 );
		$result = $sutGame->start();
		$this->assertFalse( $result );
		$this->assertEquals( 0, $sutGame->getTurn() );
	}
	
	//-- Private ----------------------------------------------------------//
	
	private function initGameWithPlayers( $numberOfPlayers )
	{
		$players = array();
		
		for( $i = 0; $i < $numberOfPlayers; $i++ )
		{
			$player = new Helpers\NullPlayer;
			
			$this->sutGame->addPlayer( $i, $player );
			$players[] = $player;
		}
		
		return $players;
	}
}
