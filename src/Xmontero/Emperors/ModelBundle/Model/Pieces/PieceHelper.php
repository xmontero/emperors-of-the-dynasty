<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces;

use Xmontero\Emperors\ModelBundle\Model\Base\Exceptions\NotImplementeException;
use Xmontero\Emperors\ModelBundle\Model\Board;

abstract class PieceHelper implements Board\IPiece
{
	protected $type;
	private $tile;
	
	public function __construct()
	{
		$this->type = null;
		$this->tile = null;
		$this->visible = false;
	}
	
	// -- Public ----------------------------------------------------------//
	
	// Always available.
	public function isPlacedInBoard()
	{
		throw new NotImplementeException;
	}
	
	public function isPlacedInTile()
	{
		return ( ! is_null( $this->tile ) );
	}
	
	public function getType()
	{
		if( is_null( $this->type ) )
		{
			throw new \RuntimeException;
		}
		
		return $this->type;
	}
	
	// Only available when the piece is not placed in a tile.
	public function placeInTile( Board\ITile $tile, $visible )
	{
		$this->assertNotPlacedInTile();
		$this->tile = $tile;
		
		if( $visible )
		{
			$this->tile->attachVisiblePiece( $this );
		}
		else
		{
			$this->tile->attachHiddenPiece( $this );
		}
	}
	
	// Only available when the piece is placed in the board.
	public function getBoard()
	{
		$this->assertPlacedInBoard();
		throw new NotImplementeException;
	}
	
	public function moveToTile( Board\ITile $tile )
	{
		$this->assertPlacedInBoard();
		throw new NotImplementeException;
	}
	
	public function moveToPosition( $x, $y )
	{
		$this->assertPlacedInBoard();
		throw new NotImplementeException;
	}
	
	public function removeFromBoard()
	{
		$this->assertPlacedInBoard();
		throw new NotImplementeException;
	}
	
	// Only available when the piece is placed in a tile.
	public function getTile()
	{
		$this->assertPlacedInTile();
		return $this->tile;
	}
	
	// -- Private ---------------------------------------------------------//
	
	private function assertNotPlacedInBoard()
	{
		if( $this->isPlacedInBoard() )
		{
			throw new \RuntimeException;
		}
	}
	
	private function assertNotPlacedInTile()
	{
		if( $this->isPlacedInTile() )
		{
			throw new \RuntimeException;
		}
	}
	
	private function assertPlacedInBoard()
	{
		if( ! $this->isPlacedInBoard() )
		{
			throw new \RuntimeException;
		}
	}
	
	private function assertPlacedInTile()
	{
		if( ! $this->isPlacedInTile() )
		{
			throw new \RuntimeException;
		}
	}
}
