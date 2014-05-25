<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Pieces;

use Xmontero\Emperors\ModelBundle\Model\Pieces;

class PieceManagerTest extends \PHPUnit_Framework_TestCase
{
	private $sutPieceManager;
	
	public function setup()
	{
		$this->sutPieceManager = new Pieces\PieceManager();
	}
	
	public function teardown()
	{
		unset( $this->sutPieceManager );
	}
	
	public function testGetAvailableTypes()
	{
		$availableTypes = $this->sutPieceManager->getAvailableTypes();
		$this->assertTrue( in_array( 'emperor', $availableTypes ) );
		$this->assertTrue( in_array( 'pawn', $availableTypes ) );
		$this->assertTrue( in_array( 'chest', $availableTypes ) );
		$this->assertTrue( in_array( 'life', $availableTypes ) );
		$this->assertTrue( in_array( 'wealth', $availableTypes ) );
	}
	
	public function testCreateNewPieceFromScratch()
	{
		$availableTypes = $this->sutPieceManager->getAvailableTypes();
		foreach( $availableTypes as $pieceType )
		{
			$piece = $this->sutPieceManager->createNewPieceFromScratch( $pieceType );
			$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Board\IPiece', $piece );
			$this->assertEquals( $pieceType, $piece->getType() );
		}
	}
}
