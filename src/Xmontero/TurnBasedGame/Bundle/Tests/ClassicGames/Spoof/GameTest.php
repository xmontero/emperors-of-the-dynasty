<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\ClassicGames\Spoof;

use Xmontero\TurnBasedGame\Bundle\Model;

//-------------------------------------------------------------------------//
// Spoof (in Spain known as "Los Chinos") is a game in which n players     //
// pick 0, 1, 2 or 3 coins or stones (can be a number of arbitrary M       //
// coins) and the players have to guess which is the total number of coins //
// selected by all of them.                                                //
//                                                                         //
// Gameplay: In each turn, each player picks "in secret" the coins and     //
// once done, extends the arm so nobody can change it later. Then each     //
// player says a number, numbers cannot be repeated. If any player guesses //
// the number, wins and is eliminated. If nobody guesses nobody is         //
// eliminated.                                                             //
//                                                                         //
// The game repeats until there is only one player left and the looser is  //
// normally who has to pay in a bar or restaurant or any other penalty.    //
//                                                                         //
// In this test, the SUT will be the Game engine, to to see if it is       //
// capable to handle a set of matches, fed thru a data provider.           //
//                                                                         //
// We will use the combination of 2 nested Game engines. One (outer loop)  //
// for making the turns that "reduce one person at a time", the other      //
// (inner loop) to do the specific game-play at n players to reduce to n-1.//
//                                                                         //
// There is no board involved in this game.                                //
//                                                                         //
// http://en.wikipedia.org/wiki/Spoof_%28game%29                           //
//-------------------------------------------------------------------------//

class GameTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider providerGame
	 */
	public function testGame( $gameSetup, $gameDesires, $expectedLooserId, $expectedEndOfGameTurn )
	{
		//echo PHP_EOL . "++++++++++++++++++++++++++++++ NEW GAME +++++++++" . PHP_EOL;
		
		$gameManager = new Model\GameManager;
		$numberOfPlayers = $gameSetup[ 'numberOfPlayers' ];
		$maxCoinsPerPlayer = $gameSetup[ 'maxCoinsPerPlayer' ];
		
		$spoof = new Spoof( $gameManager );
		$spoof->run( $numberOfPlayers, $maxCoinsPerPlayer, $gameDesires );
		
		$gameLooserId = $spoof->getLooserId();
		$gameEndOfGameTurn = $spoof->getEndOfGameTurn();
		
		$this->assertEquals( $expectedLooserId, $gameLooserId );
		$this->assertEquals( $expectedEndOfGameTurn, $gameEndOfGameTurn );
	}
	
	public function providerGame()
	{
		// gameSetup is definition of number of players, number of coins, etc.
		// gameDesires is 2 pseudorandom sequences per user: Numbers that
		//   he picks, numbers that he says. Numbers are extracted in order
		//   so on repeat turn, or repeat desire, the next one is extracted.
		//   numbers are designed to represent the games below.
		// expectedLooser: The number of the player to be found as looser.
		
		$game1 = $this->providerGame1();
		$game2 = $this->providerGame2();
		
		return array
		(
			$game1,
			$game2,
		);
	}
	
	public function providerGame1()
	{
		$gameSetup = array
		(
			'numberOfPlayers' => 2,
			'maxCoinsPerPlayer' => 3,
		);
		
		$player1PickDesires = array( 2, 5, 0, 2, 2, 0, 0, 0 );
		$player1SayDesires  = array( 2, 1, 2, 3, 2, 0, 0, 0 );
		$player2PickDesires = array( 3, 3, 3, 2, 3, 0, 0, 0 );
		$player2SayDesires  = array( 4, 4, 5, 3, 1, 5, 0, 0 );
		
		$player1Desires = array
		(
			'pick' => $player1PickDesires,
			'say' => $player1SayDesires,
		);
		
		$player2Desires = array
		(
			'pick' => $player2PickDesires,
			'say' => $player2SayDesires,
		);
		
		$gameDesires = array();
		$gameDesires[ 1 ] = $player1Desires;
		$gameDesires[ 2 ] = $player2Desires;
		
		$expectedLooserId = 1;
		$expectedEndOfGameTurn = 1;
		
		return array
		(
			$gameSetup,
			$gameDesires,
			$expectedLooserId,
			$expectedEndOfGameTurn,
		);
	}
	
	public function providerGame2()
	{
		$gameSetup = array
		(
			'numberOfPlayers' => 4,
			'maxCoinsPerPlayer' => 2,
		);
		
		$player1PickDesires = array( 2, 1, 1, 0, 2, 0, 0, 0 );
		$player1SayDesires  = array( 5, 4, 2, 1, 4, 0, 0, 0 );
		$player2PickDesires = array( 0, 0, 0, 0, 0, 0, 0, 0 );
		$player2SayDesires  = array( 3, 4, 2, 3, 0, 0, 0, 0 );
		$player3PickDesires = array( 1, 2, 1, 1, 1, 0, 0, 0 );
		$player3SayDesires  = array( 1, 6, 6, 2, 2, 0, 0, 0 );
		$player4PickDesires = array( 1, 2, 1, 2, 1, 0, 0, 0 );
		$player4SayDesires  = array( 2, 8, 5, 2, 4, 6, 0, 0 );
		
		$player1Desires = array
		(
			'pick' => $player1PickDesires,
			'say' => $player1SayDesires,
		);
		
		$player2Desires = array
		(
			'pick' => $player2PickDesires,
			'say' => $player2SayDesires,
		);
		
		$player3Desires = array
		(
			'pick' => $player3PickDesires,
			'say' => $player3SayDesires,
		);
		
		$player4Desires = array
		(
			'pick' => $player4PickDesires,
			'say' => $player4SayDesires,
		);
		
		$gameDesires = array();
		$gameDesires[ 1 ] = $player1Desires;
		$gameDesires[ 2 ] = $player2Desires;
		$gameDesires[ 3 ] = $player3Desires;
		$gameDesires[ 4 ] = $player4Desires;
		
		$expectedLooserId = 4;
		$expectedEndOfGameTurn = 2;
		
		return array
		(
			$gameSetup,
			$gameDesires,
			$expectedLooserId,
			$expectedEndOfGameTurn,
		);
	}
}

/*
==================================================
TEST #1

2 people, 3 coins, liar looses

--------------------------------------------------
Outer #1, with 2 players = P1, P2.

Test #1, Outer #1, Inner #1
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   2   |  2   |
|   P2   |   3   |  4   |
+--------+-------+------+
Inner Outcome => Nothing

Test #1, Outer #1, Inner #2
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   5   |  1   |
|   P2   |   3   |  4   |
+--------+-------+------+
|   P1   |   0   |  2   |
|   P2   |   3   |  5   |
+--------+-------+------+
Player 1 picks 5 so, when the quantity is disclosed, the
turn cannot be closed as it is invalid and needs to be played again.
-> Can also be seen as the Desire is derictly rejected by the system
but we want to test the "pre close turn" event.
Inner Outcome => Nothing

Test #1, Outer #1, Inner #3
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   2   |  3   |
|   P2   |   2   |  3   |
|   P2   |       |  1   |
+--------+-------+------+
P1, picks 2, says 3
P2, picks 2, says 3 => should be rejected because of "number already said"
-> We will test the "pre add desire" event here.
P2, says 5
Inner Outcome => Nothing

Test #1, Outer #1, Inner #4
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   2   |  2   |
|   P2   |   3   |  5   |
+--------+-------+------+
Inner Outcome => P2 is labelled "winner"
Outer Outcome => No more players left, so P1 labelled "looser"

==================================================

TEST 2

4 people, 2 coins, liar looses

--------------------------------------------------
Outer #1, with 4 players = P1, P2, P3, P4.

Test #2, Outer #1, Inner #1
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   2   |  5   |
|   P2   |   0   |  3   |
|   P3   |   1   |  1   |
|   P4   |   1   |  2   |
+--------+-------+------+
Inner Outcome => Nothing

Test #2, Outer #1, Inner #2
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   1   |  4   |
|   P2   |   0   |  4   |
|        |       |  2   |
|   P3   |   2   |  6   |
|   P4   |   2   |  8   |
+--------+-------+------+
P2 desire is invalid, as desire "4" is repeated (previously said by P1).
Inner Outcome => Nothing

Test #2, Outer #1, Inner #3
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   1   |  2   |
|   P2   |   0   |  3   |
|   P3   |   1   |  6   |
|   P4   |   1   |  5   |
+--------+-------+------+
Inner Outcome => Player P2 is labelled "winner"
Outer outcome => P2 is eliminated and another game is organized with P1, P3, and P4

--------------------------------------------------
Outer #2, with 3 players = P1, P3, P4

Test #2, Outer #1, Inner #1
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   0   |  1   |
|   P3   |   1   |  2   |
|   P4   |   2   |  2   |
|        |       |  4   |
+--------+-------+------+
P4 says 2, but that number was already said. Then says 3.
Inner Outcome => Nothing

Test #2, Outer #1, Inner #2
+--------+-------+------+
| Player | Picks | Says |
+--------+-------+------+
|   P1   |   2   |  4   |
|   P3   |   1   |  2   |
|   P4   |   1   |  6   |
+--------+-------+------+
Inner Outcome => Player P4 is a liar, because with 1 coins it is
impossible to get a result of 6, the maximum would be 5.
So 4 he is marked as "liar".
Outer outcome => "liar" found, mark as "looser" and terminate.

*/