<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

class BoardManager
{
	private $objectStorageManager;
	
	public function __construct( $objectStorageManager )
	{
		$this->objectStorageManager = $objectStorageManager;
	}
	
	public function createBoardFromScratch( $x, $y )
	{
		$board = new Board( $this->objectStorageManager, 1, $x, $y );
		return $board;
	}
	
	public function loadBoardFromJson( $document )
	{
		$board = new Board( null, 1, 0, 0 );
		$board->load( $document );
		
		return $board;
	}
}
