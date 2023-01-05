<?php

namespace Drupal\role_fields\Plugin\rest\resource;

use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Represents Roles_Fields records as resources.
 *
 * @RestResource (
 *   id = "role_fields_roles_fields",
 *   label = @Translation("Roles_Fields"),
 *   uri_paths = {
 *     "canonical" = "/api/role-fields-roles-fields/{id}",
 *     "create" = "/api/role-fields-roles-fields"
 *   }
 * )
 *
 * @DCG
 * The plugin exposes key-value records as REST resources. In order to enable it
 * import the resource configuration into active configuration storage. An
 * example of such configuration can be located in the following file:
 * core/modules/rest/config/optional/rest.resource.entity.node.yml.
 * Alternatively you can enable it through admin interface provider by REST UI
 * module.
 * @see https://www.drupal.org/project/restui
 *
 * @DCG
 * Notice that this plugin does not provide any validation for the data.
 * Consider creating custom normalizer to validate and normalize the incoming
 * data. It can be enabled in the plugin definition as follows.
 * @code
 *   serialization_class = "Drupal\foo\MyDataStructure",
 * @endcode
 *
 * @DCG
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
class RolesFieldsResource extends ResourceBase {

  /**
   * The key-value storage.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface
   */
  protected $storage;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    KeyValueFactoryInterface $keyValueFactory,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger, $keyValueFactory);
    $this->storage = $keyValueFactory->get('role_fields_roles_fields');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('keyvalue')
    );
  }

  /**
   * Responds to POST requests and saves the new record.
   *
   * @param array $data
   *   Data to write into the database.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   */
  public function post(array $data) {


    $params = $data;
    $level = $params['level'];
    $grade = $params['grade'];
    $subject = $params['subject'];
    $actor = $params['actor'];
    $verb = $params['verb'];
    $nid = $params['nid'];
    $answer_check = $params['answer_check'];
    $duration = $params['duration'];
    $data = json_encode($params['data']);

    //$msg = $level." ".$grade." ".$subject." ".$actor." ".$verb." ".$duration.' '.$answer_check;

    $connection = \Drupal::service('database');
    $query = $connection->insert('role_fields_extended_xapi')
    ->fields([
      'level' => $level,
      'grade' =>$grade,
      'subject' => $subject,
      'actor' => $actor,
      'verb' => $verb,
      'nid' => $nid,
      'answer_check' => $answer_check,
      'data' => $data,
      'duration' => $duration,
      'created' => \Drupal::time()->getRequestTime(),
    ])->execute();

    $msg = "xAPI statement saved";

    return new ModifiedResourceResponse($msg, 201);
  }

  /**
   * Responds to GET requests.
   *
   * @param int $id
   *   The ID of the record.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the record.
   */
  public function get($id) {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'material');

    $sitename = \Drupal::config('system.site')->get('name');


    $results = $query->execute();
    $msg = ["class"=>$sitename];

    return new ResourceResponse($msg);
  }

  /**
   * {@inheritdoc}
   */
  protected function getBaseRoute($canonical_path, $method) {
    $route = parent::getBaseRoute($canonical_path, $method);
    // Set ID validation pattern.
    if ($method != 'POST') {
      $route->setRequirement('id', '\d+');
    }
    return $route;
  }

  /**
   * Returns next available ID.
   */
  private function getNextId() {
    $ids = \array_keys($this->storage->getAll());
    return count($ids) > 0 ? max($ids) + 1 : 1;
  }

}
