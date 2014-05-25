<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Board\Board;

class Game
{
	private $id;
	private $boardManager;
	private $objectStorageManager;
	
	public function __construct( $id, $boardManager, $objectStorageManager )
	{
		$this->id = $id;
		$this->boardManager = $boardManager;
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
		$board = $this->boardManager->loadBoardFromTemplate( 'uukhumaki' );
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
