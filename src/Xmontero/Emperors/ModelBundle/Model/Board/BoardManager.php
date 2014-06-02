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
		$board->loadFromJson( $document );
		
		return $board;
	}
	
	public function loadBoardFromTemplate( $boardTemplate )
	{
		if( $boardTemplate != 'uukhumaki' )
		{
			throw new \DomainException( 'Template "' . $boardTemplate . '"" not found.' );
		}
			
		$board = new Board( 14, 14 );
		
		for( $i = 1; $i <= $board->getWidth(); $i++ )
		{
			if( ( $i != 5 ) && ( $i != 10 ) )
			{
				$board->getTile( $i, 1 )->setOffBoard();
				$board->getTile( $i, $board->getHeight() )->setOffBoard();
				$board->getTile( 1, $i )->setOffBoard();
				$board->getTile( $board->getWidth(), $i )->setOffBoard();
			}
		}
		
		return $board;
	}
	
	public function cloneBoard( $referenceBoard )
	{
		//$json = $referenceBoard->saveToJson();
		//$clonedBoard = $this->loadBoardFromJson( $json );
		$clonedBoard = $referenceBoard;
		return $clonedBoard;
	}
}
