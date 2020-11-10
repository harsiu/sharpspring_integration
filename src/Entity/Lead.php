<?php

namespace Drupal\sharpspring_integration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Lead entity.
 *
 * @ingroup sharpspring_integration
 *
 * @ConfigEntityType(
 *   id = "lead",
 *   label = @Translation("Sharpspring form settings"),
 *   admin_permission = "administer sharpspring",
 *   handlers = {
 *     "access" = "Drupal\sharpspring_integration\ApiAccessController",
 *     "list_builder" = "Drupal\sharpspring_integration\Controller\LeadListBuilder",
 *     "form" = {
 *       "add" = "Drupal\sharpspring_integration\Form\SharpspringAddForm",
 *       "edit" = "Drupal\sharpspring_integration\Form\SharpspringEditForm",
 *       "delete" = "Drupal\sharpspring_integration\Form\SharpspringDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/sharpspring-integration/form/{lead}",
 *     "delete-form" = "/admin/config/sharpspring-integration/form/{lead}/delete"
 *   },
 *   config_export = {
 *     "id",
 *     "uuid",
 *     "firstName",
 *     "lastName",
 *     "emailAddress",
 *     "phoneNumber",
 *     "companyName",
 *     "website",
 *     "description",
 *   }
 * )
 */
class Lead extends ConfigEntityBase {

  /**
   * The Lead entity ID.
   *
   * @var string
   */
  public $id;

  /**
   * The Lead entity UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * Webform firstName field ID.
   *
   * @var string
   */
  public $firstName;

  /**
   * Webform lastName field ID.
   *
   * @var string
   */
  public $lastName;

  /**
   * Webform emailAddress field ID.
   *
   * @var string
   */
  public $emailAddress;

  /**
   * Webform phoneNumber field ID.
   *
   * @var string
   */
  public $phoneNumber;

  /**
   * Webform companyName field ID.
   *
   * @var string
   */
  public $companyName;

  /**
   * Webforom website field ID.
   *
   * @var string
   */
  public $website;

  /**
   * Webform description field ID.
   *
   * @var array
   */
  public $description;
}
