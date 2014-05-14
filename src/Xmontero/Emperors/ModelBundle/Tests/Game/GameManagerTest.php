<?php

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $loggerStub;
	private $objectStorageManagerStub;
	
	public function setup()
	{
		$loggerStub = $this->getMockBuilder( 'Logger' )
			->disableOriginalConstructor()
			->getMock();
		
		$objectStorageManagerStub = $this->getMockBuilder( 'Xmontero\Emperors\ModelBundle\Model\ObjectStorage\ObjectStorageManager' )
			->disableOriginalConstructor()
			->getMock();
		
		$this->loggerStub = $loggerStub;
		$this->objectStorageManagerStub = $objectStorageManagerStub;
		$this->sut = new Model\Game\GameManager( $loggerStub, $objectStorageManagerStub );
	}
	
	public function teardown()
	{
		unset( $this->sut );
		unset( $this->objectStorageManagerStub );
		unset( $this->loggerStub );
	}
	
	public function testGetAllOpenGameIds()
	{
		$games = $this->sut->getAllOpenGameIds();
		$this->assertInstanceOf( 'ArrayObject', $games );
	}
	
	public function testGetOpenGames()
	{
		$games = $this->sut->getOpenGames( 5 );
		$this->assertInstanceOf( 'ArrayObject', $games );
	}
	
	public function testGetGameById()
	{
		$game = $this->sut->getGameById( 1 );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Game\Game', $game );
	}
}
