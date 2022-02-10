<?php

namespace Drupal\tlu_h5p;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

class TluH5pServiceProvider extends ServiceProviderBase implements ServiceModifierInterface
{

    /**
     * @inheritDoc
     */
    public function alter(ContainerBuilder $container)
    {
      if ($container->hasDefinition('tagclouds.cloud_builder')) {
        $definition = $container->getDefinition('tagclouds.cloud_builder');
        $definition->setClass(TluH5pCloudBuilder::class);
      }
    }

}
