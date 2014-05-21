<?php

use Xmontero\Emperors\ModelBundle\Model\Board\Tile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TileTest extends \PHPUnit_Framework_TestCase
{
	public function testValidProperty()
	{
		$sut = new Tile;
		
		$object = new \StdClass;
		$object->nano = 'pico';
		
		$sut->setProperty( 'abc', 'xyz' );
		$sut->setProperty( '123', $object );
		
		$propAbc = $sut->getProperty( 'abc' );
		$prop123 = $sut->getProperty( '123' );
		
		$this->assertEquals( 'xyz', $propAbc );
		$this->assertInstanceOf( '\StdClass', $prop123 );
		$this->assertEquals( 'pico', $prop123->nano );
	}
	
	public function testNullProperty()
	{
		$sut = new Tile;
		$this->setExpectedException( 'DomainException' );
		$sut->getProperty( 'abc' );
	}
	
	public function testPropertyExists()
	{
		$key = 'abc';
		$sut = new Tile;
		$sut->setProperty( $key, 'xyz' );
		$this->assertTrue( $sut->propertyExists( $key ) );
	}
	
	public function testPropertyDoesNotExist()
	{
		$key = 'abc';
		$sut = new Tile;
		$this->assertFalse( $sut->propertyExists( $key ) );
	}
}
