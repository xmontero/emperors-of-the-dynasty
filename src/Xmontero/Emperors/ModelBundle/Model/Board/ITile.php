<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

interface ITile
{
	// Off/OnBoard
	public function isOffBoard();
	public function isOnBoard();
	public function setOffBoard();
	public function setOnBoard();
	
	// Properties
	public function getProperty( $key );
	public function setProperty( $key, $value );
	public function propertyExists( $key );
	
	// Pieces
	public function getVisiblePieces();
	public function getHiddenPieces();
	public function getPieces();
	public function attachVisiblePiece( IPiece $piece );
	public function attachHiddenPiece( IPiece $piece );
	public function detachPiece( IPiece $piece );
	public function isVisible( IPiece $piece );
	public function pieceExists( IPiece $piece );
	
	// State
	public function isInResetState();
	public function reset();
	public function loadFromObjectDocument( $objectDocument );
	public function saveToObjectDocument();
}
