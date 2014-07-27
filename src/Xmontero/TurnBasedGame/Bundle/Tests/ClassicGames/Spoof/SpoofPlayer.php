<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\ClassicGames\Spoof;

use Xmontero\TurnBasedGame\Bundle\Model;

class SpoofPlayer
{
	private $pickDesires;
	private $sayDesires;
	private $tag;
	
	public function __construct( $desires )
	{
		$this->pickDesires = $desires[ 'pick' ];
		$this->sayDesires = $desires[ 'say' ];
	}
	
	public function setTag( $newTag )
	{
		$this->tag = $newTag;
	}
	
	public function getTag()
	{
		return $this->tag;
	}
	
	public function getPick()
	{
		$pick = array_shift( $this->pickDesires );
		return $pick;
	}
	
	public function getSay()
	{
		$say = array_shift( $this->sayDesires );
		return $say;
	}
}
