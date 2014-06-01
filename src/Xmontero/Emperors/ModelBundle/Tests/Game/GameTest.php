<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Game;

use Xmontero\Emperors\ModelBundle\Model;

class GameTest extends \PHPUnit_Framework_TestCase
{
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
	}
	
	public function teardown()
	{
		unset( $this->objectStorageManagerStub );
		unset( $this->boardManagerStub );
	}
	
	public function testGetStartDate()
	{
		$sut = new Model\Game\Game( 1, $this->boardManagerStub, $this->pieceManagerStub, $this->objectStorageManagerStub );
		$startDate = $sut->getStartDate();
		
		$this->assertInstanceOf( '\DateTime', $startDate );
	}
}
