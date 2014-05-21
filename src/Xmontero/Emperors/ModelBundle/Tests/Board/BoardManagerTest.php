<?php

use Xmontero\Emperors\ModelBundle\Model\Board;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	
	public function setup()
	{
		$this->sut = new Board\BoardManager( null );
	}
	
	public function teardown()
	{
		unset( $this->sut );
	}
	
	public function testCreateBoardFromScratch()
	{
		$board = $this->sut->createBoardFromScratch( 10, 5 );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Board\Board', $board );
		$this->assertEquals( 10, $board->getWidth() );
		$this->assertEquals( 5, $board->getHeight() );
		$this->assertEquals( 0, $board->getPieces()->count() );
	}
	
	public function testLoadBoardFromJson()
	{
		$document = '{ "width": 4, "height": 8 }';
		$board = $this->sut->loadBoardFromJson( $document );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Board\Board', $board );
		$this->assertEquals( 4, $board->getWidth() );
		$this->assertEquals( 8, $board->getHeight() );
		$this->assertEquals( 0, $board->getPieces()->count() );
	}
	
	public function testLoadBoardFromJsonFailsWithInvalidJson()
	{
		$document = "{ 'width': 4, 'height': 8 }";	// Must be double quotes.
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$board = $this->sut->loadBoardFromJson( $document );
	}
}
