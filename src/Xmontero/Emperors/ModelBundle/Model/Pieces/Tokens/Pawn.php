<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

class Pawn extends TokenHelper implements IToken
{
	public function __construct()
	{
		parent::__construct();
		$this->type = 'pawn';
		$this->namePrefix = 'P';
	}
}
