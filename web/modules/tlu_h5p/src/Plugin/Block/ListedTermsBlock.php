<?php

namespace Drupal\tlu_h5p\Plugin\Block;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;
use Drupal\tagclouds\Plugin\Block\TagcloudsTermsBlock;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * Provides a template for blocks based of each vocabulary. Only considers listed entities.
 *
 * @Block(
 *   id = "listed_tagclouds_block",
 *   admin_label = @Translation("Tagclouds terms for listed entities"),
 *   category = @Translation("Tagclouds listed"),
 *   deriver = "Drupal\tagclouds\Plugin\Derivative\TagcloudsTermsBlock"
 * )
 *
 * @see \Drupal\tagclouds\Plugin\Derivative\TagcloudsTermsBlock
 */
class ListedTermsBlock extends TagcloudsTermsBlock
{

  /**
   * {@inheritdoc}
   */
  public function build() {
    $tags_limit = $this->configuration['tags'];
    $vocab_name = $this->configuration['vocabulary'];

    $content = [
      '#attached' => ['library' => 'tagclouds/clouds'],
    ];
    if ($voc = Vocabulary::load($vocab_name)) {
      $tags = $this->tagService->getListedTags([$vocab_name], $this->configFactory->getEditable('tagclouds.settings')->get('levels'), $tags_limit);

      $tags = $this->tagService->sortTags($tags);

      $content[] = [
        'tags' => $this->cloudBuilder->build($tags),
      ];
      if (count($tags) >= $tags_limit && $tags_limit > 0) {
        $content[] = [
          '#type' => 'more_link',
          '#title' => $this->t('more tags'),
          '#url' => Url::fromRoute('tagclouds.chunk_vocs', ['tagclouds_vocs_str' => $voc->id()]),
        ];
      }
    }

    return $content;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['node_list', 'config:tagclouds.settings', 'taxonomy_term_list']);
  }

}
