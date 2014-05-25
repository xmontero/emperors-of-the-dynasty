<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\Items\Wealth;

class WealthTest extends \PHPUnit_Framework_TestCase
{
	private $sutPiece;
	
	public function setup()
	{
		$this->sutPiece = new Wealth;
	}
	
	public function testType()
	{
		$this->assertEquals( 'wealth', $this->sutPiece->getType() );
	}
}
