<?php

namespace Xmontero\Emperors\LayoutBundle\Model\Table;

class Row
{
	private $cells = array();
	
	public function addCell( $value )
	{
		$cell = new Cell( $value );
		$this->cells[] = $cell;
		return $cell;
	}
	
	public function cells()
	{
		return $this->cells;
	}
}
