<?php

namespace Xmontero\Emperors\ServerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StateCommand extends Command
{
	protected function configure()
	{
		$this
			->setName( 'emperors:server:state' )
			->setDescription( 'Connects to a running server and prints its state' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$output->writeln( 'Server state is unknown.' );
	}
}
