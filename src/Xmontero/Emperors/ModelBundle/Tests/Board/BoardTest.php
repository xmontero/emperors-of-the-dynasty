<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Board;

use Xmontero\Emperors\ModelBundle\Model\Board\Board;
use Xmontero\Emperors\ModelBundle\Model\Board\Tile;
use Xmontero\Emperors\ModelBundle\Model\Pieces;

class BoardTest extends \PHPUnit_Framework_TestCase
{
	public function testBoardCreationWidthAndHeight()
	{
		$sutBoard = new Board( 10, 5 );
		$this->assertEquals( 10, $sutBoard->getWidth() );
		$this->assertEquals( 5, $sutBoard->getHeight() );
	}
	
	public function testGetTile()
	{
		$sutBoard = new Board( 10, 5 );
		$this->assertInstanceOf( get_class( new Tile ), $sutBoard->getTile( 3, 3 ) );
		// PHP 5.5 => $this->assertInstanceOf( Tile::class, $sutBoard->getTile( 3, 3 ) );
	}
	
	public function testAddColumnAt()
	{
		$dummyKey = 'myDummyKey';
		$sutBoard = new Board( 3, 3 );
		
		$sutBoard->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sutBoard->getTile( 1, 2 )->setProperty( $dummyKey, 'b' );
		$sutBoard->getTile( 2, 1 )->setProperty( $dummyKey, 'c' );
		$sutBoard->getTile( 2, 2 )->setProperty( $dummyKey, 'd' );
		
		$this->assertEquals( 3, $sutBoard->getWidth() );
		
		$sutBoard->addColumnAt( 2 );
		$this->assertEquals( 4, $sutBoard->getWidth() );
		
		$this->assertFalse( $sutBoard->getTile( 2, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 2, 2 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sutBoard->getTile( 1, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sutBoard->getTile( 1, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sutBoard->getTile( 3, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sutBoard->getTile( 3, 2 )->getProperty( $dummyKey ) );
	}
	
	public function testAddColumnAtBig()
	{
		$dummyKey = 'myDummyKey';
		$sutBoard = new Board( 10, 5 );
		
		$sutBoard->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sutBoard->getTile( 1, 3 )->setProperty( $dummyKey, 'b' );
		$sutBoard->getTile( 1, 5 )->setProperty( $dummyKey, 'c' );
		$sutBoard->getTile( 3, 1 )->setProperty( $dummyKey, 'd' );
		$sutBoard->getTile( 3, 3 )->setProperty( $dummyKey, 'e' );
		$sutBoard->getTile( 3, 5 )->setProperty( $dummyKey, 'f' );
		$sutBoard->getTile( 10, 1 )->setProperty( $dummyKey, 'g' );
		$sutBoard->getTile( 10, 3 )->setProperty( $dummyKey, 'h' );
		$sutBoard->getTile( 10, 5 )->setProperty( $dummyKey, 'i' );
		
		//     1   2   3   4   5   6   7   8   9  10
		//   +---+---+---+---+---+---+---+---+---+---+
		// 1 | a |   | d |   |   |   |   |   |   | g |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 2 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 3 | b |   | e |   |   |   |   |   |   | h |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 4 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 5 | c |   | f |   |   |   |   |   |   | i |
		//   +---+---+---+---+---+---+---+---+---+---+
		
		$this->assertEquals( 10, $sutBoard->getWidth() );
		
		$sutBoard->addColumnAt( 11 );
		$this->assertEquals( 11, $sutBoard->getWidth() );
		
		$sutBoard->addColumnAt( 4 );
		$this->assertEquals( 12, $sutBoard->getWidth() );
		
		$sutBoard->addColumnAt( 1 );
		$this->assertEquals( 13, $sutBoard->getWidth() );
		
		//     1   2   3   4   5   6   7   8   9  10  11  12  13
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		// 1 | x | a |   | d | x |   |   |   |   |   |   | g | x |
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		// 2 | x |   |   |   | x |   |   |   |   |   |   |   | x |
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		// 3 | x | b |   | e | x |   |   |   |   |   |   | h | x |
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		// 4 | x |   |   |   | x |   |   |   |   |   |   |   | x |
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		// 5 | x | c |   | f | x |   |   |   |   |   |   | i | x |
		//   +---+---+---+---+---+---+---+---+---+---+---+---+---+
		
		$this->assertFalse( $sutBoard->getTile( 1, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 1, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 1, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 5, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 5, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 5, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 13, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 13, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 13, 5 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sutBoard->getTile( 2, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sutBoard->getTile( 2, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sutBoard->getTile( 2, 5 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sutBoard->getTile( 4, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'e', $sutBoard->getTile( 4, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'f', $sutBoard->getTile( 4, 5 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'g', $sutBoard->getTile( 12, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'h', $sutBoard->getTile( 12, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'i', $sutBoard->getTile( 12, 5 )->getProperty( $dummyKey ) );
	}
	
	public function testAddColumnIndexTooLow()
	{
		$sutBoard = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sutBoard->addColumnAt( 0 );
	}
	
	public function testAddColumnIndexTooHigh()
	{
		$sutBoard = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sutBoard->addColumnAt( 12 );
	}
	
	public function testAddRowAt()
	{
		$dummyKey = 'myDummyKey';
		$sutBoard = new Board( 3, 3 );
		
		$sutBoard->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sutBoard->getTile( 1, 2 )->setProperty( $dummyKey, 'b' );
		$sutBoard->getTile( 2, 1 )->setProperty( $dummyKey, 'c' );
		$sutBoard->getTile( 2, 2 )->setProperty( $dummyKey, 'd' );
		
		$this->assertEquals( 3, $sutBoard->getHeight() );
		
		$sutBoard->addRowAt( 2 );
		$this->assertEquals( 4, $sutBoard->getHeight() );
		
		$this->assertFalse( $sutBoard->getTile( 1, 2 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 2, 2 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sutBoard->getTile( 1, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sutBoard->getTile( 1, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sutBoard->getTile( 2, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sutBoard->getTile( 2, 3 )->getProperty( $dummyKey ) );
	}
	
	public function testAddRowAtBig()
	{
		$dummyKey = 'myDummyKey';
		$sutBoard = new Board( 10, 5 );
		
		$sutBoard->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sutBoard->getTile( 1, 3 )->setProperty( $dummyKey, 'b' );
		$sutBoard->getTile( 1, 5 )->setProperty( $dummyKey, 'c' );
		$sutBoard->getTile( 3, 1 )->setProperty( $dummyKey, 'd' );
		$sutBoard->getTile( 3, 3 )->setProperty( $dummyKey, 'e' );
		$sutBoard->getTile( 3, 5 )->setProperty( $dummyKey, 'f' );
		$sutBoard->getTile( 10, 1 )->setProperty( $dummyKey, 'g' );
		$sutBoard->getTile( 10, 3 )->setProperty( $dummyKey, 'h' );
		$sutBoard->getTile( 10, 5 )->setProperty( $dummyKey, 'i' );
		
		//     1   2   3   4   5   6   7   8   9  10
		//   +---+---+---+---+---+---+---+---+---+---+
		// 1 | a |   | d |   |   |   |   |   |   | g |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 2 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 3 | b |   | e |   |   |   |   |   |   | h |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 4 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 5 | c |   | f |   |   |   |   |   |   | i |
		//   +---+---+---+---+---+---+---+---+---+---+
		
		$this->assertEquals( 5, $sutBoard->getHeight() );
		
		$sutBoard->addRowAt( 6 );
		$this->assertEquals( 6, $sutBoard->getHeight() );
		
		$sutBoard->addRowAt( 4 );
		$this->assertEquals( 7, $sutBoard->getHeight() );
		
		$sutBoard->addRowAt( 1 );
		$this->assertEquals( 8, $sutBoard->getHeight() );
		
		//     1   2   3   4   5   6   7   8   9  10
		//   +---+---+---+---+---+---+---+---+---+---+
		// 1 | x | x | x | x | x | x | x | x | x | x |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 2 | a |   | d |   |   |   |   |   |   | g |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 3 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 4 | b |   | e |   |   |   |   |   |   | h |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 5 | x | x | x | x | x | x | x | x | x | x |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 6 |   |   |   |   |   |   |   |   |   |   |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 7 | c |   | f |   |   |   |   |   |   | i |
		//   +---+---+---+---+---+---+---+---+---+---+
		// 8 | x | x | x | x | x | x | x | x | x | x |
		//   +---+---+---+---+---+---+---+---+---+---+
		
		$this->assertFalse( $sutBoard->getTile( 1, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 1, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 1, 8 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 3, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 3, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 3, 8 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 10, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 10, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sutBoard->getTile( 10, 8 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sutBoard->getTile( 1, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sutBoard->getTile( 1, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sutBoard->getTile( 1, 7 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sutBoard->getTile( 3, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'e', $sutBoard->getTile( 3, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'f', $sutBoard->getTile( 3, 7 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'g', $sutBoard->getTile( 10, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'h', $sutBoard->getTile( 10, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'i', $sutBoard->getTile( 10, 7 )->getProperty( $dummyKey ) );
	}
	
	public function testAddRowIndexTooLow()
	{
		$sutBoard = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sutBoard->addRowAt( 0 );
	}
	
	public function testAddRowIndexTooHigh()
	{
		$sutBoard = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sutBoard->addRowAt( 7 );
	}
	
	/**
	 * @dataProvider providerGetTileName
	 */
	public function testGetTileName( $width, $height, $x, $y, $tileName )
	{
		$sutBoard = new Board( $width, $height);
		$this->assertEquals( $tileName, $sutBoard->getTileName( $x, $y ) );
	}
	
	public function providerGetTileName()
	{
		return array
		(
			array( 3, 3, 1, 1, 'A1' ),
			array( 3, 3, 1, 3, 'A3' ),
			array( 3, 3, 3, 1, 'C1' ),
			array( 3, 3, 3, 3, 'C3' ),
			array( 100, 3, 26, 2, 'Z2' ),
			array( 100, 3, 27, 2, 'AA2' ),
			array( 100, 100, 54, 78, 'BB78' ),
		);
	}
	
	/**
	 * @dataProvider providerGetTileNameDomainException
	 */
	public function testGetTileNameDomainException( $width, $height, $x, $y )
	{
		$sutBoard = new Board( $width, $height );
		$this->setExpectedException( 'DomainException' );
		$sutBoard->getTileName( $x, $y );
	}
	
	public function providerGetTileNameDomainException()
	{
		return array
		(
			array( 10, 10, 11, 10 ),
			array( 10, 10,  3, -1 ),
		);
	}
	
	/**
	 * @dataProvider providerGetRowId
	 */
	public function testGetRowId( $width, $height, $y, $expectedRowId )
	{
		$sutBoard = new Board( $width, $height);
		$actualRowId = $sutBoard->getRowId( $y );
		$this->assertInternalType( 'string', $actualRowId );
		$this->assertEquals( $expectedRowId, $actualRowId );
	}
	public function providerGetRowId()
	{
		return array
		(
			array( 3, 3, 2, '2' ),
			array( 7, 100, 73, '73' ),
		);
	}
	
	/**
	 * @dataProvider providerGetRowIdDomainException
	 */
	public function testGetRowIdDomainException( $width, $height, $y )
	{
		$sutBoard = new Board( $width, $height);
		$this->setExpectedException( 'DomainException' );
		$sutBoard->getRowId( $y );
	}
	
	public function providerGetRowIdDomainException()
	{
		return array
		(
			array( 3, 3, 5 ),
			array( 10, 10, 0 ),
		);
	}
	
	/**
	 * @dataProvider providerGetColumnId
	 */
	public function testGetColumnId( $width, $height, $tests )
	{
		$sutBoard = new Board( $width, $height);
		
		foreach( $tests as $test )
		{
			$x = $test[ 0 ];
			$expectedColumnId = $test[ 1 ];
			
			$actualColumnId = $sutBoard->getColumnId( $x );
			$this->assertInternalType( 'string', $actualColumnId );
			$this->assertEquals( $expectedColumnId, $actualColumnId );
		}
	}
	
	public function providerGetColumnId()
	{
		return array
		(
			array
			(
				3, 3, array
				(
					array( 1, 'A' ),
					array( 2, 'B' ),
					array( 3, 'C' ),
				)
			),
			array
			(
				100, 100, array
				(
					array(   1,  'A' ),
					array(   2,  'B' ),
					array(  25,  'Y' ),
					array(  26,  'Z' ),
					array(  27, 'AA' ),
					array(  28, 'AB' ),
					array(  51, 'AY' ),
					array(  52, 'AZ' ),
					array(  53, 'BA' ),
					array(  54, 'BB' ),
					array(  77, 'BY' ),
					array(  78, 'BZ' ),
					array(  79, 'CA' ),
					array(  80, 'CB' ),
					array(  99, 'CU' ),
					array( 100, 'CV' ),
				)
			)
		);
	}
	
	/**
	 * @dataProvider providerGetColumnIdDomainException
	 */
	public function testGetColumnIdDomainException( $width, $height, $x )
	{
		$sutBoard = new Board( $width, $height);
		$this->setExpectedException( 'DomainException' );
		$sutBoard->getColumnId( $x );
	}
	
	public function providerGetColumnIdDomainException()
	{
		return array
		(
			array( 3, 3, 5 ),
			array( 100, 100, 0 ),
		);
	}
	
	public function testLoadFromJson()
	{
		$document = file_get_contents( __DIR__ . '/Data/BoardTestLoad.json' );
		
		$sutBoard = new Board;
		$sutBoard->loadFromJson( $document );
		
		$this->assertEquals( 9, $sutBoard->getWidth() );
		$this->assertEquals( 12, $sutBoard->getHeight() );
		
		$this->assertTrue( $sutBoard->getTile( 3, 4 )->isOffBoard() );
		$this->assertFalse( $sutBoard->getTile( 1, 1 )->isOffBoard() );
		
		$this->assertEquals( 0, $sutBoard->getPieces()->count() );
	}
	
	public function testLoadFromJsonNoTiles()
	{
		$document = file_get_contents( __DIR__ . '/Data/BoardNoTiles.json' );
		
		$sutBoard = new Board;
		$sutBoard->loadFromJson( $document );
		
		$tiles = $sutBoard->getTiles();
		
		$this->assertEquals( 9 * 12, $tiles->count() );
		
		$allAreInResetState = true;
		foreach( $tiles as $tile )
		{
			$thisIsInResetState = $tile->isInResetState();
			$allAreInResetState = $allAreInResetState && $thisIsInResetState;
		}
		
		$this->assertTrue( $allAreInResetState );
	}
	
	public function testSave()
	{
		$sutBoard = new Board( 9, 12 );
		
		$sutBoard->getTile( 1, 1 )->setProperty( 'foo', 'bar' );
		$sutBoard->getTile( 9, 12 )->setProperty( 'foo', 'bar' );
		
		$document = $sutBoard->saveToJson();
		
		$dummy = json_decode( $document );
		
		$this->assertEquals( 9, $dummy->width );
		$this->assertEquals( 12, $dummy->height );
		
		$this->assertFalse( array_key_exists( '0-1', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '1-0', $dummy->tiles ) );
		$this->assertTrue( array_key_exists( '1-1', $dummy->tiles ) );
		
		$this->assertFalse( array_key_exists( '5-5', $dummy->tiles ) );
		
		$this->assertTrue( array_key_exists( '9-12', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '10-12', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '9-13', $dummy->tiles ) );
	}
	
	public function testGetHiddenAndVisiblePieces()
	{
		$sutBoard = new Board( 4, 6 );
		
		$chest1 = new Pieces\Items\Chest;
		$chest2 = new Pieces\Items\Chest;
		$chest3 = new Pieces\Items\Chest;
		$life1 = new Pieces\Items\Life;
		$life2 = new Pieces\Items\Life;
		$life3 = new Pieces\Items\Life;
		$emperor1 = new Pieces\Tokens\Emperor;
		$emperor2 = new Pieces\Tokens\Emperor;
		$pawn1 = new Pieces\Tokens\Pawn;
		$pawn2 = new Pieces\Tokens\Pawn;
		$pawn3 = new Pieces\Tokens\Pawn;
		
		$sutBoard->placePiece( 2, 2, $chest1, false );
		$sutBoard->placePiece( 2, 2, $chest2, true );
		$sutBoard->placePiece( 3, 3, $life1, false );
		$sutBoard->placePiece( 1, 4, $life2, true );
		$sutBoard->placePiece( 2, 2, $emperor1, false );
		$sutBoard->placePiece( 3, 3, $pawn1, true );
		$sutBoard->placePiece( 1, 4, $pawn2, true );
		
		$pieces = $sutBoard->getPieces();
		
		$this->assertEquals( 7, $pieces->count() );
		
		$this->assertTrue( $pieces->contains( $chest1 ) );
		$this->assertTrue( $pieces->contains( $chest2 ) );
		$this->assertTrue( $pieces->contains( $life1 ) );
		$this->assertTrue( $pieces->contains( $life2 ) );
		
		$this->assertTrue( $pieces->contains( $emperor1 ) );
		$this->assertTrue( $pieces->contains( $pawn1 ) );
		$this->assertTrue( $pieces->contains( $pawn2 ) );
		
		$this->assertFalse( $pieces->contains( $chest3 ) );
		$this->assertFalse( $pieces->contains( $life3 ) );
		
		$this->assertFalse( $pieces->contains( $emperor2 ) );
		$this->assertFalse( $pieces->contains( $pawn3 ) );
		
		$visiblePieces = $sutBoard->getVisiblePieces();
		
		$this->assertEquals( 4, $visiblePieces->count() );
		
		$this->assertTrue( $visiblePieces->contains( $chest2 ) );
		$this->assertTrue( $visiblePieces->contains( $life2 ) );
		
		$this->assertTrue( $visiblePieces->contains( $pawn1 ) );
		$this->assertTrue( $visiblePieces->contains( $pawn2 ) );
		
		$this->assertFalse( $visiblePieces->contains( $chest1 ) );
		$this->assertFalse( $visiblePieces->contains( $chest3 ) );
		$this->assertFalse( $visiblePieces->contains( $life1 ) );
		$this->assertFalse( $visiblePieces->contains( $life3 ) );
		
		$this->assertFalse( $visiblePieces->contains( $emperor1 ) );
		$this->assertFalse( $visiblePieces->contains( $emperor2 ) );
		$this->assertFalse( $visiblePieces->contains( $pawn3 ) );
		
		$hiddenPieces = $sutBoard->getHiddenPieces();
		
		$this->assertEquals( 3, $hiddenPieces->count() );
		
		$this->assertTrue( $hiddenPieces->contains( $chest1 ) );
		$this->assertTrue( $hiddenPieces->contains( $life1 ) );
		
		$this->assertTrue( $hiddenPieces->contains( $emperor1 ) );
		
		$this->assertFalse( $hiddenPieces->contains( $chest2 ) );
		$this->assertFalse( $hiddenPieces->contains( $chest3 ) );
		$this->assertFalse( $hiddenPieces->contains( $life2 ) );
		$this->assertFalse( $hiddenPieces->contains( $life3 ) );
		
		$this->assertFalse( $hiddenPieces->contains( $emperor2 ) );
		$this->assertFalse( $hiddenPieces->contains( $pawn1 ) );
		$this->assertFalse( $hiddenPieces->contains( $pawn2 ) );
		$this->assertFalse( $hiddenPieces->contains( $pawn3 ) );
	}
}
