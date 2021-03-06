<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

class Emperor extends TokenHelper implements IToken
{
	public function __construct( $playerId )
	{
		parent::__construct( $playerId );
		$this->type = 'emperor';
		$this->namePrefix = 'E';
	}
}
