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
	
	public function testType()
	{
		$this->assertEquals( 'emperor', $this->sutPiece->getType() );
	}
}
