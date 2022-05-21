<?php

/**
 * @file
 * Contains \Drupal\hours_minutes_seconds\Plugin\Field\FieldFormatter\HoursMinutesSecondsFormatter.
 */

namespace Drupal\hours_minutes_seconds\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\hours_minutes_seconds\HoursMinutesSecondsServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'hour_minutes_seconds_default_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "hour_minutes_seconds_default_formatter",
 *   label = @Translation("Hours Minutes and Seconds"),
 *   field_types = {
 *     "hour_minutes_seconds"
 *   }
 * )
 */
class HoursMinutesSecondsFormatter extends FormatterBase implements ContainerFactoryPluginInterface {


  /**
   * Constructor
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, HoursMinutesSecondsServiceInterface $hour_minutes_seconds_service) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->hour_minutes_seconds_service = $hour_minutes_seconds_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('hours_minutes_seconds.hour_minutes_seconds')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'format' => 'h:mm',
      'leading_zero' => TRUE,
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $settings = $this->getSettings();
    $elements['format'] = array(
      '#type' => 'select',
      '#title' => t('Display format'),
      '#options' => $this->hour_minutes_seconds_service->format_options(),
      '#description' => t('The display format used for this field'),
      '#default_value' => $settings['format'],
      '#required' => TRUE,
    );
    $elements['leading_zero'] = array(
      '#type' => 'checkbox',
      '#title' => t('Leading zero'),
      '#description' => t('Leading zero values will be displayed when this option is checked'),
      '#default_value' => $settings['leading_zero'],
    );
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $settings = $this->getSettings();

    $summary[] = t('Format: @format', array('@format' => $settings['format']));
    $summary[] = t('Leading zero: @zero', array('@zero' => ($settings['leading_zero'] ? t('On') : t('Off'))));

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = array();

    foreach ($items as $delta => $item) {
      $element[$delta] = array(
        '#theme' => 'hour_minutes_seconds',
        '#value' => $item->value,
        '#format' => $this->getSetting('format'),
        '#leading_zero' => $this->getSetting('leading_zero'),
      );
    }

    return $element;
  }
}
