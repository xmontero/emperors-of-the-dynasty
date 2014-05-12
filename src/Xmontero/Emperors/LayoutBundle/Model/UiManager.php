<?php

namespace Xmontero\Emperors\LayoutBundle\Model;

class UiManager
{
	public function createTable()
	{
		$table = new Table\Table;
		return $table;
	}
}
