<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

use Xmontero\Emperors\ModelBundle\Model\Base;
use Xmontero\Emperors\ModelBundle\Model\Game\Pieces\Pieces;

class Tile implements ITile
{
	private $onBoard;
	private $properties;
	private $visiblePieces;
	private $hiddenPieces;
	
	public function __construct()
	{
		$this->reset();
	}
	
	// OffBoard
	
	public function isOffBoard()
	{
		return ( ! $this->onBoard );
	}
	
	public function isOnBoard()
	{
		return $this->onBoard;
	}
	
	public function setOffBoard()
	{
		$this->onBoard = false;
	}
	
	public function setOnBoard()
	{
		$this->onBoard = true;
	}
	
	// Properties
	
	public function getProperty( $key )
	{
		if( $this->properties->offsetExists( $key ) )
		{
			$result = $this->properties->offsetGet( $key );
		}
		else
		{
			throw new \DomainException();
		}
		
		return $result;
	}
	
	public function setProperty( $key, $value )
	{
		$this->assertIsOnBoard();
		$this->properties->offsetSet( $key, $value );
	}
	
	public function propertyExists( $key )
	{
		return $this->properties->offsetExists( $key );
	}
	
	// Pieces
	
	// Always available.
	
	public function getVisiblePieces()
	{
		return $this->visiblePieces;
	}
	
	public function getHiddenPieces()
	{
		return $this->hiddenPieces;
	}
	
	public function getPieces()
	{
		$result = new Pieces;
		
		foreach( $this->visiblePieces as $piece )
		{
			$result->attach( $piece );
		}
		
		foreach( $this->hiddenPieces as $piece )
		{
			$result->attach( $piece );
		}
		
		return $result;
	}
	
	public function pieceExists( IPiece $piece )
	{
		$existsInVisible = $this->pieceExistsInVisible( $piece );
		$existsInHidden = $this->pieceExistsInHidden( $piece );
		
		return ( $existsInVisible || $existsInHidden );
	}
	
	// Only available if piece does not exist.
	
	public function attachVisiblePiece( IPiece $piece )
	{
		$this->assertPieceDoesNotExist( $piece );
		$this->visiblePieces->attach( $piece );
	}
	
	public function attachHiddenPiece( IPiece $piece )
	{
		$this->assertPieceDoesNotExist( $piece );
		$this->hiddenPieces->attach( $piece );
	}
	
	// Only available if piece exists.
	
	public function detachPiece( IPiece $piece )
	{
		$this->assertPieceExists( $piece );
		$this->pieces->detach( $piece );
	}
	
	public function isVisible( IPiece $piece )
	{
		$this->assertPieceExists( $piece );
		return false;
	}
	
	// State
	
	public function isInResetState()
	{
		$isOnBoard = $this->isOnBoard();
		$thereAreNoProperties = ( $this->properties->count() == 0 );
		$thereAreNoHiddenPieces = ( $this->hiddenPieces->count() == 0 );
		$thereAreNoVisiblePieces = ( $this->visiblePieces->count() == 0 );
		
		$isInResetState =
		(
			   $isOnBoard
			&& $thereAreNoProperties
			&& $thereAreNoVisiblePieces
			&& $thereAreNoHiddenPieces
		);
		
		return $isInResetState;
	}
	
	public function reset()
	{
		$this->setOnBoard();
		$this->properties = new Base\Collection;
		$this->visiblePieces = new Pieces;
		$this->hiddenPieces = new Pieces;
	}
	
	public function loadFromObjectDocument( $objectDocument )
	{
		$this->reset();
		
		if( isset( $objectDocument->offBoard ) )
		{
			$this->setOffBoard();
		}
	}
	
	public function saveToObjectDocument()
	{
		$tile = new \StdClass();
		
		if( $this->properties->count() > 0 )
		{
			$tile->properties = array();
			foreach( $this->properties as $key => $value )
			{
				$tile->properties[] = array( $key, $value );
			}
		}
		
		if( $this->isOffBoard() )
		{
			$tile->offBoard = true;
		}
		
		return $tile;
	}
	
	// -- Private ---------------------------------------------------------//
	
	private function pieceExistsInVisible( IPiece $piece )
	{
		return $this->visiblePieces->contains( $piece );
	}
	
	private function pieceExistsInHidden( IPiece $piece )
	{
		return $this->hiddenPieces->contains( $piece );
	}
	
	private function assertPieceExists( IPiece $piece )
	{
		if( ! $this->pieceExists( $piece ) )
		{
			throw new \RuntimeException;
		}
	}
	
	private function assertPieceDoesNotExist( IPiece $piece )
	{
		if( $this->pieceExists( $piece ) )
		{
			throw new \RuntimeException;
		}
	}
	
	private function assertIsOnBoard()
	{
		if( $this->isOffBoard() )
		{
			throw new \RuntimeException;
		}
	}
}
