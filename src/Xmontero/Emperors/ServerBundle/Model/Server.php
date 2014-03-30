<?php

namespace Xmontero\Emperors\ServerBundle\Model;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface
{
	protected $logger;
	protected $clients;
	
	public function __construct( $logger )
	{
		$this->logger = $logger;
		$this->clients = new \SplObjectStorage;
	}
	
	public function onOpen( ConnectionInterface $connection )
	{
		$this->clients->attach( $connection );
		echo( 'New connection! ( ' . $connection->resourceId . ')' . PHP_EOL );
	}
	
	public function onMessage( ConnectionInterface $from, $msg )
	{
		$numRecv = count( $this->clients ) - 1;
		
		$logMessage = sprintf
		(
			'Connection %d sending message "%s" to %d other connection%s' . "\n",
			$from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'
		);
		
		echo $logMessage;
		
		$len = mb_strlen( $msg );
		for( $i = 0; $i < $len; $i++ )
		{
			$c = $msg[ $i ];
			echo( ord( $c ) . ' - "' . $c . '"' .  PHP_EOL );
		}
		
		if( ( ord( $msg ) == 4 ) || ( $msg == "exit\n" ) || ( $msg == "exit" ) )
		{
			$from->close();
		}
		else
		{
			foreach( $this->clients as $client )
			{
				if( $from !== $client )
				{
					$client->send( 'Lilo-lalo: ' . $msg );
				}
			}
		}
	}
	
	public function onClose( ConnectionInterface $connection )
	{
		$this->clients->detach( $connection );
		echo "Connection {$connection->resourceId} has disconnected\n";
	}
	
	public function onError( ConnectionInterface $connection, \Exception $e )
	{
		echo "An error has occurred: {$e->getMessage()}\n";
		$connection->close();
	}
}
