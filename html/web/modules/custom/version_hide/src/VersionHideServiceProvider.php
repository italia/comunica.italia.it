<?php

namespace Drupal\version_hide;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Defines a Service Provider for the version_hide module.
 */
class VersionHideServiceProvider extends ServiceProviderBase {

  /**
   * Removes the X-Generator HTTP Header to hide the current
   * version of Drupal.
   *
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   */
  public function alter(ContainerBuilder $container) {
    $container->removeDefinition('response_generator_subscriber');
  }
}
