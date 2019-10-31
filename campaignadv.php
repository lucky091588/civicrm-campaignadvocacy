<?php

require_once 'campaignadv.civix.php';
use CRM_Campaignadv_ExtensionUtil as E;


/**
 * Implements of hook_civicrm_custom().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_custom
 *
 */
function campaignadv_civicrm_custom($op, $groupID, $entityID, &$params) {
  // We could do an api call to test whether $groupID is the custom group named
  // 'electoral_districts', but this regex is faster (if more brittle WRT civicrm
  // upgrades), and I'm opting for speed because this hook fires with every
  // change of any custom field value.
  if (preg_match('/civicrm_value_electoral_districts_[0-9]+/', $params[0]['table_name'])) {
    // We've updated the "electoral" custom fields, so  Update 'official/const'
    // relationshpis for the given contact accordingly.
    $result = civicrm_api3('contact', 'updateelectoralrelationships', array('contact_id' => $entityID));
  }
}

/**
 * Implements of hook_civicrm_pageRun().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pageRun
 *
 */
function campaignadv_civicrm_pageRun(&$page) {
  $pageName = $page->getVar('_name');
  if (!empty($page->angular)) {
    $f = '_' . __FUNCTION__ . '_Angular_' . str_replace('\\', '_', $pageName);
  }
  else {
    $f = '_' . __FUNCTION__ . '_' . $pageName;
  }

  if (function_exists($f)) {
    $f($page);
  }
  _campaignadv_periodicChecks();
}

/**
 * hook_civicrm_pageRun handler for civicrm core extensions admin page.
 * @param type $page
 */
function _campaignadv_civicrm_pageRun_CRM_Admin_Page_Extensions(&$page) {
  _campaignadv_prereqCheck();
}

/**
 * hook_civicrm_pageRun handler for "extensionsui" extensions admin page.
 * @param type $page
 */
function _campaignadv_civicrm_pageRun_Angular_Civi_Angular_Page_Main(&$page) {
  _campaignadv_prereqCheck();
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function campaignadv_civicrm_config(&$config) {
  _campaignadv_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function campaignadv_civicrm_xmlMenu(&$files) {
  _campaignadv_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function campaignadv_civicrm_install() {
  _campaignadv_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function campaignadv_civicrm_postInstall() {
  _campaignadv_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function campaignadv_civicrm_uninstall() {
  _campaignadv_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function campaignadv_civicrm_enable() {
  _campaignadv_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function campaignadv_civicrm_disable() {
  _campaignadv_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function campaignadv_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _campaignadv_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function campaignadv_civicrm_managed(&$entities) {
  _campaignadv_civix_civicrm_managed($entities);
  foreach ($entities as &$e) {
    if (empty($e['params']['version'])) {
      $e['params']['version'] = '3';
    }
  }

}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function campaignadv_civicrm_caseTypes(&$caseTypes) {
  _campaignadv_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function campaignadv_civicrm_angularModules(&$angularModules) {
  _campaignadv_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function campaignadv_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _campaignadv_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function campaignadv_civicrm_entityTypes(&$entityTypes) {
  _campaignadv_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function campaignadv_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function campaignadv_civicrm_navigationMenu(&$menu) {
  _campaignadv_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _campaignadv_civix_navigationMenu($menu);
} // */


function _campaignadv_prereqCheck() {
  $unmet = CRM_Campaignadv_Upgrader::checkExtensionDependencies();
  CRM_Campaignadv_Upgrader::displayDependencyErrors($unmet);
}

function _campaignadv_periodicChecks() {
  $session = CRM_Core_Session::singleton();
  if (
    !CRM_Core_Permission::check('administer CiviCRM')
    || !$session->timer('check_CRM_Campaignadv_Depends', CRM_Utils_Check::CHECK_TIMER)
  ) {
    return;
  }

  _campaignadv_prereqCheck();
}

