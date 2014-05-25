<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens\Pawn;

class PawnTest extends \PHPUnit_Framework_TestCase
{
	private $sutPiece;
	
	public function setup()
	{
		$this->sutPiece = new Pawn;
	}
	
	public function testType()
	{
		$this->assertEquals( 'pawn', $this->sutPiece->getType() );
	}
}
