<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

class Pawn extends TokenHelper implements IToken
{
	private $workingForEmperorId;
	
	public function __construct( $playerId )
	{
		parent::__construct( $playerId );
		$this->type = 'pawn';
		$this->namePrefix = 'P';
	}
	
	public function getWorkingForEmperorId()
	{
		return $this->workingForEmperorId;
	}
	
	public function setWorkingForEmperorId( $emperorId )
	{
		$this->workingForEmperorId = $emperorId;
	}
}
