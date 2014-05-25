<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces;

use Xmontero\Emperors\ModelBundle\Model\Board;

class Pieces extends \SplObjectStorage implements Board\IPieces
{
	/*
	public function filterByType( $type )
	{
		switch( $type )
		{
			case 'token':
			case 'item':
				
				$result = new Pieces;
				
				foreach( $this as $piece )
				{
					if( $piece->getType() == $type )
					{
						$result->attach( $piece );
					}
				}
				
				break;
			
			default:
				
				throw new \DomainException( 'Unknown piece type: ' . $type );
				break;
		}
		
		return $result;
	}
	*/
}
