<?php

namespace Xmontero\Emperors\ModelBundle\Model\ObjectStorage;

class ObjectStorageManager
{
	private $logger;
	private $doctrine;
	private $connection;
	
	public function __construct( $logger, $doctrine )
	{
		$this->logger = $logger;
		$this->doctrine = $doctrine;
		$this->connection = $doctrine->getManager()->getConnection();
	}
	
	public function getObjectById( $id )
	{
		$query = "SELECT * FROM objects WHERE  id='" . $id . "'";
		$statement = $this->connection->prepare( $query );
		$statement->execute();
		$objects = $statement->fetchAll();
		
		return $objects[ 0 ];
	}
	
	public function setObject( $id, $object )
	{
		throw new \Exception( 'Not implemented' );
	}
}
