<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Board\IPiece;

interface IToken extends IPiece
{
	public function __construct( $playerId );
	public function getPlayerId();
}
