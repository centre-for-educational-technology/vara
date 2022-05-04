<?php

namespace Drupal\tlu_h5p\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a TLU H5P form.
 */
class MaterialSearchForm extends FormBase {

  /**
   * Node type to search for.
   */
  const NODE_TYPE = 'material';

  /**
   * Entity field manager service.
   *
   * @var EntityFieldManagerInterface
   */
  protected EntityFieldManagerInterface $entityFieldManager;

  /**
   * Language manager service.
   *
   * @var LanguageManagerInterface
   */
  protected LanguageManagerInterface $languageManager;

  /***
   * @param EntityFieldManagerInterface $entityFieldManager
   * @param LanguageManagerInterface $languageManager
   */
  public function __construct(EntityFieldManagerInterface $entityFieldManager, LanguageManagerInterface $languageManager)
  {
    $this->entityFieldManager = $entityFieldManager;
    $this->languageManager = $languageManager;
  }

  /**
   * @param ContainerInterface $container
   * @return MaterialSearchForm|static
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_field.manager'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tlu_h5p_material_search';
  }

  /**
   * Prepends the - All - option.
   * @param array $options
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup[]
   */
  private function prependAllOption(array $options): array {
    return array_merge([
      'All' => $this->t('- All -'),
    ], $options);
  }

  /**
   * Returns unit field options.
   *
   * @param bool $prependAll
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup[]
   */
  private function getUnitOptions(bool $prependAll = true): array {
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('node', self::NODE_TYPE);

    $options = options_allowed_values($fields['field_unit']);

    return $prependAll ? $this->prependAllOption($options) : $options;
  }

  /**
   * Returns language field options.
   *
   * @param bool $prependAll
   * @return array|\Drupal\Core\StringTranslation\TranslatableMarkup[]
   */
  private function getLanguageOptions(bool $prependAll = true): array {
    $options = [];

    foreach ($this->languageManager->getLanguages() as $langcode => $language) {
      $options[$langcode] = $language->getName();
    }

    return $prependAll ? $this->prependAllOption($options) : $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state
      ->setMethod('GET');

    $searchText = $this->getRequest()->get('search_text', '');
    $language = $this->getRequest()->get('language');
    $unit = $this->getRequest()->get('unit');
    $tags = $this->getRequest()->get('tags');

    $form['#cache'] = [
      'max-age' => 0,
    ];

    $form['search_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search text'),
      '#required' => FALSE,
      '#default_value' => 'All',
      '#value' => $searchText,
      '#attributes' => [
        'placeholder' => $this->t('Search'),
      ],
    ];

    $form['language'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#required' => FALSE,
      '#options' => $this->getLanguageOptions(),
      '#value' => $language,
    ];

    $form['unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Unit'),
      '#required' => FALSE,
      '#options' => $this->getUnitOptions(),
      '#value' => $unit,
    ];

    $form['tags'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#tags' => TRUE,
      '#title' => $this->t('Tags'),
      '#value' => $tags,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];
    $form['actions']['reset'] = [
      '#type' => 'button',
      '#value' => $this->t('Reset'),
      '#ajax' => [
        'callback' => '::doReset',
      ],
      '#limit_validation_errors' => [],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * Reset the search from by redirecting to current route with an ajax command.
   *
   * @return AjaxResponse
   */
  public function doReset(): AjaxResponse {
    $response = new AjaxResponse();

    $response->addCommand(new RedirectCommand(Url::fromRoute('<current>', [], ['absolute' => TRUE])->toString()));

    return $response;
  }

}
