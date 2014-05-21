<?php

namespace Xmontero\Emperors\ModelBundle\Model\Game\Pieces\Items;

use Xmontero\Emperors\ModelBundle\Model\Game\Pieces\PieceHelper;

abstract class ItemHelper extends PieceHelper implements IItem
{
	public function getType()
	{
		return 'item';
	}
}
