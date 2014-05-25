<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Items;

class Wealth extends ItemHelper implements IItem
{
	public function __construct()
	{
		parent::__construct();
		$this->type = 'wealth';
		$this->namePrefix = 'W';
	}
}
