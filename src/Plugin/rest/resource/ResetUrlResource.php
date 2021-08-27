<?php

namespace Drupal\rest_pass_reset_url\Plugin\rest\resource;

use Drupal\Core\Database\Connection;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\user\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Represents Reset URL records as resources.
 *
 * @RestResource (
 *   id = "pass_reset_url",
 *   label = @Translation("Password Reset URL"),
 *   uri_paths = {
 *     "canonical" = "/entity/reset_url",
 *     "https://www.drupal.org/link-relations/create" = "/entity/reset_url"
 *   }
 * )
 *
 * @DCG
 * This plugin exposes database records as REST resources. In order to enable it
 * import the resource configuration into active configuration storage. You may
 * find an example of such configuration in the following file:
 * core/modules/rest/config/optional/rest.resource.entity.node.yml.
 * Alternatively you can make use of REST UI module.
 * @see https://www.drupal.org/project/restui
 * For accessing Drupal entities through REST interface use
 * \Drupal\rest\Plugin\rest\resource\EntityResource plugin.
 */
class ResetUrlResource extends ResourceBase {
  protected $dbConnection;
  protected $currentRequest;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, LoggerInterface $logger, Connection $db_connection, Request $current_request) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->dbConnection = $db_connection;
    $this->currentRequest = $current_request;
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
      $container->get('database'),
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  public function get() {
    $user_id = (int) $this->currentRequest->query->get('user');
    return $this->_generate_password_reset_url($user_id);
  }

  public function validate(){

  }

  public function post($data) {
    $user_id = (int) $data['user'];
    return $this->_generate_password_reset_url($user_id);
  }

  protected function _generate_password_reset_url($user_id){
    $user = User::load($user_id);

    if(!$user) throw new NotFoundHttpException("Invalid user identifier is given.");
    return new ModifiedResourceResponse(
      ['url' => user_pass_reset_url($user)],
      201
    );
  }
}
