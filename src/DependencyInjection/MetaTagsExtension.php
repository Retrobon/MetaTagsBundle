<?php

namespace retrobon\MetaTagsBundle\DependencyInjection;

use Exception;
use retrobon\MetaTagsBundle\Service\MetaTags;
use retrobon\MetaTagsBundle\Twig\MetaTagsTwigExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MetaTagsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(MetaTags::class);
        $definition->setArguments([
            '$conf' => $config,
        ]);
    }
}
