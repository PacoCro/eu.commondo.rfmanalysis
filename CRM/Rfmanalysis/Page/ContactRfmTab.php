<?php
use CRM_Rfmanalysis_ExtensionUtil as E;

class CRM_Rfmanalysis_Page_ContactRfmTab extends CRM_Core_Page {

  public function run() {

    if (isset($_REQUEST['contactId'])) {

        /*
         * Get contactId from get request.
         */
        $contactId = $_REQUEST['contactId'];
        $this->assign('contactId', $contactId);


        /*
         * Get contact RFM data from database.
         */
        $query = "SELECT * from civicrm_rfm_analysis_member_data WHERE member_id = {$contactId}";
        $memberdataTable = CRM_Core_DAO::executeQuery($query);
        $contact = $memberdataTable->fetchAll();


        /*
         * Extract contact RFM values and assign them to view.
         */
        if (!$contact[0]['RFM_R']) {
            $contact[0]['RFM_R'] = "None";
        }

        if (!$contact[0]['RFM_F']) {
            $contact[0]['RFM_F'] = "None";
        }

        if (!$contact[0]['RFM_M']) {
            $contact[0]['RFM_M'] = "None";
        }

        $this->assign('rfmR', $contact[0]['RFM_R']);
        $this->assign('rfmF', $contact[0]['RFM_F']);
        $this->assign('rfmM', $contact[0]['RFM_M']);


        /*
         * Assign title value to view.
         */
        $this->assign('title', 'Contact RFM Analysis Report');


        /*
         * Add plugin stylesheet to view.
         */
        CRM_Core_Resources::singleton()->addStyleFile('eu.commondo.rfmanalysis', 'css/style.css');


        /*
         * Extract contact group value and assign it to view.
         */
        $groups = array(
            "0" => "Undefined",
            "1" => "Can't lose them",
            "2" => "At risk of loosing",
            "3" => "Loyal customers",
            "4" => "Hibernating",
            "5" => "Lost",
            "6" => "Need attention",
            "7" => "About to sleep",
            "8" => "Promising",
            "9" => "Potential loyalist",
            "10" => "New customers",
            "11" => "Champions"
        );

        if (!$groups[$contact[0]['RFM_G']]) {
            $contact[0]['RFM_G'] = 0;
        }

        $this->assign('group', $groups[$contact[0]['RFM_G']]);
        $this->assign('groupId', $contact[0]['RFM_G']);

    }

    parent::run();
  }

}
