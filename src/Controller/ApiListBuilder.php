<?php

namespace Drupal\sharpspring_integration\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\sharpspring_integration\Utility\DescriptionTemplateTrait;

/**
 * Provides a listing of API entities.
 *
 * @ingroup sharpspring_integration
 */
class ApiListBuilder extends ConfigEntityListBuilder {
  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'sharpspring_integration';
  }

  /**
   * Builds the header row for the entity listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   * @see \Drupal\Core\Entity\EntityListController::render()
   */
  public function buildHeader() {
    $header['accountId'] = $this->t('API account ID');
    $header['machine_name'] = $this->t('Machine Name');
    return $header + parent::buildHeader();
  }

  /**
   * Builds a row for an entity in the entity listing.
   *
   * @param EntityInterface $entity
   *   The entity for which to build the row.
   *
   * @return array
   *   A render array of the table row for displaying the entity.
   *
   * @see \Drupal\Core\Entity\EntityListController::render()
   */
  public function buildRow(EntityInterface $entity) {
    $row['accountId'] = $entity->accountId;
    $row['machine_name'] = $entity->id();

    return $row + parent::buildRow($entity);
  }

  /**
   * Adds some descriptive text to our entity list.
   *
   * Typically, there's no need to override render(). You may wish to do so,
   * however, if you want to add markup before or after the table.
   *
   * @return array
   *   Renderable array.
   */
  public function render() {
    $build = $this->description();
    $build[] = parent::render();
    return $build;
  }
}
