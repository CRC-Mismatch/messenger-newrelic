<?php declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('arxus_messenger_newrelic');

        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('mappings')
            ->info('Alternative to NewrelicMessage attributes, accepts a list of mappings with `target` referring to the target message class, and all arguments accepted by NewrelicMessage.')
            ->fixXmlConfig('mapping')
            ->useAttributeAsKey('target')
            ->arrayPrototype()
            ->children()
            ->scalarNode('transaction_name')->isRequired()->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
