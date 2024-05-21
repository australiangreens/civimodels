<?php

class CRM_CiviModels_Hook {

  /**
   * This hook allows individual models to provide data for rendering
   * inside the CiviModels display tab on a contact record
   *
   * @param array $data with the following keys
   *
   * - model_name (which extension is using the hook)
   * - title (title text for display inside the
   * - introduction
   * - template
   * - model_data
   *
   */
  public static function displayCiviModelData($contact_id, &$data) {
    $null = CRM_Utils_Hook::$_nullObject;
    return CRM_Utils_Hook::singleton()->invoke([
      'contact_id', 'data'
    ],
      $contact_id,
      $data,
      $null,
      $null,
      $null,
      $null,
      'civimodels_displayCiviModelData');
  }
}

