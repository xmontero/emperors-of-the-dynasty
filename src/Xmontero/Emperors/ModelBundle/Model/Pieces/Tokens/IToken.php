<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Tokens;

use Xmontero\Emperors\ModelBundle\Model\Board\IPiece;

interface IToken extends IPiece
{
	public function __construct( $playerId );
	public function getPlayerId();
	public function getExperience();
	public function setExperience( $newExperience );
	public function getUsableExperience();
	public function setUsableExperience( $newUsableExperience );
	public function getLiveQuarters();
	public function setLiveQuarters( $newLiveQuarters );
}
