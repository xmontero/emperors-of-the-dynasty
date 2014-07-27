<?php

namespace Xmontero\TurnBasedGame\Bundle\Model;

use Xmontero\TurnBasedGame\Bundle\Model\Interfaces\Desire;

class Game
{
	// State.
	private $started;
	private $aborted;
	private $ended;
	
	// Objects.
	private $elements;
	private $observers;
	private $players;
	private $rules;
	private $desires;
	private $turn;
	
	public function __construct()
	{
		$this->started = false;
		$this->aborted = false;
		$this->ended = false;
		
		$this->elements = new \ArrayObject();
		$this->observers = new \ArrayObject();
		$this->players = new \ArrayObject();
		$this->rules = new \ArrayObject();
		$this->turn = 0;
	}
	
	//-- State ------------------------------------------------------------//
	
	public function run()
	{
		$this->start();
		while( ! $this->isEnded() )
		{
			$this->tick();
			$this->closeTurn();
		}
	}
	
	public function start( $testMode = false )
	{
		$this->assertStartable();
		
		$cancel = false;
		$this->raiseEventPreStart( $cancel );
		$canStart = ( ! $cancel );
		
		if( ( ! $testMode ) && $canStart )
		{
			$this->started = true;
			$this->raiseEventPostStart();
			$this->openNextTurn();
		}
		
		return $canStart;
	}
	
	public function tick()
	{
		$this->assertOperable( 'tick' );
		$this->raiseEventTick();
	}
	
	public function closeTurn( $testMode = false )
	{
		$this->assertOperable( 'close turn' );
		
		$cancel = false;
		$this->raiseEventPreCloseTurn( $cancel );
		
		$canCloseTurn = ( ! $cancel );
		
		if( ( ! $testMode ) && $canCloseTurn )
		{
			$this->raiseEventPostCloseTurn();
			
			if( ! $this->ended )
			{
				$this->openNextTurn();
			}
		}
		
		return $canCloseTurn;
	}
	
	public function resetTurn( $testMode = false )
	{
		$this->assertOperable( 'reset turn' );
		
		$cancel = false;
		$this->raiseEventPreResetTurn( $cancel );
		
		$canResetTurn = ( ! $cancel );
		
		if( ( ! $testMode ) && $canResetTurn )
		{
			$this->openSameTurn();
			$this->raiseEventPostResetTurn();
		}
		
		return $canResetTurn;
	}
	
	public function abort( $testMode = false )
	{
		$this->assertOperable( 'abort game' );
		
		$cancel = false;
		$this->raiseEventPreAbort( $cancel );
		$canAbort = ( ! $cancel );
		
		if( ( ! $testMode ) && $canAbort )
		{
			$this->aborted = true;
			$this->ended = true;
			$this->raiseEventPostAbort();
		}
		
		return $canAbort;
	}
	
	public function end( $testMode = false )
	{
		$this->assertOperable( 'end game' );
		
		$cancel = false;
		$this->raiseEventPreEnd( $cancel );
		$canEnd = ( ! $cancel );
		
		if( ( ! $testMode ) && $canEnd )
		{
			$this->ended = true;
			$this->raiseEventPostEnd();
		}
		
		return $canEnd;
	}
	
	public function isStarted()
	{
		return $this->started;
	}
	
	public function isAborted()
	{
		return $this->aborted;
	}
	
	public function isEnded()
	{
		return $this->ended;
	}
	
	//-- Objects ----------------------------------------------------------//
	
	//-- Elements --------------------------------//
	
	public function addElement( $key, $element )
	{
		$this->elements->offsetSet( $key, $element );
	}
	
	public function updateElement( $key, $element )
	{
		$this->elements->offsetUnset( $key );
		$this->elements->offsetSet( $key, $element );
	}
	
	public function getElement( $key )
	{
		return $this->elements->offsetGet( $key );
	}
	
	public function getElements()
	{
		return $this->elements;
	}
	
	//-- Players ---------------------------------//
	
	public function addPlayer( $playerIndex, $player, $testMode = false )
	{
		$cancel = false;
		$this->raiseEventPreAddPlayer( $playerIndex, $player, $cancel );
		$canAddPlayer = ( ! $cancel );
		
		if( ( ! $testMode ) && $canAddPlayer )
		{
			$this->players->offsetSet( $playerIndex, $player );
			$this->raiseEventPostAddPlayer( $playerIndex, $player );
		}
		
		return $canAddPlayer;
	}
	
	public function getPlayers()
	{
		return $this->players;
	}
	
	public function getPlayer( $playerIndex )
	{
		return $this->players->offsetGet( $playerIndex );
	}
	
	//-- Rules -----------------------------------//
	
	public function addRule( Interfaces\GameObserver $rule, $testMode = false )
	{
		$cancel = false;
		$this->raiseEventPreAddRule( $rule, $cancel );
		$canAddRule = ( ! $cancel );
		
		if( ( ! $testMode ) && $canAddRule )
		{
			$this->rules->append( $rule );
			$this->observers->append( $rule );
			$this->raiseEventPostAddRule( $rule );
		}
		
		return $canAddRule;
	}
	
	public function getRules()
	{
		return $this->rules;
	}
	
	//-- Desires ---------------------------------//
	
	public function addDesire( Desire $desire, $testMode = false )
	{
		$this->assertOperable( 'add desire' );
		
		$cancel = false;
		$this->raiseEventPreAddDesire( $desire, $cancel );
		$canAddDesire = ( ! $cancel );
		
		if( ( ! $testMode ) && $canAddDesire )
		{
			$this->desires->append( $desire );
			$this->raiseEventPostAddDesire( $desire );
		}
		
		return $canAddDesire;
	}
	
	public function getDesires()
	{
		return $this->desires;
	}
	
	public function getTurn()
	{
		return $this->turn;
	}
	
	//---------------------------------------------------------------------//
	// Event definition                                                    //
	//---------------------------------------------------------------------//
	
	private function raiseEventPreStart( & $cancel )
	{
		$this->raiseGenericPreEvent( 'onGamePreStart', $cancel );
	}
	
	private function raiseEventPostStart()
	{
		$this->raiseGenericPostEvent( 'onGamePostStart' );
	}
	
	private function raiseEventTick()
	{
		$this->raiseGenericPostEvent( 'onGameTick' );
	}
	
	private function raiseEventPreAbort( & $cancel )
	{
		$this->raiseGenericPreEvent( 'onGamePreAbort', $cancel );
	}
	
	private function raiseEventPostAbort()
	{
		$this->raiseGenericPostEvent( 'onGamePostAbort' );
	}
	
	private function raiseEventPreEnd( & $cancel )
	{
		$this->raiseGenericPreEvent( 'onGamePreEnd', $cancel );
	}
	
	private function raiseEventPostEnd()
	{
		$this->raiseGenericPostEvent( 'onGamePostEnd' );
	}
	
	private function raiseEventPreCloseTurn( & $cancel )
	{
		$turn = $this->turn;
		$this->raiseGenericPreEventWithParameters( 'onGamePreCloseTurn', $turn, $cancel );
	}
	
	private function raiseEventPostCloseTurn()
	{
		$turn = $this->turn;
		$this->raiseGenericPostEventWithParameters( 'onGamePostCloseTurn', $turn );
	}
	
	private function raiseEventPreResetTurn( & $cancel )
	{
		$turn = $this->turn;
		$this->raiseGenericPreEventWithParameters( 'onGamePreResetTurn', $turn, $cancel );
	}
	
	private function raiseEventPostResetTurn()
	{
		$turn = $this->turn;
		$this->raiseGenericPostEventWithParameters( 'onGamePostResetTurn', $turn );
	}
	
	private function raiseEventPreOpenTurn( $turnAboutToBeOpen, & $cancel )
	{
		$this->raiseGenericPreEventWithParameters( 'onGamePreOpenTurn', $turnAboutToBeOpen, $cancel );
	}
	
	private function raiseEventPostOpenTurn()
	{
		$turn = $this->turn;
		$this->raiseGenericPostEventWithParameters( 'onGamePostOpenTurn', $turn );
	}
	
	private function raiseEventPreAddPlayer( $playerId, $player, & $cancel )
	{
		$params = array( 'playerId' => $playerId, 'player' => $player );
		$this->raiseGenericPreEventWithParameters( 'onGamePreAddPlayer', $params, $cancel );
	}
	
	private function raiseEventPostAddPlayer( $playerId, $player )
	{
		$params = array( 'playerId' => $playerId, 'player' => $player );
		$this->raiseGenericPostEventWithParameters( 'onGamePostAddPlayer', $params );
	}
	
	private function raiseEventPreAddRule( $rule, & $cancel )
	{
		$this->raiseGenericPreEventWithParameters( 'onGamePreAddRule', $rule, $cancel );
	}
	
	private function raiseEventPostAddRule( $rule )
	{
		$this->raiseGenericPostEventWithParameters( 'onGamePostAddRule', $rule );
	}
	
	private function raiseEventPreAddDesire( $desire, & $cancel )
	{
		$this->raiseGenericPreEventWithParameters( 'onGamePreAddDesire', $desire, $cancel );
	}
	
	private function raiseEventPostAddDesire( $desire )
	{
		$this->raiseGenericPostEventWithParameters( 'onGamePostAddDesire', $desire );
	}
	
	//---------------------------------------------------------------------//
	// Event definition (generic)                                          //
	//---------------------------------------------------------------------//
	
	private function raiseGenericPreEvent( $methodName, & $cancel )
	{
		$parameters = null;
		$this->raiseGenericPreEventWithParameters( $methodName, $parameters, $cancel );
	}
	
	private function raiseGenericPreEventWithParameters( $methodName, & $parameters, & $cancel )
	{
		foreach( $this->observers as $observer )
		{
			if( method_exists( $observer, $methodName ) )
			{
				if( is_null( $parameters ) )
				{
					$observer->$methodName( $this, & $cancel );
				}
				else
				{
					$observer->$methodName( $this, $parameters, & $cancel );
				}
			}
			
			if( $cancel )
			{
				break;
			}
		}
	}
	
	private function raiseGenericPostEvent( $methodName )
	{
		$parameters = null;
		$this->raiseGenericPostEventWithParameters( $methodName, $parameters );
	}
	
	private function raiseGenericPostEventWithParameters( $methodName, $parameters )
	{
		foreach( $this->observers as $observer )
		{
			if( method_exists( $observer, $methodName ) )
			{
				if( is_null( $parameters ) )
				{
					$observer->$methodName( $this );
				}
				else
				{
					$observer->$methodName( $this, $parameters );
				}
			}
		}
	}
	
	//---------------------------------------------------------------------//
	// Private                                                             //
	//---------------------------------------------------------------------//
	
	private function assertStartable()
	{
		if( $this->started )
		{
			throw new \RuntimeException( 'Cannot start an already started game.' );
		}
	}
	
	private function assertOperable( $operation )
	{
		if( ! $this->started )
		{
			throw new \RuntimeException( 'Cannot ' . $operation . ' on a non started game.' );
		}
		
		if( $this->ended )
		{
			throw new \RuntimeException( 'Cannot ' . $operation . ' on an ended game.' );
		}
	}
	
	private function addObserver( Interfaces\GameObserver $observer )
	{
		$this->observers->append( $observer );
	}
	
	private function openNextTurn()
	{
		$this->openTurnAhead( 1 );
	}
	
	private function openSameTurn()
	{
		$this->openTurnAhead( 0 );
	}
	
	private function openTurnAhead( $increment )
	{
		$this->assertOperable( 'open turn' );
		
		$cancel = false;
		$turnAboutToBeOpen = $this->turn + $increment;
		$this->raiseEventPreOpenTurn( $turnAboutToBeOpen, $cancel );
		$canOpenTurn = ( ! $cancel );
		
		if( $canOpenTurn )
		{
			$this->turn = $turnAboutToBeOpen;
			$this->desires = new \ArrayObject();
			$this->raiseEventPostOpenTurn();
		}
	}
}
