<?php

namespace Drupal\tlu_h5p;

use Drupal\Core\Url;
use Drupal\tagclouds\CloudBuilder;

class TluH5pCloudBuilder extends CloudBuilder
{

  /**
   * {@inheritdoc}
   */
  public function build(array $terms) {
    $output = parent::build($terms);

    array_walk($output, function(&$item, $tid) {
      if (isset($item['#url']) && $item['#url']->getRouteName() === 'entity.taxonomy_term.canonical') {
        $options = $item['#url']->getOptions();
        $options['query']['tag'] = $tid;
        $item['#url'] = Url::fromRoute('view.all_materials.all_materials', [], $options);
      }
    });

    return $output;
  }

}
