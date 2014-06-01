<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

use Xmontero\Emperors\ModelBundle\Model\Board\BoardManager;
use Xmontero\Emperors\ModelBundle\Model\Pieces\PieceManager;
use Xmontero\Emperors\ModelBundle\Model\ObjectStorage\ObjectStorageManager;
use Xmontero\Emperors\ModelBundle\Model\Base\Manager;

class GameManager extends Manager
{
	private $boardManager;
	private $pieceManager;
	private $objectStorageManager;
	private $games;
	
	public function __construct( BoardManager $boardManager, PieceManager $pieceManager, ObjectStorageManager $objectStorageManager )
	{
		$this->boardManager = $boardManager;
		$this->pieceManager = $pieceManager;
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
		$result = new Game( $id, $this->boardManager, $this->pieceManager, $this->objectStorageManager );
		return $result;
	}
}
