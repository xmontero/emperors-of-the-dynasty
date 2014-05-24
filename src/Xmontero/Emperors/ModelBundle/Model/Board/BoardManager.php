<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

use Xmontero\Emperors\ModelBundle\Model\Base\Manager;

class BoardManager extends Manager
{
	private $objectStorageManager;
	
	public function __construct( $objectStorageManager )
	{
		$this->objectStorageManager = $objectStorageManager;
	}
	
	public function createBoardFromScratch( $width, $height )
	{
		$board = new Board( $width, $height );
		return $board;
	}
	
	public function loadBoardFromJson( $document )
	{
		$board = new Board;
		$board->load( $document );
		
		return $board;
	}
}
