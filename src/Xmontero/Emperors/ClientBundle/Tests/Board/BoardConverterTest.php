<?php
namespace Xmontero\Emperors\ClientBundle\Tests\Board\BoardConverter;

use Xmontero\Emperors\ClientBundle\Model\Board;
use Xmontero\Emperors\ModelBundle\Model\Board\Board as ModelBoard;
use Xmontero\Emperors\ModelBundle\Model\Pieces;
use Xmontero\Emperors\ModelBundle\Model\Players;

class BoardConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvert()
	{
		$modelBoard = new ModelBoard( 6, 4 );
		$sutBoardConverter = new Board\BoardConverter();
		
		$emperor = new Pieces\Tokens\Emperor( 1 );
		$pawn = new Pieces\Tokens\Pawn( 2 );
		$chest = new Pieces\Items\Chest( 2 );
		$life = new Pieces\Items\Life;
		$wealth = new Pieces\Items\Wealth;
		
		$chest->setId( 2 );
		
		$modelBoard->getTile( 1, 1 )->setOffBoard();
		$modelBoard->getTile( 2, 2 )->attachVisiblePiece( $emperor );
		$modelBoard->getTile( 2, 3 )->attachHiddenPiece( $emperor );
		$modelBoard->getTile( 3, 2 )->attachVisiblePiece( $chest );
		$modelBoard->getTile( 3, 3 )->attachHiddenPiece( $chest );
		$modelBoard->getTile( 4, 3 )->attachVisiblePiece( $pawn );
		$modelBoard->getTile( 4, 3 )->attachHiddenPiece( $chest );
		
		$clientBoard = $sutBoardConverter->convert( $modelBoard );
		$this->assertEquals( 6, $clientBoard[ 'width' ] );
		$this->assertEquals( 4, $clientBoard[ 'height' ] );
		
		$clientTiles = $clientBoard[ 'tiles' ];
		$this->assertEquals( 'border', $clientTiles[ '1-1' ][ 'class' ] );
		$this->assertEquals( 'emperor', $clientTiles[ '2-2' ][ 'class' ] );
		$this->assertEquals( 'free', $clientTiles[ '2-3' ][ 'class' ] );
		$this->assertEquals( 'chest', $clientTiles[ '3-2' ][ 'class' ] );
		$this->assertEquals( 'free', $clientTiles[ '3-3' ][ 'class' ] );
		$this->assertEquals( 'pawn', $clientTiles[ '4-3' ][ 'class' ] );
		
		$this->assertEquals( 'C2', $clientTiles[ '3-2' ][ 'caption' ] );
	}
}
