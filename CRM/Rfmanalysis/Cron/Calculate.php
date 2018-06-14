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


    /*
     * Main function to control the flow of the process.
     *
     * First, function calculates RFM bucket boundaries.
     * Second, when buckets are calculated, function selects all contact for RFM analysis.
     * Then, R, F and M values are calculated for each customer and saved to DB.
     */
    public function start()
    {
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

        $bucketLimit = round($this->memberCount/5);

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'sort' => "custom_47 desc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result5 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'offset' => $bucketLimit, 'sort' => "custom_47 desc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result4 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'offset' => $bucketLimit*2, 'sort' => "custom_47 desc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result3 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'offset' => $bucketLimit*3, 'sort' => "custom_47 desc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result2 = $result = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'offset' => $bucketLimit*4, 'sort' => "custom_47 desc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_47",
            'options' => array('limit' => 1, 'sort' => "custom_47 asc"),
            'custom_47' => array('IS NOT NULL' => 1),
        ));


        $first = $result1['values'][0]['custom_47'];

        $second = $result2['values'][0]['custom_47'];
        $third = $result3['values'][0]['custom_47'];
        $fourth = $result4['values'][0]['custom_47'];
        $fifth = $result5['values'][0]['custom_47'];

        $sixth = $result6['values'][0]['custom_47'];

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

        $bucketLimit = round($this->memberCount/5);

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50,custom_46",
            'options' => array('sort' => "custom_50 asc", 'limit' => 1),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $result2 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_50 desc", 'limit' => 1, 'offset' => $bucketLimit),
        ));

        $result3 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_50 desc", 'limit' => 1, 'offset' => $bucketLimit*2),
        ));

        $result4 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_50 desc", 'limit' => 1, 'offset' => $bucketLimit*3),
        ));

        $result5 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_50 desc", 'limit' => 1, 'offset' => $bucketLimit*4),
        ));

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_50,custom_46",
            'options' => array('sort' => "custom_50 desc", 'limit' => 1),
            'custom_47' => array('IS NOT NULL' => 1),
        ));

        $min = $result1['values'][0]['custom_50'];

        $second = $result2['values'][0]['custom_50'];
        $third = $result3['values'][0]['custom_50'];
        $fourth = $result4['values'][0]['custom_50'];
        $fifth = $result5['values'][0]['custom_50'];

        $max = $result6['values'][0]['custom_50'];


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

        $bucketLimit = round($this->memberCount/5);

        $result1 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 asc", 'limit' => 1),
        ));

        $result2 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 desc", 'limit' => 1, 'offset' => $bucketLimit*4),
        ));

        $result3 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 desc", 'limit' => 1, 'offset' => $bucketLimit*3),
        ));

        $result4 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 desc", 'limit' => 1, 'offset' => $bucketLimit*2),
        ));

        $result5 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 desc", 'limit' => 1, 'offset' => $bucketLimit),
        ));

        $result6 = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39",
            'custom_47' => array('IS NOT NULL' => 1),
            'options' => array('sort' => "custom_39 desc", 'limit' => 1),
        ));

        $min = $result1['values'][0]['custom_39'];
        $second = $result2['values'][0]['custom_39'];
        $third = $result3['values'][0]['custom_39'];
        $fourth = $result4['values'][0]['custom_39'];
        $fifth = $result5['values'][0]['custom_39'];
        $max = $result6['values'][0]['custom_39'];

        $this->buckets['m'] = array(
            $max,
            $fifth,
            $fourth,
            $third,
            $second,
            $min
        );
    }


    /*
    * Function to calculate RFM scores for each contact.
    */
    private function recalculateMemberRFM()
    {

    $count = $this->memberCount;
    $offset = 0;
    $limit = 10;

    while ($offset < $count) {

        $contacts = civicrm_api3('Contact', 'get', array(
            'sequential' => 1,
            'return' => "custom_39,custom_47,custom_50",
            'custom_47' => array('IS NOT NULL' => 1),
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
            'custom_47' => array('IS NOT NULL' => 1),
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

        $contact['custom_47'] = strtotime($contact['custom_47']);

        $value = 6;
        foreach($this->buckets['r'] as $boundary) {

            $boundary = strtotime($boundary);

            if (($contact['custom_47'] > $boundary) or ($value === 1 and $contact['custom_47'] >= $boundary)) {
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

        $value = 6;
        foreach($this->buckets['f'] as $boundary) {

            if (($contact['custom_50'] > $boundary) or ($value === 1 and $contact['custom_50'] >= $boundary)) {
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
        $value = 6;
        foreach($this->buckets['m'] as $boundary) {

            if (($contact['custom_39'] > $boundary) or ($value === 1 and $contact['custom_39'] >= $boundary)) {
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

        if ($r <2 AND $fm <2) {
            $group = 1;
        } else if ((($fm > 1 AND $fm < 4) AND ($r > 1 AND $r < 3) OR (($fm < 1) AND ($r > 1 and $r <3)))) {
            $group = 2;
        } else if ((($r < 2 AND $r < 5) AND ($fm < 3)) OR (($r > 5) AND (($fm < 3) AND ($fm  > 1)))) {
            $group = 3;
        } else if (($fm < 5 AND $fm >3) AND ($r > 1 AND $r <3)) {
            $group = 4;
        } else if ((($fm > 3) AND ($r < 3)) AND (($fm < 5 AND $fm > 3) AND $r <2)) {
            $group = 5;
        } else if (($r > 2 AND $r < 4) AND ($fm > 2 and $fm < 4)) {
            $group = 6;
        } else if ($fm > 3 AND ($r > 2 AND $r < 4)) {
            $group = 7;
        } else if (($r > 3 AND $r < 5) AND $fm > 4) {
            $group = 8;
        } else if ($r > 3 AND ($fm > 2 AND $fm < 5)) {
            $group = 9;
        } else if ($r > 5 AND $fm > 5) {
            $group = 10;
        } else if ($r > 5 and $fm < 2) {
            $group = 11;
        } else {
            $group = 0;
        }

        return $group;
    }
}