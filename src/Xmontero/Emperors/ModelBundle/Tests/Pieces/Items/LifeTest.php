<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\Items\Life;

class LifeTest extends \PHPUnit_Framework_TestCase
{
	private $sutPiece;
	
	public function setup()
	{
		$this->sutPiece = new Life;
	}
	
	public function testType()
	{
		$this->assertEquals( 'life', $this->sutPiece->getType() );
	}
}
