<?php
use CRM_Rfmanalysis_ExtensionUtil as E;

/**
 * RfmCron.CronRecalculateRFM API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_rfm_cron_CronRecalculateRFM_spec(&$spec) {

}

/**
 * RfmCron.CronRecalculateRFM API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_rfm_cron_CronRecalculateRFM($params) {

    $calculateRFM = new CRM_Rfmanalysis_Cron_Calculate();
    $result = $calculateRFM->start();

    return civicrm_api3_create_success(array("Done!"));

}
