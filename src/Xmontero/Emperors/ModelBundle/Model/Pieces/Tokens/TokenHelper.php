<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Pieces\PieceHelper;

abstract class TokenHelper extends PieceHelper implements IToken
{
	private $playerId;
	private $experience;
	private $usableExperience;
	private $liveQuarters;
	
	public function __construct( $playerId )
	{
		$argumentType = gettype( $playerId );
		if( $argumentType != 'integer' )
		{
			throw new \InvalidArgumentException( 'Expected string, got ' . $argumentType . '.' );
		}
		
		$this->playerId = $playerId;
	}
	
	public function getPlayerId()
	{
		return $this->playerId;
	}
	
	public function getExperience()
	{
		return $this->experience;
	}
	
	public function setExperience( $newExperience )
	{
		$this->experience = $newExperience;
	}
	
	public function getUsableExperience()
	{
		return $this->usableExperience;
	}
	
	public function setUsableExperience( $newUsableExperience )
	{
		$this->usableExperience = $newUsableExperience;
	}
	
	public function getLiveQuarters()
	{
		return $this->liveQuarters;
	}
	
	public function setLiveQuarters( $newLiveQuarters )
	{
		$this->liveQuarters = $newLiveQuarters;
	}
}
