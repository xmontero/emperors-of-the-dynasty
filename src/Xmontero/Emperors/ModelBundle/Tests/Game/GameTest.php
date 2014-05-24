<?php
namespace Xmontero\Emperors\ModelBundle\Tests\Game;

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameTest extends \PHPUnit_Framework_TestCase
{
	private $sut;
	private $objectStorageManagerStub;
	
	public function setup()
	{
		$objectStorageManagerStub = $this->getMockBuilder( 'Xmontero\Emperors\ModelBundle\Model\ObjectStorage\ObjectStorageManager' )
			->disableOriginalConstructor()
			->getMock();
		
		$this->objectStorageManagerStub = $objectStorageManagerStub;
	}
	
	public function teardown()
	{
		unset( $this->sut );
		unset( $this->objectStorageManagerStub );
	}
	
	public function testGetStartDate()
	{
		$sut = new Model\Game\Game( 1, $this->objectStorageManagerStub );
		$startDate = $sut->getStartDate();
		
		$this->assertInstanceOf( '\DateTime', $startDate );
	}
}
