<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Base\Manager;

class GameManager extends Manager
{
	private $logger;
	private $objectStorageManager;
	private $games;
	
	public function __construct( $logger, $objectStorageManager )
	{
		$this->logger = $logger;
		$this->objectStorageManager = $objectStorageManager;
		$this->games = new Games;
	}
	
	public function getAllOpenGameIds()
	{
		$result = new \ArrayObject();
		$result[] = 1;
		return $result;
	}
	
	public function getOpenGames( $limit )
	{
		$ids = $this->getAllOpenGameIds();
		
		$maxGames = min( $limit, $ids->count() );
		
		for( $i = 0; $i < $maxGames; $i++ )
		{
			$id = $ids[ $i ];
			$game = $this->getGameById( $id );
			$this->games[ $id ] = $game;
		}
		
		return $this->games;
	}
	
	public function getGameById( $id )
	{
		$result = new Game( $id, $this->objectStorageManager );
		return $result;
	}
}
