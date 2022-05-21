<?php

/**
 * @file
 * Contains \Drupal\hours_minutes_seconds\Element\Hmsfield.
 */

namespace Drupal\hours_minutes_seconds\Element;

use Drupal\Core\Render\Element;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a one-line text field form element.
 *
 * @FormElement("hour_minutes_seconds")
 */
class HoursMinutesSeconds extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {

    $class = get_class($this);
    return array(
      '#input' => TRUE,
      '#size' => 8,
      '#maxlength' => 16,
      '#default_value' => FALSE,
      '#format' => 'h:mm:ss',
      '#placeholder' => 'h:mm:ss',
      '#autocomplete_route_name' => FALSE,
      '#process' => array(
        array($class, 'processAutocomplete'),
        array($class, 'processAjaxForm'),
        array($class, 'processPattern'),
        array($class, 'processGroup'),
      ),
      '#pre_render' => array(
        array($class, 'preRenderHoursMinutesSeconds'),
      ),
      '#element_validate' => array(
        array($class, 'validateHoursMinutesSeconds'),
      ),
      '#theme' => 'input__textfield',
      '#theme_wrappers' => array('form_element'),

    );
  }

  /**
   * Form element validation handler for #type 'hour_minutes_seconds'.
   *
   * Note that #required is validated by _form_validate() already.
   */
  public static function validateHoursMinutesSeconds(&$element, FormStateInterface $form_state, &$complete_form) {
    $value = trim($element['#value']);

    $form_state->setValueForElement($element, $value);
    if ($value !== '' && !\Drupal::service('hours_minutes_seconds.hour_minutes_seconds')->isValid($value, $element['#format'])) {
      $form_state->setError($element, t('Please enter a correct hour_minutes_seconds value in format %format.', array('%format' => $element['#format'])));
    } else {
      // Format given value to seconds if input is valid.
      $seconds = \Drupal::service('hours_minutes_seconds.hour_minutes_seconds')
      ->formatted_to_seconds($value, $element['#format']);
      $form_state->setValueForElement($element, $seconds);
    }
  }

  /**
   * Prepares a #type 'hour_minutes_seconds' render element for input.html.twig.
   *
   * @param array $element
   *   An associative array containing the properties of the element.
   *   Properties used: #title, #value, #description, #size, #maxlength,
   *   #placeholder, #required, #attributes.
   *
   * @return array
   *   The $element with prepared variables ready for input.html.twig.
   */
  public static function preRenderHoursMinutesSeconds($element) {

    Element::setAttributes($element, array(
      'id',
      'name',
      'value',
      'size',
      'maxlength',
      'placeholder'
    ));
    static::setAttributes($element, array('form-hour_minutes_seconds'));

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    // Get saved value from db.
    if ($input === FALSE) {
      $formatted = \Drupal::service('hours_minutes_seconds.hour_minutes_seconds')
      ->seconds_to_formatted($element['#default_value'], $element['#format']);
      return $formatted;
    }
  }
}
