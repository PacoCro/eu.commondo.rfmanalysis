<?php

require_once 'rfmanalysis.civix.php';
use CRM_Rfmanalysis_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function rfmanalysis_civicrm_config(&$config) {
  _rfmanalysis_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function rfmanalysis_civicrm_xmlMenu(&$files) {
  _rfmanalysis_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function rfmanalysis_civicrm_install() {
  _rfmanalysis_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function rfmanalysis_civicrm_postInstall() {
  _rfmanalysis_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function rfmanalysis_civicrm_uninstall() {
  _rfmanalysis_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function rfmanalysis_civicrm_enable() {
  _rfmanalysis_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function rfmanalysis_civicrm_disable() {
  _rfmanalysis_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function rfmanalysis_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _rfmanalysis_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function rfmanalysis_civicrm_managed(&$entities) {
  _rfmanalysis_civix_civicrm_managed($entities);
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
function rfmanalysis_civicrm_caseTypes(&$caseTypes) {
  _rfmanalysis_civix_civicrm_caseTypes($caseTypes);
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
function rfmanalysis_civicrm_angularModules(&$angularModules) {
  _rfmanalysis_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function rfmanalysis_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _rfmanalysis_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function rfmanalysis_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function rfmanalysis_civicrm_navigationMenu(&$menu) {
  _rfmanalysis_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _rfmanalysis_civix_navigationMenu($menu);
} // */


/*
 * Get plugin configuration
 */
function getConfig() {

    $query_sql = "SELECT * FROM civicrm_rfm_analysis_config";

    $configTable = CRM_Core_DAO::executeQuery($query_sql);

    foreach ($configTable->fetchAll() as $item) {
        $config[$item['name']] = $item['value'];
    }

    return $config;
}

/*
 * Set config value in config database, if not already set, and return it.
 */
function setConfig($name, $value) {

    $config = getConfig();
    $configKeys = array_flip($config);

    if(in_array($name, $configKeys)){
        $return = $config[$name];
    } else {
        $query_sql = "INSERT INTO civicrm_rfm_analysis_config (name, value) VALUES ('{$name}', '{$value}')";

        $configTable = CRM_Core_DAO::executeQuery($query_sql);

        $return = $value;
    }

    return $return;
}


function rfmanalysis_civicrm_tabs(&$tabs, $contactID) {
    /*echo '<pre>';
    var_dump($tabs);
    echo '</pre>';
    die();*/

    $tabs[] = array(
        "id" => "rfm",
        "url" => CRM_Utils_System::url('civicrm/rfm-analysis/contact-tab', "contactId=$contactID"),
        "title" => "RFM Analysis",
        "weight" => "300"
    );
}