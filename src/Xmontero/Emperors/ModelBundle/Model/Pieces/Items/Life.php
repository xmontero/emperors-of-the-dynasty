<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Items;

class Life extends ItemHelper implements IItem
{
	public function __construct()
	{
		parent::__construct();
		$this->type = 'life';
		$this->namePrefix = 'L';
	}
}
