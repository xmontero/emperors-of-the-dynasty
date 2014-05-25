<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces;

use Xmontero\Emperors\ModelBundle\Model\Base\Manager;

class PieceManager extends Manager
{
	private $availableTypes;
	
	public function __construct()
	{
		$this->availableTypes = array
		(
			'emperor',
			'pawn',
			'chest',
			'life',
			'wealth',
		);
	}
	
	public function getAvailableTypes()
	{
		return $this->availableTypes;
	}
	
	public function createNewPieceFromScratch( $type )
	{
		switch( $type )
		{
			case 'emperor':
				
				$piece = new Tokens\Emperor;
				break;
				
			case 'pawn':
				
				$piece = new Tokens\Pawn;
				break;
				
			case 'chest':
				
				$piece = new Items\Chest;
				break;
				
			case 'life':
				
				$piece = new Items\Life;
				break;
				
			case 'wealth':
				
				$piece = new Items\Wealth;
				break;
				
			default:
			
				throw new \DomainException( 'Piece of type "' . $type . '" not recognized.' );
		}
		
		return $piece;
	}
}
