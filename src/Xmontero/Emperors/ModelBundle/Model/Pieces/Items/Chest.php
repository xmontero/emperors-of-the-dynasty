<?php

namespace Xmontero\Emperors\ModelBundle\Model\Pieces\Items;

class Chest extends ItemHelper implements IItem
{
	private $open;
	
	public function __construct()
	{
		parent::__construct();
		$this->type = 'chest';
		$this->close();
	}
	
	public function isOpen()
	{
		return $this->open;
	}
	
	public function open()
	{
		$this->open = true;
	}
	
	public function close()
	{
		$this->open = false;
	}
}
