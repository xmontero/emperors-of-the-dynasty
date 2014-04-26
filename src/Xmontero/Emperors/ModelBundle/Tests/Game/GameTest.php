<?php

use Xmontero\Emperors\ModelBundle\Model;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameTest extends \PHPUnit_Framework_TestCase
{
	public function testGetStartDate()
	{
		$sut = new Model\Game\Game( 1 );
		$startDate = $sut->getStartDate();
		
		$this->assertInstanceOf( '\DateTime', $startDate );
	}
}
