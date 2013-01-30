<?php
namespace Rhapsody\SetupBundle;

use Rhapsody\SetupBundle\DependencyInjection\RhapsodySetupExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RhapsodySetupBundle extends Bundle
{

	public function boot()
	{
		parent::boot();
	}

	/**
	 * {@inheritDoc}
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->registerExtension(new RhapsodySetupExtension());
	}
}