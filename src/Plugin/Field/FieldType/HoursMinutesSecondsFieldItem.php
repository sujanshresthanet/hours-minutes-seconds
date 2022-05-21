<?php

/**
 * @file
 * Contains \Drupal\hours_minutes_seconds\Plugin\Field\FieldType\HoursMinutesSecondsFieldItem.
 */

namespace Drupal\hours_minutes_seconds\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationWrapper;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'hour_minutes_seconds' field type.
 *
 * @FieldType(
 *   id = "hour_minutes_seconds",
 *   label = @Translation("Hours Minutes and Seconds"),
 *   description = @Translation("Store Hours, Minutes or Seconds as an integer."),
 *   default_widget = "hour_minutes_seconds_default",
 *   default_formatter = "hour_minutes_seconds_default_formatter"
 * )
 */
class HoursMinutesSecondsFieldItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslationWrapper.
    $properties['value'] = DataDefinition::create('integer')
    ->setLabel(new TranslationWrapper('HoursMinutesSeconds integer value'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = array(
      'columns' => array(
        'value' => array(
          'type' => 'int',
          'unsigned' => FALSE,
          'not null' => FALSE,
        ),
      ),
    );
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }
}
