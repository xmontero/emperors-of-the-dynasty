<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Board\Board;

class Game
{
	private $id;
	private $objectStorageManager;
	
	public function __construct( $id, $objectStorageManager )
	{
		$this->id = $id;
		$this->objectStorageManager = $objectStorageManager;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getStartDate()
	{
		$result = new \DateTime();
		return $result;
	}
	
	public function getBoard( $turn )
	{
		$board = new Board( 14, 14 );
		$board->oldLoad( $this->objectStorageManager, $turn, 14, 14 );
		return $board;
	}
	
	public function getNumberOfPlayers()
	{
		return 8;
	}
	
	public function getNumberOfPlayersAlive()
	{
		return 8;
	}
}
