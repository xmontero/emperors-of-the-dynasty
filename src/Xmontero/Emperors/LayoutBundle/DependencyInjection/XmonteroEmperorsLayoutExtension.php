<?php

namespace Xmontero\Emperors\LayoutBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class XmonteroEmperorsLayoutExtension extends Extension
{
	public function load( array $configs, ContainerBuilder $container )
	{
		//$configuration = new Configuration();
		//$processedConfig = $this->processConfiguration( $configuration, $configs );
		
		$loader = new YamlFileLoader( $container, new FileLocator( __DIR__ . '/../Resources/config' ) );
		$loader->load( 'services.yml' );
	}
}
