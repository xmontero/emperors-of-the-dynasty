<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

class Emperor extends TokenHelper implements IToken
{
	public function __construct()
	{
		parent::__construct();
		$this->type = 'emperor';
	}
}
