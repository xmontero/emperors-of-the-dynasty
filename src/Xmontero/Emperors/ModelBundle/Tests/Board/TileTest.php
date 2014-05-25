<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Board;

use Xmontero\Emperors\ModelBundle\Model\Board\Tile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TileTest extends \PHPUnit_Framework_TestCase
{
	public function testOffOnBoard()
	{
		$sutTile = new Tile;
		
		$this->assertFalse( $sutTile->isOffBoard() );
		$this->assertTrue( $sutTile->isOnBoard() );
		
		$sutTile->setOffBoard();
		$this->assertTrue( $sutTile->isOffBoard() );
		$this->assertFalse( $sutTile->isOnBoard() );
		
		$sutTile->setOnBoard();
		$this->assertFalse( $sutTile->isOffBoard() );
		$this->assertTrue( $sutTile->isOnBoard() );
	}
	
	public function testGetSetProperty()
	{
		$sutTile = new Tile;
		
		$object = new \StdClass;
		$object->qux = 'quux';
		
		$sutTile->setProperty( 'foo', 'bar' );
		$sutTile->setProperty( 'baz', $object );
		
		$propFoo = $sutTile->getProperty( 'foo' );
		$propBaz = $sutTile->getProperty( 'baz' );
		
		$this->assertEquals( 'bar', $propFoo );
		$this->assertInstanceOf( '\StdClass', $propBaz );
		$this->assertEquals( 'quux', $propBaz->qux );
	}
	
	public function testNullPropertyThrowsException()
	{
		$sutTile = new Tile;
		$this->setExpectedException( 'DomainException' );
		$sutTile->getProperty( 'foo' );
	}
	
	public function testPropertyExists()
	{
		$key = 'foo';
		$sutTile = new Tile;
		$sutTile->setProperty( $key, 'bar' );
		$this->assertTrue( $sutTile->propertyExists( $key ) );
	}
	
	public function testPropertyDoesNotExist()
	{
		$key = 'foo';
		$sutTile = new Tile;
		$this->assertFalse( $sutTile->propertyExists( $key ) );
	}
	
	public function testSetPropertyOnOffBoardThrowsException()
	{
		$key = 'foo';
		$sutTile = new Tile;
		
		$this->setExpectedException( 'RuntimeException' );
		$sutTile->setOffBoard();
		$sutTile->setProperty( 'foo', 'bar' );
	}
}
