<?php

namespace Drupal\sharpspring_integration\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class SharpspringAddForm.
 *
 * Provides the add form for our Lead entity.
 *
 * @ingroup sharpspring_integration
 */
class SharpspringAddForm extends SharpspringFormBase {

  /**
   * Returns the actions provided by this form.
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
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Create Form config');
    return $actions;
  }
}
