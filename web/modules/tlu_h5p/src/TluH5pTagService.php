<?php

namespace Drupal\tlu_h5p;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\tagclouds\TagService;

class TluH5pTagService extends TagService implements TluH5pTagServiceInterface
{

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_store
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(ConfigFactoryInterface $config_factory, LanguageManagerInterface $language_manager, CacheBackendInterface $cache_store, Connection $connection) {
    parent::__construct($config_factory, $language_manager, $cache_store);
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getListedTags(array $vids, $steps = 6, $size = 60, $display = NULL) {
    // Build the options so we can cache multiple versions.
    $language = $this->languageManager->getCurrentLanguage();
    $options = implode('_', $vids) . '_' . $language->getId() . '_' . $steps . '_' . $size . "_" . $display;
    // Check if the cache exists.
    // MODIFIED Changed cache name
    $cache_name = 'listed_tagclouds_cache_' . $options;
    $cache = $this->cacheStore->get($cache_name);
    $tags = [];
    // Make sure cache has data.
    if (!empty($cache->data)) {
      $tags = $cache->data;
    }
    else {

      if (count($vids) == 0) {
        return [];
      }
      $config = $this->configFactory->get('tagclouds.settings');

      $query = $this->connection->select('taxonomy_term_data', 'td');
      $query->addExpression('COUNT(td.tid)', 'count');
      $query->fields('tfd', ['name', 'description__value']);
      $query->fields('td', ['tid', 'vid']);
      $query->addExpression('MIN(tn.nid)', 'nid');

      $query->join('taxonomy_index', 'tn', 'td.tid = tn.tid');
      $query->join('node_field_data', 'n', 'tn.nid = n.nid');
      $query->join('taxonomy_term_field_data', 'tfd', 'tfd.tid = tn.tid');
      // MODIFIED Joined listed field
      $query->join('node__field_listed', 'nfl', 'nfl.entity_id = n.nid AND nfl.revision_id = n.vid');

      if ($config->get('language_separation')) {
        $query->condition('n.langcode', $language->getId());
      }

      $query->condition('td.vid', $vids);
      $query->condition('n.status', 1);
      // MODIFIED Added listed condition
      $query->condition('nfl.field_listed_value', 1);

      $query->groupBy('td.tid')->groupBy('td.vid')->groupBy('tfd.name');
      $query->groupBy('tfd.description__value');

      $query->having('COUNT(td.tid)>0');
      $query->orderBy('count', 'DESC');

      if ($size > 0) {
        $query->range(0, $size);
      }
      $result = $query->execute()->fetchAll();

      foreach ($result as $tag) {
        $tags[$tag->tid] = $tag;
      }
      if ($display == NULL) {
        $display = $config->get('display_type');
      }

      // MODIFIED Made buildWeightedTags method accessible and invoked that on current service instance
      $method = new \ReflectionMethod(TagService::class, 'buildWeightedTags');
      $method->setAccessible(true);

      $tags = $method->invoke($this, $tags, $steps);
      //$tags = $this->buildWeightedTags($tags, $steps);

      $this->cacheStore->set($cache_name, $tags, CacheBackendInterface::CACHE_PERMANENT, ['node_list', 'taxonomy_term_list', 'config:tagclouds.settings']);
    }

    return $tags;
  }

}
