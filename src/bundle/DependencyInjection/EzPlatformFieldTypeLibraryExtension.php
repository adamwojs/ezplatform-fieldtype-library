<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFieldTypeLibraryBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

final class EzPlatformFieldTypeLibraryExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configFile = __DIR__ . '/../Resources/config/prepend.yaml';

        $container->addResource(new FileResource($configFile));
        foreach (Yaml::parseFile($configFile) as $name => $config) {
            $container->prependExtensionConfig($name, $config);
        }
    }
}
