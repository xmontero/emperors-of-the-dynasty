<?php

namespace Xmontero\Emperors\ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
		$i = 0;
		
		while( true )
		{
			$output->writeln( 'Starting server - count: ' . $i++ );
			sleep( 1 );
		}
	}
}
