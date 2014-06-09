<?php
/**
 * @package logging-bundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 09.06.14
 * @time 17:35
 */

namespace CodeLovers\LoggingBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('code_lovers_logging');

        $rootNode
            ->children()
                ->arrayNode('prowl')
                    ->children()
                        ->booleanNode('debug')->defaultValue(false)->end()
                        ->scalarNode('provider_key')->defaultValue('')->end()
                        ->scalarNode('api_key')->defaultValue('')->end()
                        ->scalarNode('app_name')->defaultValue('')->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}