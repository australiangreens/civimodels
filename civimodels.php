<?php

require_once 'civimodels.civix.php';

use CRM_CiviModels_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function civimodels_civicrm_config(&$config): void {
  _civimodels_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function civimodels_civicrm_install(): void {
  _civimodels_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function civimodels_civicrm_enable(): void {
  _civimodels_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function civimodels_civicrm_navigationMenu(&$menu): void {
  _civimodels_civix_insert_navigation_menu($menu, 'Administer/System Settings', [
    'label' => 'CiviModels settings' ,
    'name' => 'civimodles_settings',
    'url' => 'civicrm/admin/setting/civimodels',
    'permission' => 'administer civimodels',
    'operator' => 'OR',
    'separator' => 0,
  ]);
  _civimodels_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_permission
 */
function civimodels_civicrm_permission(&$permissions): void {
  $prefix = E::ts('CiviModels extension: ');
  $permissions['administer civimodels'] = [
    'label' => $prefix . E::ts('Administer CiviModels'),
    'description' => E::ts('Manage CiviModels settings')
  ];
  $permissions['access civimodels'] = [
    'label' => $prefix . E::ts('Access CiviModels'),
    'description' => E::ts('Access CiviModels')
  ];
}

/**
 * Implements hook_civicrm_tabset().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset
 */
function civimodels_civicrm_tabset($tabsetName, &$tabs, $context): void {
  if ($tabsetName === 'civicrm/contact/view' && CRM_Core_Permission::check('access civimodels')) {
    // add a tab to the contact summary screen
    $contactId = $context['contact_id'];
    $url = CRM_Utils_System::url('civicrm/contact/view/models/', ['cid' => $contactId]);

    $tabs[] = [
      'id' => 'models_contact',
      'url' => $url,
      'title' => E::ts('Models'),
      'weight' => 1,
      'icon' => 'crm-i fa-line-chart',
    ];
  }
}
