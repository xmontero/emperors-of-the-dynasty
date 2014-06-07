<?php

namespace Xmontero\TurnBasedGame\Bundle\Model;

class Game
{
	private $observers;
	private $players;
	private $rules;
	private $turn;
	
	public function __construct()
	{
		$this->observers = new \ArrayObject();
		$this->players = new \ArrayObject();
		$this->rules = new \ArrayObject();
		$this->turn = 0;
	}
	
	public function addPlayer( Interfaces\GameObserver $player )
	{
		$this->players->append( $player );
		$this->observers->append( $player );
	}
	
	public function getPlayers()
	{
		return $this->players;
	}
	
	public function start( $testMode = false )
	{
		$cancel = false;
		$this->raiseEventPreStart( $cancel );
		$canStart = ( ! $cancel );
		
		if( ! $testMode )
		{
			if( $canStart )
			{
				$this->internalStart();
			}
			
			$this->raiseEventPostStart();
		}
		
		return $canStart;
	}
	
	public function getTurn()
	{
		return $this->turn;
	}
	
	public function addRule( Interfaces\GameObserver $rule )
	{
		$this->rules->append( $rule );
		$this->observers->append( $rule );
	}
	
	//-- Event definition -------------------------------------------------//
	
	private function raiseEventPreStart( & $cancel )
	{
		$methodName = 'onGamePreStart';
		foreach( $this->observers as $observer )
		{
			if( method_exists( $observer, $methodName ) )
			$observer->$methodName( $this, $cancel );
			
			if( $cancel )
			{
				break;
			}
		}
	}
	
	private function raiseEventPostStart()
	{
		$methodName = 'onGamePostStart';
		foreach( $this->observers as $observer )
		{
			if( method_exists( $observer, $methodName ) )
			$observer->$methodName( $this );
		}
	}
	
	private function raiseEventPostOpenTurn()
	{
	}
	
	//-- Private ----------------------------------------------------------//
	
	private function addObserver( Interfaces\GameObserver $observer )
	{
		$this->observers->append( $observer );
	}
	
	private function internalStart()
	{
		if( $this->turn == 0 )
		{
			$this->turn = 1;
			$this->openTurn();
		}
		else
		{
			throw new \RuntimeException( 'Cannot start an already started game.' );
		}
	}
	
	private function openTurn()
	{
		$this->raiseEventPostOpenTurn();
	}
}
