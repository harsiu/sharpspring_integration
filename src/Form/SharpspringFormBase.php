<?php

namespace Drupal\sharpspring_integration\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SharpspringFormBase.
 *
 * @ingroup sharpspring_integration
 */
class SharpspringFormBase extends EntityForm {

  /**
   * An entity query factory for the Lead entity type.
   *
   * @var EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * Construct the SharpspringFormBase.
   *
   * @param EntityStorageInterface $entity_storage
   *   An entity query factory for the Lead entity type.
   */
  public function __construct(EntityStorageInterface $entity_storage) {
    $this->entityStorage = $entity_storage;
  }

  /**
   * Factory method for SharpspringFormBase.
   *
   * @param ContainerInterface $container
   *
   * @return SharpspringFormBase|static
   */
  public static function create(ContainerInterface $container) {
    $form = new static($container->get('entity_type.manager')
      ->getStorage('lead'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::form().
   *
   * Builds the entity add/edit form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An associative array containing the Lead add/edit form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get anything we need from the base class.
    $form = parent::buildForm($form, $form_state);
    $lead = $this->entity;

    // Build the form.
    $form['id'] = [
      '#type' => 'machine_name',
      '#title' => $this->t('Webform form ID'),
      '#default_value' => $lead->id(),
      '#machine_name' => [
        'exists' => [$this, 'exists'],
        'replace_pattern' => "([^a-z0-9_]+)|(^custom$)",
        'error' => "The machine-readable name must be unique, and can only contain lowercase letters, numbers, and underscores. Additionally, it can not be the reserved word 'custom'.",
      ],
      '#disabled' => !$lead->isNew(),
    ];

    $form['leadStatus'] = [
      '#type' => 'select',
      '#title' => $this->t('Lead status'),
      '#options' => [
        'unqualified' => $this->t('Unqualified'),
        'open' => $this->t('Open'),
        'qualified' => $this->t('Qualified'),
        'contact' => $this->t('Contact'),
        'customer' => $this->t('Customer'),
      ],
      '#default_value' => $lead->leadStatus,
    ];

    $form['ownerID'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Lead owner account ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->ownerID,
    ];

    $form['lead_info'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Lead information fields'),
    ];

    $form['lead_info']['firstName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->firstName,
    ];

    $form['lead_info']['lastName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->lastName,
    ];

    $form['lead_info']['emailAddress'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email address field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->emailAddress,
      '#required' => TRUE,
    ];

    $form['lead_info']['phoneNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->phoneNumber,
    ];

    $form['lead_info']['companyName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company name field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->companyName,
    ];

    $form['lead_info']['website'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Website url field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->website,
    ];

    $form['lead_info']['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Lead description field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->description,
    ];

    $form['lead_info']['isUnsubscribed'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Lead opt-in field ID'),
      '#maxlength' => 255,
      '#default_value' => $lead->isUnsubscribed,
    ];

    // Return the form.
    return $form;
  }

  /**
   * Checks for an existing Lead entity.
   *
   * @param string|int $entity_id
   *   The entity ID.
   * @param array $element
   *   The form element.
   * @param FormStateInterface $form_state
   *   The form state.
   *
   * @return bool
   *   TRUE if this format already exists, FALSE otherwise.
   */
  public function exists($entity_id, array $element, FormStateInterface $form_state) {
    // Use the query factory to build a new Lead entity query.
    $query = $this->entityStorage->getQuery();

    // Query the entity ID to see if its in use.
    $result = $query->condition('id', $element['#field_prefix'] . $entity_id)
      ->execute();

    // We don't need to return the ID, only if it exists or not.
    return (bool) $result;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::actions().
   *
   * To set the submit button text, we need to override actions().
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return array
   *   An array of supported actions for the current entity form.
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    // Get the basic actions from the base class.
    $actions = parent::actions($form, $form_state);

    // Change the submit button text.
    $actions['submit']['#value'] = $this->t('Save');

    // Return the result.
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Add code here to validate your config entity's form elements.
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   *
   * Saves the entity.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   *
   * @return int|void
   * @throws EntityMalformedException
   * @throws EntityStorageException
   */
  public function save(array $form, FormStateInterface $form_state) {
    // EntityForm provides us with the entity we're working on.
    $lead = $this->getEntity();

    $status = $lead->save();
    // Grab the URL of the new entity. We'll use it in the message.
    $url = $lead->toUrl();

    // Create an edit link.
    $edit_link = Link::fromTextAndUrl($this->t('Edit'), $url)->toString();

    if ($status == SAVED_UPDATED) {
      // If we edited an existing entity...
      $this->messenger()
        ->addMessage($this->t('Sharpspring Form %id has been updated.', ['%id' => $lead->id]));
      $this->logger('sharpspring_integration')
        ->notice('Sharpspring Form %id has been updated.', [
          '%id' => $lead->id,
          'link' => $edit_link,
        ]);
    }
    else {
      // If we created a new entity...
      $this->messenger()
        ->addMessage($this->t('Sharpspring Form %id has been added.', ['%id' => $lead->id]));
      $this->logger('sharpspring_integration')
        ->notice('Sharpspring Form %id has been added.', [
          '%id' => $lead->id,
          'link' => $edit_link,
        ]);
    }

    // Redirect the user back to the listing route after the save operation.
    $form_state->setRedirect('entity.lead.list');
  }

}
