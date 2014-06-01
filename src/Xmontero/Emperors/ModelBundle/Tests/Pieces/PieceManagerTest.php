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
		
		$correctProperties = true;
		$foundTypes = array();
		foreach( $availableTypes as $value )
		{
			$isNotSetClass = ( ! isset( $value->class ) );
			$isNotSetName = ( ! isset( $value->name ) );
			if( $isNotSetClass || $isNotSetName )
			{
				$correctProperties = false;
				break;
			}
			
			$foundTypes[] = $value->name;
		}
		
		$this->assertTrue( $correctProperties );
		$this->assertTrue( in_array( 'emperor', $foundTypes ) );
		$this->assertTrue( in_array( 'pawn', $foundTypes ) );
		$this->assertTrue( in_array( 'chest', $foundTypes ) );
		$this->assertTrue( in_array( 'life', $foundTypes ) );
		$this->assertTrue( in_array( 'wealth', $foundTypes ) );
	}
	
	public function testCreateNewPieceFromScratch()
	{
		$availableTypes = $this->sutPieceManager->getAvailableTypes();
		foreach( $availableTypes as $pieceType )
		{
			switch( $pieceType->class )
			{
				case 'token':
					
					$piece = $this->sutPieceManager->createNewTokenFromScratch( $pieceType->name, 1 );
					break;
					
				case 'item':
					
					$piece = $this->sutPieceManager->createNewItemFromScratch( $pieceType->name );
					break;
					
				default:
					
					throw new \RuntimeException;
					break;
			}
			
			$this->assertInstanceOf( 'Xmontero\Emperors\ModelBundle\Model\Board\IPiece', $piece );
			$this->assertEquals( $pieceType->name, $piece->getType() );
		}
	}
}
