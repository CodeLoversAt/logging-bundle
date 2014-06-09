<?php
/**
 * @package logging-bundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 09.06.14
 * @time 17:44
 */

namespace CodeLovers\LoggingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CodeLoversLoggingExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $config               An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($config['prowl']) && isset($config['prowl']['provider_key'])) {
            $prowlConfig = $config['prowl'];

            $container->getDefinition('code_lovers_logging.prowl.connector')
                ->addMethodCall('setProviderKey', array($prowlConfig['provider_key']));

            $container->getDefinition('code_lovers_logging.monolog.handler.prowl')
                ->addArgument($prowlConfig['api_key'])
                ->addArgument($prowlConfig['app_name']);
        }

        if (isset($config['jabber'])) {
            $jabberConfig = $config['jabber'];

            $container->getDefinition('code_lovers_logging.monolog.handler.jabber')
                ->addArgument($jabberConfig['host'])
                ->addArgument($jabberConfig['port'])
                ->addArgument($jabberConfig['user'])
                ->addArgument($jabberConfig['password'])
                ->addArgument($jabberConfig['recipient'])
                ->addArgument($jabberConfig['server'])
                ->addArgument($jabberConfig['use_encryption']);
        }
    }
}