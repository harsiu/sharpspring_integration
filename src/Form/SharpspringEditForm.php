<?php

namespace Drupal\sharpspring_integration\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class SharpspringEditForm.
 *
 * Provides the edit form for our Lead entity.
 *
 * @ingroup sharpspring_integration
 */
class SharpspringEditForm extends SharpspringFormBase {

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
    $actions['submit']['#value'] = $this->t('Update From');
    return $actions;
  }
}
