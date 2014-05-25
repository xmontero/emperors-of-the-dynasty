<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\Items\Chest;

class ChestTest extends \PHPUnit_Framework_TestCase
{
	private $sutPiece;
	
	public function setup()
	{
		$this->sutPiece = new Chest;
	}
	
	public function testType()
	{
		$this->assertEquals( 'chest', $this->sutPiece->getType() );
	}
	
	public function testIsOpen()
	{
		$sutPiece = $this->sutPiece;
		
		$this->assertFalse( $sutPiece->isOpen() );
		$sutPiece->open();
		$this->assertTrue( $sutPiece->isOpen() );
		$sutPiece->close();
		$this->assertFalse( $sutPiece->isOpen() );
	}
}
