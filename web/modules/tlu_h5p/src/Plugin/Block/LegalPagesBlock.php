<?php

namespace Drupal\tlu_h5p\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a legal pages block.
 *
 * @Block(
 *   id = "tlu_h5p_legal_pages",
 *   admin_label = @Translation("Legal pages"),
 *   category = @Translation("Custom")
 * )
 */
final class LegalPagesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Language manager
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LanguageManagerInterface $languageManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->languageManager = $languageManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   *
   * @return \Drupal\tlu_h5p\Plugin\Block\LegalPagesBlock|static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $options = [
      'language' => $this->languageManager->getCurrentLanguage(),
    ];
    $termsAndConditionsUrl = Url::fromUri('internal:/terms-and-conditions', $options);

    $build['content'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['legal-pages'],
      ],
    ];
    $build['content']['terms_and_conditions'] = [
      '#type' => 'link',
      '#url' => $termsAndConditionsUrl,
      '#title' => $this->t('Terms and Conditions'),
      '#attributes' => [
        'class' => ['legal-page', 'terms-and-conditions'],
        'data-url' => $termsAndConditionsUrl->toString(),
      ],
    ];
    return $build;
  }

}
