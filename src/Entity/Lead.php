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
 *     "ownerID",
 *     "leadStatus",
 *     "firstName",
 *     "lastName",
 *     "emailAddress",
 *     "phoneNumber",
 *     "companyName",
 *     "website",
 *     "description",
 *     "isUnsubscribed",
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
   * The Lead entity UUID
   *
   * @var string
   */
  public $uuid;

  /**
   * Lead owner ID
   *
   * @var string
   */
  public $ownerID;

  /**
   * Lead status
   *
   * @var string
   */
  public $leadStatus;

  /**
   * Lead first name
   *
   * @var string
   */
  public $firstName;

  /**
   * Lead last name
   *
   * @var string
   */
  public $lastName;

  /**
   * Lead email address
   *
   * @var string
   */
  public $emailAddress;

  /**
   * Lead phone number
   *
   * @var string
   */
  public $phoneNumber;

  /**
   * Lead company name
   *
   * @var string
   */
  public $companyName;

  /**
   * Lead website
   *
   * @var string
   */
  public $website;

  /**
   * Lead description
   *
   * @var array
   */
  public $description;

  /**
   * Lead opt-in status
   *
   * @var tinyint
   */
  public $isUnsubscribed;
}
