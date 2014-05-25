<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Items;

class Chest extends ItemHelper implements IItem
{
	public function __construct()
	{
		parent::__construct();
		$this->type = 'chest';
	}
}
