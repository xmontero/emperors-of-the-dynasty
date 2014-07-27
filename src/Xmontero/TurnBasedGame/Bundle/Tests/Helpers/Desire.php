<?php

namespace Xmontero\TurnBasedGame\Bundle\Tests\Helpers;

use Xmontero\TurnBasedGame\Bundle\Model\Interfaces\Desire as IDesire;

class Desire implements IDesire
{
	private $subject;
	private $verb;
	private $arguments;
	
	public function setSubject( $subject )
	{
		$this->subject = $subject;
	}
	
	public function setPredicate( $verb, $arguments )
	{
		$this->verb = $verb;
		$this->arguments = $arguments;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function getPredicate()
	{
		$predicate = array
		(
			'verb' => $this->verb,
			'arguments' => $this->arguments,
		);
		
		return $predicate;
	}
	
	public function getVerb()
	{
		return $this->verb;
	}
	
	public function getArgument( $argumentName )
	{
		return $this->arguments[ $argumentName ];
	}
}
