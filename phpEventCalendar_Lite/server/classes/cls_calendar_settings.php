<?php
class C_Calendar_Settings {
    public $language;
    public $time_zone;
    public $default_view;
    public $shortdate_format;
    public $longdate_format;
    public $timeformat;
    public $start_day;
    public function __construct() {
    }
    public function loadCalendarSettings($userID) {
        $sql = "SELECT * FROM  `pec_settings` WHERE `user_id` = ?";
        $allCals = $this->dbObj->db_query($sql, [$userID]);
        $result = NULL;
        if ($this->dbObj->num_rows($allCals) > 0) {
            while ($res = $this->dbObj->fetch_array_assoc($allCals)) {
                $result = $res;
            }
        } else return NULL;
        return $result;
    }
    public function loadPublicCalendarSettings() {
        $params['shortdate_format'] = 'MM/DD/YYYY';
        $params['longdate_format'] = 'dddd, DD MMMM YYYY';
        $params['timeformat'] = 'core';
        $params['custom_view'] = '';
        $params['start_day'] = '0';
        $params['default_view'] = 'month';
        $params['wysiwyg'] = '0';
        $params['staff_mode'] = '0';
        $params['calendar_mode'] = 'vertical';
        $params['timeline_day_width'] = '360';
        $params['timeline_row_height'] = '28';
        $params['timeline_show_hours'] = '1';
        $params['timeline_mode'] = 'horizontal';
        $params['week_cal_timeslot_min'] = '30';
        $params['timeslot_height'] = '20';
        $params['week_cal_start_time'] = '00:00';
        $params['week_cal_end_time'] = '23:00';
        $params['week_cal_show_hours'] = '1';
        $params['event_tooltip'] = '1';
        $params['left_side_visible'] = '1';
        $params['language'] = 'English';
        $params['time_zone'] = '-12';
        $params['privacy'] = 'public';
        $params['email_server'] = 'PHPMailer';
        return $params;
    }
    public function saveCalendarSettings($params) {

        $clean_params = array_map(array($this, 'sanitize'), $params);

        return ($this->db->AutoExecute('pec_settings', $clean_params, 'INSERT') && isset($this->db->_connectionID->insert_id)) ? $this->db->_connectionID->insert_id : $this->db->Insert_ID();
    }

    public function updateCalendarSettings($params, $id) {

        $clean_params = array_map(array($this, 'sanitize'), $params);

        return ($this->db->AutoExecute('pec_settings', $clean_params, 'UPDATE', "id=$id")) ? $id : $this->db->Insert_ID();
    }

    /**
     *  preventing XSS by escape all characters
     */

    private function sanitize($s) {
        return htmlspecialchars($s);
    }
}