<?php
namespace Rhapsody\SetupBundle\DependencyInjection;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\DependencyInjection
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class Configuration implements ConfigurationInterface
{

	/**
	 * Generates the configuration tree builder.
	 *
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
	 */
	public function getConfigTreeBuilder()
	{
		$resourceDir = $this->getResourceDirectory();

		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('rhapsody_setup');

		$rootNode
			->children()
				->scalarNode('environment')->cannotBeEmpty()->defaultValue('dev')->end()
			->end()
		;
		return $treeBuilder;
	}

	private function getResourceDirectory() {
		$dir = dirname(__FILE__);
		$dir .= '..' . DIRECTORY_SEPARATOR . 'Resources';
		return realpath($dir);
	}

}
