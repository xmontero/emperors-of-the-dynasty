<?php

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	
	public function setup()
	{
		$this->sut = new Model\Game\BoardManager( null );
	}
	
	public function teardown()
	{
		unset( $this->sut );
	}
	
	public function testCreateBoardFromScratch()
	{
		$board = $this->sut->createBoardFromScratch( 10, 5 );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Game\Board', $board );
		$this->assertEquals( 10, $board->getWidth() );
		$this->assertEquals( 5, $board->getHeight() );
		$this->assertEquals( 0, $board->getItemsAndPieces()->count() );
	}
}
