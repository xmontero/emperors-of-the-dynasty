<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

interface IPiece
{
	// Always available.
//	/** @return bool */ public function isPlacedInBoard();
//	/** @return bool */ public function isPlacedInTile();
	/** @return string */ public function getType();
	
	// Only available when the piece is not placed in a tile.
//	/** @return void */ public function placeInTile( ITile $tile, $visible );
	
	// Only available when the piece is placed in the board.
//	/** @return Board */ public function getBoard();
//	/** @return void */ public function moveToTile( ITile $tile );
//	/** @return void */ public function moveToPosition( /* int */ $x, /* int */ $y );
//	/** @return void */ public function removeFromBoard();
	
	// Only available when the piece is placed in a tile.
//	/** @return Tile */ public function getTile();
}
