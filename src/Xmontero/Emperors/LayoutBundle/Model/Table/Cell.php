<?php

namespace Xmontero\Emperors\LayoutBundle\Model\Table;

class Cell
{
	private $value;
	private $dataType;
	
	public function __construct( $initialValue )
	{
		$this->value = $initialValue;
	}
	
	public function setDataType( $newDataType )
	{
		switch( $newDataType )
		{
			case 'bool':
			case 'link':
				
				$this->dataType = $newDataType;
				break;
				
			default:
				
				throw new \DomainException( 'Desired type ' . $newDataType . ' is not supported.' );
				break;
		}
	}
	
	public function getDataType()
	{
		return $this->dataType;
	}
	
	public function getValue()
	{
		$result = $this->value;
		return $result;
	}
	
	public function setSameWindowLink( $url )
	{
		return $this->setLink( $url, false );
	}
	
	public function setNewWindowLink( $url )
	{
		return $this->setLink( $url, true );
	}
	
	private function setLink( $url, $newWindow )
	{
		$oldValue = $this->value;
		
		$newValue = array();
		$newValue[ 'url' ] = $url;
		$newValue[ 'display' ] = $oldValue;
		$newValue[ 'newWindow' ] = $newWindow;
		
		$this->value = $newValue;
		$this->setDataType( 'link' );
		
		return $this;
	}
	
}
