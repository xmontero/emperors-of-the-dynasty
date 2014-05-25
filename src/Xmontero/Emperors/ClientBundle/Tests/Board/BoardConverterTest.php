<?php
namespace Xmontero\Emperors\ClientBundle\Tests\Board\BoardConverter;

use Xmontero\Emperors\ClientBundle\Model\Board;
use Xmontero\Emperors\ModelBundle\Model\Board\Board as ModelBoard;

class BoardConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvert()
	{
		$modelBoard = new ModelBoard( 6, 4 );
		$sutBoardConverter = new Board\BoardConverter();
		
		$clientBoard = $sutBoardConverter->convert( $modelBoard );
		$this->assertEquals( 6, $clientBoard[ 'width' ] );
		$this->assertEquals( 4, $clientBoard[ 'height' ] );
	}
}
