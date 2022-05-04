<?php

namespace Drupal\tlu_h5p\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\Query\ResultSetInterface;
use Drupal\tlu_h5p\Form\MaterialSearchForm;
use Drupal\tlu_h5p\TagSearchException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for TLU H5P routes.
 */
class SearchController extends ControllerBase {

  /**
   * Node type to search for.
   */
  const NODE_TYPE = 'material';

  /**
   * Results per page.
   */
  const LIMIT = 25;

  /**
   * Term vocabulary identifier for tags.
   */
  const TERM_VOCABULARY_ID = 'tags';

  /**
   * Form builder.
   *
   * @var FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityFormBuilder;

  /**
   * Current request.
   *
   * @var Request
   */
  protected $currentRequest;

  /**
   * Pager manager.
   *
   * @var PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * Messenger.
   *
   * @var MessengerInterface
   */
  protected $messenger;

  /**
   * The controller constructor.
   *
   * @param FormBuilderInterface $formBuilder
   * @param EntityTypeManagerInterface $entityTypeManager
   * @param Request $currentRequest
   * @param PagerManagerInterface $pagerManager
   * @param MessengerInterface $messenger
   */
  public function __construct(FormBuilderInterface $formBuilder, EntityTypeManagerInterface $entityTypeManager, Request $currentRequest, PagerManagerInterface $pagerManager, MessengerInterface $messenger) {
    $this->formBuilder = $formBuilder;
    $this->entityTypeManager = $entityTypeManager;
    $this->currentRequest = $currentRequest;
    $this->pagerManager = $pagerManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder'),
      $container->get('entity_type.manager'),
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('pager.manager'),
      $container->get('messenger')
    );
  }

  /**
   * Converts tags field input into tag identifiers. Makes sure that tags exist.
   *
   * @param string $tags
   * @return array
   * @throws TagSearchException
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getTagIds(string $tags): array
  {
    $ids = [];
    $split = explode(',', $tags);

    foreach ($split as $tag) {
      $processed = trim(preg_replace('/\(\d+\)/', '', $tag));
      $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['name' => $processed, 'vid' => self::TERM_VOCABULARY_ID]);

      if (count($terms) === 0) {
        throw new TagSearchException($processed);
      }

      $ids[] = array_shift($terms)->id();
    }

    return $ids;
  }

  /**
   * Makes a search and returns results object.
   *
   * @param string $keys
   * @param string $language
   * @param string $unit
   * @param string $tags
   * @return ResultSetInterface
   * @throws TagSearchException
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\search_api\SearchApiException
   */
  protected function getSearchResults(string $keys, string $language, string $unit, string $tags): ResultSetInterface {
    $index = Index::load('default_index');
    $query = $index->query();

    // TODO See if we need to modify full text search fields
    $query->keys($keys);
    $query->addCondition('status', 1)
      ->addCondition('type', self::NODE_TYPE)
      ->addCondition('field_listed', 1);

    if ($language && $language !== 'All') {
      $query->setLanguages([$language]);
    }

    if ($unit && $unit !== 'All') {
      $query->addCondition('field_unit', $unit);
    }

    if ($tags) {
      $query->addCondition('field_tags', $this->getTagIds($tags), 'IN');
    }

    $query->addTag('pager');
    $query->range((int)$this->currentRequest->get('page', 0) * self::LIMIT, self::LIMIT);

    return $query->execute();
  }

  /**
   * Extracts entity identifiers from search results.
   *
   * @param ResultSetInterface $results
   * @return array
   */
  protected function getResultIds(ResultSetInterface $results): array {
    return array_map(function($id) {
      return (int) explode(':', explode('/', $id, 2)[1], 2)[0];
    }, array_keys($results->getResultItems()));
  }

  /**
   * Builds the response.
   *
   * @throws \Drupal\search_api\SearchApiException
   */
  public function build(): array {
    $build['search_form'] = $this->formBuilder->getForm(MaterialSearchForm::class);

    $keys = $this->currentRequest->get('search_text');
    $language = $this->currentRequest->get('language');
    $unit = $this->currentRequest->get('unit');
    $tags = $this->currentRequest->get('tags');

    if (!($keys || $language || $unit || $tags)) {
      return $build;
    }

    try {
      $results = $this->getSearchResults($keys, $language, $unit, $tags);
    } catch (TagSearchException $e) {
      $this->messenger->addError($this->t('There are no tags matching <em class="placeholder">@tag</em>.', ['@tag' => $e->getMessage()]));
      return $build;
    }

    if ((int)$results->getResultCount() === 0) {
      $build['no_results'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['no-material-search-results'],
        ],
      ];
      $build['no_results']['text'] = [
        '#plain_text' => $this->t('There were no results for provided search criteria.'),
      ];

      return $build;
    }

    $build['materials'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['material-search-results'],
      ],
    ];
    $build['materials']['results'] = $this
      ->entityTypeManager
      ->getViewBuilder('node')
      ->viewMultiple(Node::loadMultiple($this->getResultIds($results)));

    $this->pagerManager->createPager($results->getResultCount(), self::LIMIT);
    $build['pager'] = [
      '#type' => 'pager',
    ];

    return $build;
  }

}
