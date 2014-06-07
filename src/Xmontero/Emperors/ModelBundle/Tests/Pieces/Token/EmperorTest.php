<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens\Emperor;

class EmperorTest extends \PHPUnit_Framework_TestCase
{
	private $sutPiece;
	
	public function setup()
	{
		$this->sutPiece = new Emperor( 1 );
	}
	
	public function testCreatorWithNonIntegerThrowsException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$emperor = new Emperor( 'foo' );
	}
	
	public function testType()
	{
		$this->assertEquals( 'emperor', $this->sutPiece->getType() );
	}
	
	public function testExperience()
	{
		$this->sutPiece->setExperience( 555 );
		$this->assertEquals( 555, $this->sutPiece->getExperience() );
	}
	
	public function testUsableExperience()
	{
		$this->sutPiece->setUsableExperience( 444 );
		$this->assertEquals( 444, $this->sutPiece->getUsableExperience() );
	}
	
	public function testLiveQuarters()
	{
		$this->sutPiece->setLiveQuarters( 333 );
		$this->assertEquals( 333, $this->sutPiece->getLiveQuarters() );
	}
}
