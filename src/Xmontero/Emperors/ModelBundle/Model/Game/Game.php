<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

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
		$result = new Board( $this->objectStorageManager, $turn );
		return $result;
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
