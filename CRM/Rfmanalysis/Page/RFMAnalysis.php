<?php
use CRM_Rfmanalysis_ExtensionUtil as E;

class CRM_Rfmanalysis_Page_RFMAnalysis extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('RFM Analysis'));

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    $bao = new CRM_Rfmanalysis_Cron_Calculate();
    $bao->start();

    parent::run();
  }

}
