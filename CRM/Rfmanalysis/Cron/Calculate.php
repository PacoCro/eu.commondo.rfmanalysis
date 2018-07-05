<?php

use CRM_Rfmanalysis_ExtensionUtil as E;

class CRM_Rfmanalysis_Cron_Calculate extends CRM_Core_Page
{

    private $buckets = array(
        'r' => array([0,0,0,0,0,0]),
        'f' => array([0,0,0,0,0,0]),
        'm' => array([0,0,0,0,0,0])
    );
    private $memberCount;
    private $cf_coc;
    private $cf_dolc;
    private $cf_tlc;


    /*
     * Main function to control the flow of the process.
     *
     * First, function calculates RFM bucket boundaries.
     * Second, when buckets are calculated, function selects all contact for RFM analysis.
     * Then, R, F and M values are calculated for each customer and saved to DB.
     */
    public function start()
    {
        $this->findCustomFields();

        $this->memberCount = $this->getMemberCount();

        $this->calculateBuckets();
        $this->recalculateMemberRFM();

    }


    /*
     * Function to calculate bucked boundaries 1-6
     */
    private function calculateBuckets()
    {

        $this->calculateRBucket();
        $this->calculateFBucket();
        $this->calculateMBucket();

        return $this->buckets;
    }


    /*
     * Function to calculate boundaries for bucket R (Recency)
     */
    private function calculateRBucket()
    {

        $dateOfLastContribution = $this->cf_dolc;

        $bucketLimit = round($this->memberCount/5);

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'sort' => "{$dateOfLastContribution} desc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result5 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'offset' => $bucketLimit, 'sort' => "{$dateOfLastContribution} desc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result4 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'offset' => $bucketLimit*2, 'sort' => "{$dateOfLastContribution} desc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result3 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'offset' => $bucketLimit*3, 'sort' => "{$dateOfLastContribution} desc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result2 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'offset' => $bucketLimit*4, 'sort' => "{$dateOfLastContribution} desc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $dateOfLastContribution,
            'options' => array('limit' => 1, 'sort' => "{$dateOfLastContribution} asc"),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));


        $first = $result1['values'][0][$dateOfLastContribution];

        $second = $result2['values'][0][$dateOfLastContribution];
        $third = $result3['values'][0][$dateOfLastContribution];
        $fourth = $result4['values'][0][$dateOfLastContribution];
        $fifth = $result5['values'][0][$dateOfLastContribution];

        $sixth = $result6['values'][0][$dateOfLastContribution];

        $this->buckets['r'] = array(
            $sixth,
            $fifth,
            $fourth,
            $third,
            $second,
            $first
        );
    }


    /*
    * Function to calculate boundaries for bucket F (Frequency)
    */
    private function calculateFBucket()
    {

        $dateOfLastContribution = $this->cf_dolc;
        $countOfContributions = $this->cf_coc;

        $bucketLimit = round($this->memberCount/5);

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            'options' => array('sort' => "{$countOfContributions} asc", 'limit' => 1),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $result2 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$countOfContributions} desc", 'limit' => 1, 'offset' => $bucketLimit*4),
        ));

        $result3 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$countOfContributions} desc", 'limit' => 1, 'offset' => $bucketLimit*3),
        ));

        $result4 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$countOfContributions} desc", 'limit' => 1, 'offset' => $bucketLimit*2),
        ));

        $result5 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$countOfContributions} desc", 'limit' => 1, 'offset' => $bucketLimit),
        ));

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $countOfContributions,
            'options' => array('sort' => "{$countOfContributions} desc", 'limit' => 1),
            $dateOfLastContribution => array('IS NOT NULL' => 1),
        ));

        $min = $result1['values'][0][$countOfContributions];

        $second = $result2['values'][0][$countOfContributions];
        $third = $result3['values'][0][$countOfContributions];
        $fourth = $result4['values'][0][$countOfContributions];
        $fifth = $result5['values'][0][$countOfContributions];

        $max = $result6['values'][0][$countOfContributions];


        $this->buckets['f'] = array(
            $max,
            $fifth,
            $fourth,
            $third,
            $second,
            $min
        );
    }


    /*
    *  Function to calculate boundaries for bucket M (Monetary Value)
    */
    private function calculateMBucket()
    {

        $totalContributions = $this->cf_tlc;
        $dateOfLastContribution = $this->cf_dolc;

        $bucketLimit = round($this->memberCount/5);

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$totalContributions} asc", 'limit' => 1),
        ));

        $result2 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$totalContributions} desc", 'limit' => 1, 'offset' => $bucketLimit*4),
        ));

        $result3 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => $totalContributions, 'limit' => 1, 'offset' => $bucketLimit*3),
        ));

        $result4 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$totalContributions} desc", 'limit' => 1, 'offset' => $bucketLimit*2),
        ));

        $result5 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$totalContributions} desc", 'limit' => 1, 'offset' => $bucketLimit),
        ));

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => $totalContributions,
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('sort' => "{$totalContributions} desc", 'limit' => 1),
        ));

        $min = $result1['values'][0][$totalContributions];
        $second = $result2['values'][0][$totalContributions];
        $third = $result3['values'][0][$totalContributions];
        $fourth = $result4['values'][0][$totalContributions];
        $fifth = $result5['values'][0][$totalContributions];
        $max = $result6['values'][0][$totalContributions];

        $this->buckets['m'] = array(
            $max,
            $fifth,
            $fourth,
            $third,
            $second,
            $min
        );

        //echo '<pre>'; var_dump($this->buckets); echo '</pre>'; die();
    }


    /*
    * Function to calculate RFM scores for each contact.
    */
    private function recalculateMemberRFM()
    {

        $totalContributions = $this->cf_tlc;
        $dateOfLastContribution = $this->cf_dolc;
        $countOfContributions = $this->cf_coc;

    $count = $this->memberCount;
    $offset = 0;
    $limit = 10;

    while ($offset < $count) {

        $contacts = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "{$totalContributions},{$dateOfLastContribution},{$countOfContributions}",
            $dateOfLastContribution => array('IS NOT NULL' => 1),
            'options' => array('limit' => $limit, 'offset' => $offset, 'sort' => 'contact_id asc'),
        ));

        foreach ($contacts['values'] as $contact) {

            $contactId = $contact['contact_id'];

            $query = "SELECT * FROM civicrm_rfm_analysis_member_data WHERE member_id = {$contactId}";
            $result = CRM_Core_DAO::executeQuery($query);

            $contactFound = $result->fetch();

            //dummy values
            $r = $this->calculateR($contact);
            $f = $this->calculateF($contact);
            $m = $this->calculateM($contact);
            $g = $this->calculateGroup($r, $f, $m);


            if ($contactFound) {

                $this->editContactRFM($contactId, $r, $f, $m, $g);

            } else {

                $this->createNewContactRFM($contactId, $r, $f, $m, $g);

            }


        }

        $offset += $limit;
    }

    }


    /*
     * Function to get count of all contacts covered by RFM anaylsis.
     * The ones with at least one contribution/donation.
     */
    private function getMemberCount ()
    {
        $result = civicrm_api3('Contact', 'getcount', array(
            'sequential' => 1,
            $this->cf_dolc => array('IS NOT NULL' => 1),
        ));

        return $result;
    }


    /*
     * Add new RFM values for an existing contact to the database.
     */
    private function editContactRFM($contactId, $r, $f, $m, $g)
    {

        $query = "UPDATE civicrm_rfm_analysis_member_data SET RFM_R = {$r}, RFM_F = {$f}, RFM_M = {$m}, RFM_G = {$g} WHERE member_id = {$contactId}";
        $result = CRM_Core_DAO::executeQuery($query);

        return;

    }


    /*
     * Add new RFM values for an none-existing contact to the database.
     */
    private function createNewContactRFM($contactId, $r, $f, $m, $g)
    {

        $query = "INSERT INTO civicrm_rfm_analysis_member_data (member_id, RFM_R, RFM_F, RFM_M, RFM_G) VALUES ({$contactId}, {$r}, {$f}, {$m}, {$g})";
        $result = CRM_Core_DAO::executeQuery($query);

        return;
    }


    /*
     * Function to calculate Recency (R) value for a contact.
     */
    private function calculateR($contact)
    {

        $dateOfLastContribution = $this->cf_dolc;

        $contact[$dateOfLastContribution] = strtotime($contact[$dateOfLastContribution]);

        $value = 6;
        foreach($this->buckets['r'] as $boundary) {

            $boundary = strtotime($boundary);

            if (($contact[$dateOfLastContribution] > $boundary) or ($value === 1 and $contact[$dateOfLastContribution] >= $boundary)) {
                break;
            }

            $value = $value-1;
        }

        return $value;
    }


    /*
     * Function to calculate Frequency (F) value for a contact.
     */
    private function calculateF($contact)
    {

        $countOfContributions = $this->cf_coc;

        $value = 6;
        foreach($this->buckets['f'] as $boundary) {

            if (($contact[$countOfContributions] > $boundary) or ($value === 1 and $contact[$countOfContributions] >= $boundary)) {
                break;
            }

            $value = $value-1;
        }

        return $value;
    }


    /*
     * Function to calculate Monetary (M) value for a contact.
     */
    private function calculateM($contact)
    {
        $totalContributions = $this->cf_tlc;

        $value = 6;
        foreach($this->buckets['m'] as $boundary) {

            if (($contact[$totalContributions] > $boundary) or ($value === 1 and $contact[$totalContributions] >= $boundary)) {
                break;
            }

            $value = $value-1;
        }

        return $value;

    }


    /*
     * Calculate customer RFM group.
     */
    private function calculateGroup($r, $f, $m){

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

        $fm = ($f + $m)/2;

        if ($r > 4 and $fm > 4) {
            $group = 11;
        } else if ($r < 2 AND $fm > 4) {
            $group = 10;
        } else if (($r < 4 AND $r > 1) AND $fm > 3) {
            $group = 9;
        } else if ($fm > 3 and $fm < 5 AND $r < 2) {
            $group = 8;
        } else if ($fm > 2 and $fm < 4 AND $r < 3) {
            $group = 7;
        } else if (($fm > 2 AND $fm < 4) AND ($r > 2 AND $r < 4)) {
            $group = 6;
        } else if (($fm < 2 AND ($r < 3)) OR ($fm < 3 and $r < 2)) {
            $group = 5;
        } else if ($r > 1 and $r < 3 and $fm > 1 and $fm < 3) {
            $group = 4;
        } else if (($fm > 2 and $fm < 5 and $r > 3) or ($fm > 4 and $r < 5 and $r > 3)) {
            $group = 3;
        } else if (($fm < 3 and $r < 5 and $r > 2) or ($fm > 1 and $fm < 3 and $r > 4)) {
            $group = 2;
        } else if ($r > 4 AND $fm < 2) {
            $group = 1;
        } else {
            $group = 0;
        }

        return $group;
    }


    private function findCustomFields() {

        // Total number of contribution custom field id
        $cf_coc = civicrm_api3('CustomField', 'get', array(
            'sequential' => 1,
            'return' => "id",
            'name' => "Count_Of_Contributions",
        ));

        $this->cf_coc = "custom_" . $cf_coc['values'][0]['id'];

        // Date of last contribution custom field id
        $cf_dolc = civicrm_api3('CustomField', 'get', array(
            'sequential' => 1,
            'return' => "id",
            'name' => "Date_Of_Last_Contribution",
        ));

        $this->cf_dolc = "custom_" . $cf_dolc['values'][0]['id'];

        // Total lifetime contribution custom field id
        $cf_tlc = civicrm_api3('CustomField', 'get', array(
            'sequential' => 1,
            'return' => "id",
            'name' => "Total_Lifetime_Contributions",
        ));

        $this->cf_tlc = "custom_" . $cf_tlc['values'][0]['id'];
    }
}