<?php

namespace Xmontero\Emperors\ModelBundle\Model\Board;

use Xmontero\Emperors\ModelBundle\Model\Base;

class Tiles extends Base\Collection
{
	public function load( $document )
	{
	}
	
	public function saveToObject()
	{
		foreach( $this as $tile )
		{
			$tileSaveObject = $tile->saveObject();
			$tiles[] = $tileSaveObject;
		}
		
		return $object;
	}
	
	public function saveToJson()
	{
		return json_encode( $this->saveToObject );
	}
}
