<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Game\Pieces\PieceHelper;

abstract class TokenHelper extends PieceHelper implements IToken
{
	public function getType()
	{
		return 'token';
	}
}
