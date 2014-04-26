<?php

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameManagerTest extends \PHPUnit_Framework_TestCase
{
	public function testGetAllOpenGameIds()
	{
		$sut = new Model\Game\GameManager;
		$games = $sut->getAllOpenGameIds();
		
		$this->assertInstanceOf( 'ArrayObject', $games );
	}
	
	public function testGetGameById()
	{
		$sut = new Model\Game\GameManager;
		$game = $sut->getGameById( 1 );
		
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Game\Game', $game );
	}
}
