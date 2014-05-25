<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

interface IPiece
{
	public function getType();
	public function getId();
	public function setId( $id );
	public function getName();
}
