<?php
use CRM_CiviModels_ExtensionUtil as E;
use CRM_CiviModels_Hook as Hook;

class CRM_CiviModels_Page_ContactModels extends CRM_Core_Page {

  public function run() {
    // Retrieve contact ID for use in hook invocation
    $contact_id = CRM_Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    // $models holds each model's information to display
    $models = [];
    // Invoke custom hook to collect data to display
    Hook::displayCiviModelData($contact_id, $models);

    // Set title and assign $models to template
    CRM_Utils_System::setTitle(E::ts('Data models'));
    $this->assign('models', $models);

    parent::run();
  }

}
