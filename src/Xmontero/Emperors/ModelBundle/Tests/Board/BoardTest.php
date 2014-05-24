<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Xmontero\Emperors\ModelBundle\Model\Board\Board;
use Xmontero\Emperors\ModelBundle\Model\Board\Tile;
use Xmontero\Emperors\ModelBundle\Model\Game\Pieces;

class BoardTest extends \PHPUnit_Framework_TestCase
{
	public function testBoardCreationWidthAndHeight()
	{
		$sut = new Board( 10, 5 );
		$this->assertEquals( 10, $sut->getWidth() );
		$this->assertEquals( 5, $sut->getHeight() );
	}
	
	public function testGetTile()
	{
		$sut = new Board( 10, 5 );
		$this->assertInstanceOf( get_class( new Tile ), $sut->getTile( 3, 3 ) );
		// PHP 5.5 => $this->assertInstanceOf( Tile::class, $sut->getTile( 3, 3 ) );
	}
	
	public function testAddColumnAt()
	{
		$dummyKey = 'myDummyKey';
		$sut = new Board( 3, 3 );
		
		$sut->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sut->getTile( 1, 2 )->setProperty( $dummyKey, 'b' );
		$sut->getTile( 2, 1 )->setProperty( $dummyKey, 'c' );
		$sut->getTile( 2, 2 )->setProperty( $dummyKey, 'd' );
		
		$this->assertEquals( 3, $sut->getWidth() );
		
		$sut->addColumnAt( 2 );
		$this->assertEquals( 4, $sut->getWidth() );
		
		$this->assertFalse( $sut->getTile( 2, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 2, 2 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sut->getTile( 1, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sut->getTile( 1, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sut->getTile( 3, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sut->getTile( 3, 2 )->getProperty( $dummyKey ) );
	}
	
	public function testAddColumnAtBig()
	{
		$dummyKey = 'myDummyKey';
		$sut = new Board( 10, 5 );
		
		$sut->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sut->getTile( 1, 3 )->setProperty( $dummyKey, 'b' );
		$sut->getTile( 1, 5 )->setProperty( $dummyKey, 'c' );
		$sut->getTile( 3, 1 )->setProperty( $dummyKey, 'd' );
		$sut->getTile( 3, 3 )->setProperty( $dummyKey, 'e' );
		$sut->getTile( 3, 5 )->setProperty( $dummyKey, 'f' );
		$sut->getTile( 10, 1 )->setProperty( $dummyKey, 'g' );
		$sut->getTile( 10, 3 )->setProperty( $dummyKey, 'h' );
		$sut->getTile( 10, 5 )->setProperty( $dummyKey, 'i' );
		
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
		
		$this->assertEquals( 10, $sut->getWidth() );
		
		$sut->addColumnAt( 11 );
		$this->assertEquals( 11, $sut->getWidth() );
		
		$sut->addColumnAt( 4 );
		$this->assertEquals( 12, $sut->getWidth() );
		
		$sut->addColumnAt( 1 );
		$this->assertEquals( 13, $sut->getWidth() );
		
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
		
		$this->assertFalse( $sut->getTile( 1, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 1, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 1, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 5, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 5, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 5, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 13, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 13, 3 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 13, 5 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sut->getTile( 2, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sut->getTile( 2, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sut->getTile( 2, 5 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sut->getTile( 4, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'e', $sut->getTile( 4, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'f', $sut->getTile( 4, 5 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'g', $sut->getTile( 12, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'h', $sut->getTile( 12, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'i', $sut->getTile( 12, 5 )->getProperty( $dummyKey ) );
	}
	
	public function testAddColumnIndexTooLow()
	{
		$sut = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sut->addColumnAt( 0 );
	}
	
	public function testAddColumnIndexTooHigh()
	{
		$sut = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sut->addColumnAt( 12 );
	}
	
	public function testAddRowAt()
	{
		$dummyKey = 'myDummyKey';
		$sut = new Board( 3, 3 );
		
		$sut->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sut->getTile( 1, 2 )->setProperty( $dummyKey, 'b' );
		$sut->getTile( 2, 1 )->setProperty( $dummyKey, 'c' );
		$sut->getTile( 2, 2 )->setProperty( $dummyKey, 'd' );
		
		$this->assertEquals( 3, $sut->getHeight() );
		
		$sut->addRowAt( 2 );
		$this->assertEquals( 4, $sut->getHeight() );
		
		$this->assertFalse( $sut->getTile( 1, 2 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 2, 2 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sut->getTile( 1, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sut->getTile( 1, 3 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sut->getTile( 2, 1 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sut->getTile( 2, 3 )->getProperty( $dummyKey ) );
	}
	
	public function testAddRowAtBig()
	{
		$dummyKey = 'myDummyKey';
		$sut = new Board( 10, 5 );
		
		$sut->getTile( 1, 1 )->setProperty( $dummyKey, 'a' );
		$sut->getTile( 1, 3 )->setProperty( $dummyKey, 'b' );
		$sut->getTile( 1, 5 )->setProperty( $dummyKey, 'c' );
		$sut->getTile( 3, 1 )->setProperty( $dummyKey, 'd' );
		$sut->getTile( 3, 3 )->setProperty( $dummyKey, 'e' );
		$sut->getTile( 3, 5 )->setProperty( $dummyKey, 'f' );
		$sut->getTile( 10, 1 )->setProperty( $dummyKey, 'g' );
		$sut->getTile( 10, 3 )->setProperty( $dummyKey, 'h' );
		$sut->getTile( 10, 5 )->setProperty( $dummyKey, 'i' );
		
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
		
		$this->assertEquals( 5, $sut->getHeight() );
		
		$sut->addRowAt( 6 );
		$this->assertEquals( 6, $sut->getHeight() );
		
		$sut->addRowAt( 4 );
		$this->assertEquals( 7, $sut->getHeight() );
		
		$sut->addRowAt( 1 );
		$this->assertEquals( 8, $sut->getHeight() );
		
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
		
		$this->assertFalse( $sut->getTile( 1, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 1, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 1, 8 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 3, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 3, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 3, 8 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 10, 1 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 10, 5 )->propertyExists( $dummyKey ) );
		$this->assertFalse( $sut->getTile( 10, 8 )->propertyExists( $dummyKey ) );
		
		$this->assertEquals( 'a', $sut->getTile( 1, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'b', $sut->getTile( 1, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'c', $sut->getTile( 1, 7 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'd', $sut->getTile( 3, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'e', $sut->getTile( 3, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'f', $sut->getTile( 3, 7 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'g', $sut->getTile( 10, 2 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'h', $sut->getTile( 10, 4 )->getProperty( $dummyKey ) );
		$this->assertEquals( 'i', $sut->getTile( 10, 7 )->getProperty( $dummyKey ) );
	}
	
	public function testAddRowIndexTooLow()
	{
		$sut = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sut->addRowAt( 0 );
	}
	
	public function testAddRowIndexTooHigh()
	{
		$sut = new Board( 10, 5 );
		$this->setExpectedException( 'DomainException' );
		$sut->addRowAt( 7 );
	}
	
	/**
	 * @dataProvider providerGetTileName
	 */
	public function testGetTileName( $width, $height, $x, $y, $tileName )
	{
		$sut = new Board( $width, $height);
		$this->assertEquals( $tileName, $sut->getTileName( $x, $y ) );
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
		$sut = new Board( $width, $height );
		$this->setExpectedException( 'DomainException' );
		$sut->getTileName( $x, $y );
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
		$sut = new Board( $width, $height);
		$actualRowId = $sut->getRowId( $y );
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
		$sut = new Board( $width, $height);
		$this->setExpectedException( 'DomainException' );
		$sut->getRowId( $y );
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
		$sut = new Board( $width, $height);
		
		foreach( $tests as $test )
		{
			$x = $test[ 0 ];
			$expectedColumnId = $test[ 1 ];
			
			$actualColumnId = $sut->getColumnId( $x );
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
		$sut = new Board( $width, $height);
		$this->setExpectedException( 'DomainException' );
		$sut->getColumnId( $x );
	}
	
	public function providerGetColumnIdDomainException()
	{
		return array
		(
			array( 3, 3, 5 ),
			array( 100, 100, 0 ),
		);
	}
	
	public function testLoad()
	{
		$document = file_get_contents( __DIR__ . '/Data/BoardTestLoad.json' );
		
		$board = new Board;
		$board->load( $document );
		
		$this->assertEquals( 9, $board->getWidth() );
		$this->assertEquals( 12, $board->getHeight() );
		$this->assertEquals( 0, $board->getPieces()->count() );
	}
	
	public function testLoadNoTilesGeneratesException()
	{
		$document = file_get_contents( __DIR__ . '/Data/BoardTestLoadNoTilesGeneratesException.json' );
		
		$board = new Board;
		$this->setExpectedException( 'RuntimeException' );
		$board->load( $document );
	}
	
	public function testSave()
	{
		$board = new Board( 9, 12 );
		$document = $board->saveToJson();
		
		$dummy = json_decode( $document );
		
		$this->assertEquals( 9, $dummy->width );
		$this->assertEquals( 12, $dummy->height );

		$this->assertFalse( array_key_exists( '0-1', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '1-0', $dummy->tiles ) );
		$this->assertTrue( array_key_exists( '1-1', $dummy->tiles ) );

		$this->assertTrue( array_key_exists( '9-12', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '10-12', $dummy->tiles ) );
		$this->assertFalse( array_key_exists( '9-13', $dummy->tiles ) );
	}
	
	public function testGetHiddenAndVisiblePieces()
	{
		$board = new Board( 4, 6 );
		
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
		
		$board->placePiece( 2, 2, $chest1, false );
		$board->placePiece( 2, 2, $chest2, true );
		$board->placePiece( 3, 3, $life1, false );
		$board->placePiece( 1, 4, $life2, true );
		$board->placePiece( 2, 2, $emperor1, false );
		$board->placePiece( 3, 3, $pawn1, true );
		$board->placePiece( 1, 4, $pawn2, true );
		
		$pieces = $board->getPieces();
		
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
		
		$visiblePieces = $board->getVisiblePieces();
		
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
		
		$hiddenPieces = $board->getHiddenPieces();
		
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
