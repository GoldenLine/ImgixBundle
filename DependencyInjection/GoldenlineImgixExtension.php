<?php

namespace GoldenLine\ImgixBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Imgix\ShardStrategy;

class GoldenlineImgixExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (false === array_key_exists($config['default_source'], $config['sources'])) {
            throw new \InvalidArgumentException('Default source should be one of: ' . implode(', ', array_keys($config['sources'])));
        }

        $container->setParameter('goldenline_imgix.default_source', $config['default_source']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $extension = $container->getDefinition('goldenline_imgix.twig.url_builder_extension');
        $class = $container->getParameter('goldenline_imgix.url_builder.class');

        foreach ($config['sources'] as $name => $source) {
            $domains = $source['domains'];
            $tls = true;
            $key = $source['sign_key'];
            $strategy = 'cycle' === $source['shard_strategy']
                ? ShardStrategy::CYCLE
                : ShardStrategy::CRC;

            $definition = new Definition($class, [$domains, $tls, $key, $strategy]);

            $id = sprintf('goldenline_imgix.url_builder_%s', $name);

            $container->setDefinition($id, $definition);

            $extension->addMethodCall('addBuilder', [$name, new Reference($id)]);
        }
    }
}
