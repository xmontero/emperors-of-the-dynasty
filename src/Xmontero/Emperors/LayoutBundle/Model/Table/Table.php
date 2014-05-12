<?php

namespace Xmontero\Emperors\LayoutBundle\Model\Table;

class Table
{
	private $headers = array();
	private $dataRows = array();
	
	public function addHeader()
	{
		$header = new Row;
		$this->headers[] = $header;
		return $header;
	}
	
	public function addDataRow()
	{
		$dataRow = new Row;
		$this->dataRows[] = $dataRow;
		return $dataRow;
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public function getDataRows()
	{
		return $this->dataRows;
	}
}
