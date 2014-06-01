<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\PieceHelper;

abstract class TokenHelper extends PieceHelper implements IToken
{
	public function __construct( $playerId )
	{
		$argumentType = gettype( $playerId );
		if( $argumentType != 'integer' )
		{
			throw new \InvalidArgumentException( 'Expected string, got ' . $argumentType . '.' );
		}
	}
}
