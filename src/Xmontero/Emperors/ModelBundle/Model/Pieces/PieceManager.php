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
			( object )array( 'class' => 'token', 'name' => 'emperor' ),
			( object )array( 'class' => 'token', 'name' => 'pawn' ),
			( object )array( 'class' => 'item', 'name' => 'chest' ),
			( object )array( 'class' => 'item', 'name' => 'life' ),
			( object )array( 'class' => 'item', 'name' => 'wealth' ),
		);
	}
	
	public function getAvailableTypes()
	{
		return $this->availableTypes;
	}
	
	public function createNewTokenFromScratch( $type, $playerId )
	{
		switch( $type )
		{
			case 'emperor':
				
				$piece = new Tokens\Emperor( $playerId );
				break;
				
			case 'pawn':
				
				$piece = new Tokens\Pawn( $playerId );
				break;
				
			default:
			
				throw new \DomainException( 'Token of type "' . $type . '" not recognized.' );
		}
		
		return $piece;
	}
	
	public function createNewItemFromScratch( $type )
	{
		switch( $type )
		{
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
			
				throw new \DomainException( 'Item of type "' . $type . '" not recognized.' );
		}
		
		return $piece;
	}
}
