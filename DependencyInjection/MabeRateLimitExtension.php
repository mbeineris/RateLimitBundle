<?php

namespace Mabe\RateLimitBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MabeRateLimitExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('mabe_rate_limit.enabled', $config['enabled']);
        $container->setParameter('mabe_rate_limit.paths', $config['paths']);
        if (!empty($config['redis'])) {
            $container->setParameter('mabe_rate_limit.redis_dsn', 'redis://'.$config['redis']['host'].':'.$config['redis']['port'].'/'.$config['redis']['database']);
            $loader->load('services.yml');
        }
    }
}
