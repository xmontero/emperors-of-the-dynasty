<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game;

class GameManager
{
	public function getAllOpenGameIds()
	{
		$result = new \ArrayObject();
		return $result;
	}
	
	public function getGameById( $id )
	{
		$result = new Game( $id );
		return $result;
	}
}
