<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

class BoardManager
{
	private $logger;
	private $objectStorageManager;
	
	public function __construct( $logger, $objectStorageManager )
	{
		$this->logger = $logger;
		$this->objectStorageManager = $objectStorageManager;
	}
	
	public function createBoardFromScratch( $x, $y )
	{
		$board = new Board( $this->objectStorageManager, 1, $x, $y );
		return $board;
	}
}
