<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Game;

use Xmontero\Emperors\ModelBundle\Model;

class GameManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sutGameManager;
	private $boardManagerStub;
	private $pieceManagerStub;
	private $objectStorageManagerStub;
	
	public function setup()
	{
		$boardManagerStub = $this->getMockBuilder( 'Xmontero\Emperors\ModelBundle\Model\Board\BoardManager' )
			->disableOriginalConstructor()
			->getMock();
		
		$pieceManagerStub = $this->getMockBuilder( 'Xmontero\Emperors\ModelBundle\Model\Pieces\PieceManager' )
			->disableOriginalConstructor()
			->getMock();
		
		$objectStorageManagerStub = $this->getMockBuilder( 'Xmontero\Emperors\ModelBundle\Model\ObjectStorage\ObjectStorageManager' )
			->disableOriginalConstructor()
			->getMock();
		
		$this->boardManagerStub = $boardManagerStub;
		$this->pieceManagerStub = $pieceManagerStub;
		$this->objectStorageManagerStub = $objectStorageManagerStub;
		$this->sutGameManager = new Model\Game\GameManager( $boardManagerStub, $pieceManagerStub, $objectStorageManagerStub );
	}
	
	public function teardown()
	{
		unset( $this->sutGameManager );
		unset( $this->objectStorageManagerStub );
		unset( $this->boardManagerStub );
	}
	
	public function testGetAllOpenGameIds()
	{
		$games = $this->sutGameManager->getAllOpenGameIds();
		$this->assertInstanceOf( 'ArrayObject', $games );
	}
	
	public function testGetOpenGames()
	{
		$games = $this->sutGameManager->getOpenGames( 5 );
		$this->assertInstanceOf( 'ArrayObject', $games );
	}
	
	public function testGetGameById()
	{
		$game = $this->sutGameManager->getGameById( 1 );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Game\Game', $game );
	}
}
