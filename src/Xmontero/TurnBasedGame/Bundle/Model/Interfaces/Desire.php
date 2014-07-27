<?php

namespace Xmontero\TurnBasedGame\Bundle\Model\Interfaces;

interface Desire
{
	// Read as {SUBJECT} "DESIRES TO" {PREDICATE}
	// PREDICATE is a form of {VERB} + {ARGUMENTS}
	// ARGUMENTS is zero or more ARGUMENT
	// For example: Player3 desires to move Pawn4 to tile E2.
	// For example: Player2 desires to explore.
	
	// subject = object;
	// predicate = verb + arguments
	// arguments = array( 'argumentName1' => object argument1, 'argumentName2' => object argument2... ) )
	
	public function setSubject( $subject );
	public function setPredicate( $verb, $arguments );
	public function getSubject();
	public function getPredicate();
	public function getVerb();
	public function getArgument( $argumentName );
}