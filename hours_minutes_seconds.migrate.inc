<?php

/**
 * @file
 * Support for the Migrate API.
 */

/**
 * Migrate handler for HoursMinutesSeconds fields, so we can import values to these fields.
 * You should give the integer (seconds) value to the mapping.
 */
class MigrateHmsFieldHandler extends MigrateSimpleFieldHandler {

  /**
   * This does all the work. Mainly register the field type.
   */
  public function __construct() {
    parent::__construct(array(
      'value_key' => 'value',
      'skip_empty' => FALSE,
    ));
    $this->registerTypes(array('hours_minutes_seconds'));
  }
}

/**
 * Let's tell migrate that we have implementation above.
 * Implements migrate hook_migrate_api().
 */
function hours_minutes_seconds_migrate_api() {
  $api = array(
    'api' => 2,
  );
  return $api;
}
