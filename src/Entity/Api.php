<?php

namespace Drupal\sharpspring_integration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the API entity.
 *
 * @ingroup sharpspring_integration
 *
 * @ConfigEntityType(
 *   id = "api",
 *   label = @Translation("API"),
 *   admin_permission = "administer sharpspring",
 *   handlers = {
 *     "access" = "Drupal\sharpspring_integration\ApiAccessController",
 *     "list_builder" = "Drupal\sharpspring_integration\Controller\ApiListBuilder",
 *     "form" = {
 *       "edit" = "Drupal\sharpspring_integration\Form\ApiEditForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/sharpspring-integration/api/{api}"
 *   },
 *   config_export = {
 *     "id",
 *     "uuid",
 *     "accountId",
 *     "secretKey",
 *   }
 * )
 */
class Api extends ConfigEntityBase {

  /**
   * The API ID.
   *
   * @var string
   */
  public $id;

  /**
   * The API UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The account ID.
   *
   * @var string
   */
  public $accountId;

  /**
   * The account secret key.
   *
   * @var string
   */
  public $secretKey;
}
