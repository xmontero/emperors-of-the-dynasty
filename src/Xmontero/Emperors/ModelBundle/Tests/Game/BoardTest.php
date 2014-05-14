<?php

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardTest extends \PHPUnit_Framework_TestCase
{
	public function setup()
	{
	}
	
	public function teardown()
	{
	}
	
	public function testBoard()
	{
		$sut = new Model\Game\Board( null, null, 10, 5 );
		$this->assertEquals( 10, $sut->getWidth() );
		$this->assertEquals( 5, $sut->getHeight() );
	}
	
	public function testGetTile()
	{
		$sut = new Model\Game\Board( null, null, 10, 5 );
		$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Game\Tile', $sut->getTile( 3, 3 ) );
	}
}
