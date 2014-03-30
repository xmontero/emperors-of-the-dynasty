<?php

namespace Xmontero\Emperors\ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand as Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class StartCommand extends Command
{
	protected function configure()
	{
		$this
			->setName( 'emperors:server:start' )
			->setDescription( 'Starts the server' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$emperorsServer = $this->getContainer()->get( 'emperors.server' );
		
		$server = IoServer::factory(
        new HttpServer(
            new WsServer(
                $emperorsServer
            )
        ),
        8080
    );
		$server->run();
	}
}
