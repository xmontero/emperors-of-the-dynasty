<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Board;

use Xmontero\Emperors\ModelBundle\Model\Board;

class BoardManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	
	const boardClass = 'Xmontero\Emperors\ModelBundle\Model\Board\Board';
	
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
		$this->assertInstanceOf( self::boardClass, $board );
		$this->assertEquals( 10, $board->getWidth() );
		$this->assertEquals( 5, $board->getHeight() );
		$this->assertEquals( 0, $board->getPieces()->count() );
	}
	
	public function testLoadBoardFromJson()
	{
		$document = file_get_contents( __DIR__ . '/Data/BoardTestLoad.json' );
		$board = $this->sut->loadBoardFromJson( $document );
		$this->assertInstanceOf( self::boardClass, $board );
		$this->assertEquals( 9, $board->getWidth() );
		$this->assertEquals( 12, $board->getHeight() );
		$this->assertEquals( 0, $board->getPieces()->count() );
	}
	
	public function testLoadBoardFromTemplate()
	{
		$board = $this->sut->loadBoardFromTemplate( 'uukhumaki' );
		$this->assertInstanceOf( self::boardClass, $board );
		$this->assertEquals( 14, $board->getWidth() );
		$this->assertEquals( 14, $board->getHeight() );
		$this->assertTrue( $board->getTile( 1, 1 )->isOffBoard() );
		$this->assertFalse( $board->getTile( 5, 1 )->isOffBoard() );
		$this->assertFalse( $board->getTile( 14, 10 )->isOffBoard() );
		$this->assertTrue( $board->getTile( 14, 14 )->isOffBoard() );
	}
	
	public function testLoadBoardFromJsonFailsWithInvalidJson()
	{
		$document = "{ 'width': 4, 'height': 8 }";	// Must be double quotes.
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$board = $this->sut->loadBoardFromJson( $document );
	}
}
