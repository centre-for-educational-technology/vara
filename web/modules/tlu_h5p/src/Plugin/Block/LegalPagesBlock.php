<?php

namespace Drupal\tlu_h5p\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides a legal pages block.
 *
 * @Block(
 *   id = "tlu_h5p_legal_pages",
 *   admin_label = @Translation("Legal pages"),
 *   category = @Translation("Custom")
 * )
 */
class LegalPagesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['legal-pages'],
      ],
    ];
    $build['content']['privacy_policy'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/privacy-policy'),
      '#title' => $this->t('Privacy Policy'),
      '#attributes' => [
        'class' => ['legal-page', 'privacy-policy'],
      ],
    ];
    $build['content']['terms_and_conditions'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/terms-and-conditions'),
      '#title' => $this->t('Terms and Conditions'),
      '#attributes' => [
        'class' => ['legal-page', 'terms-and-conditions'],
      ],
    ];
    return $build;
  }

}
