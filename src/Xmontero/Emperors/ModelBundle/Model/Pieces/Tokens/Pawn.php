<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

class Pawn extends TokenHelper implements IToken
{
	public function __construct( $playerId )
	{
		parent::__construct( $playerId );
		$this->type = 'pawn';
		$this->namePrefix = 'P';
	}
}
