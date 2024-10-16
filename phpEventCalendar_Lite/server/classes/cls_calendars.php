<?php
class C_Calendar extends C_Calendar_Settings {
    public $id;
    public $type;
    public $user_id;
    public $name;
    public $description;
    public $color;
    public $admin_id;
    public $status;
    public $show_in_list;
    public $public;
    public $reminder_message_email;
    public $reminder_message_popup;
    public $access_key;
    public $created_on;
    public $updated_on;
    protected $errorNo;
    protected $errMsg = false;
    protected $errMsgList = array(0 => 'Required params are missing', 1 => 'DB Error', 2 => 'Method Not Found');
    public $db;
    public $dbObj;
    public $allCalendars;
    public $calendarProperties;
    public function __construct($name, $description = '', $color = '#3a87ad', $type = 'user', $status = 'on', $show_in_list = '1', $public = 1, $reminder_message_email = '', $reminder_message_popup = '', $access_key = '', $created_on = '', $updated_on = '') {
        $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $this->db = $this->dbObj->db;
        $userID = @$_SESSION['userData']['id'];
        if ($name == 'LOAD_MY_CALENDARS') {
            $this->allCalendars = $this->loadAllCalendars($userID);
            if ($_SESSION['userData']['active_calendar_id'][0] == '') {
                C_User::setActiveCalendar($_SESSION['userData']['id'], array($this->allCalendars[0]['id']));
                $_SESSION['userData']['active_calendar_id'] = array($this->allCalendars[0]['id']);
            }
            if ($this->allCalendars == NULL) {
                $params['name'] = 'Default Calendar';
                $params['description'] = 'This is a default calendar';
                $params['color'] = '#3a87ad';
                $params['type'] = 'user';
                $params['status'] = 'on';
                $params['show_in_list'] = '1';
                $params['public'] = 0;
                $params['reminder_message_email'] = '';
                $params['reminder_message_popup'] = '';
                $params['access_key'] = '';
                $params['created_on'] = date('Y-m-d');
                $params['updated_on'] = '';
                $this->id = $this->saveCalendar($params);
                $params['id'] = $this->id;
                C_User::setActiveCalendar($_SESSION['userData']['id'], array($this->id));
                $_SESSION['userData']['active_calendar_id'] = array($this->id);
                $this->allCalendars = $this->loadAllCalendars($userID);
            }
            $this->calendarProperties = $this->loadCalendarSettings($userID);
            if ($this->calendarProperties == NULL) {
                $params['user_id'] = $userID;
                $params['language'] = 'English';
                $params['time_zone'] = '-12';
                $params['default_view'] = 'month';
                $params['shortdate_format'] = 'MM/DD/YYYY';
                $params['longdate_format'] = 'dddd, DD MMMM YYYY';
                $params['timeformat'] = 'core';
                $params['start_day'] = 'Saturday';
                $params['email_server'] = 'PHPMailer';
                $this->id = $this->saveCalendarSettings($params);
                $params['id'] = $this->id;
                $this->calendarProperties = $this->loadCalendarSettings($userID);
                $_SESSION['calendarData']['properties'] = $this->calendarProperties;
            }
        } else if ($name == 'LOAD_CALENDAR_PROPERTIES') {
            $this->loadCalendarSettings($userID);
        } else if ($name == 'UPDATE_CALENDAR_PROPERTIES') {
            $params = $_POST;
            $this->calendarProperties = $this->loadCalendarSettings($userID);
            $_SESSION['calendarData']['properties'] = $this->calendarProperties;
            $id = $_SESSION['calendarData']['properties']['id'];
            $settingsID = $this->updateCalendarSettings($params, $id);
            $this->calendarProperties = $this->loadCalendarSettings($userID);
            $_SESSION['calendarData']['properties'] = $this->calendarProperties;
        } else if ($name == 'LOAD_PUBLIC_CALENDARS') {
            $this->calendarProperties = $this->loadPublicCalendarSettings();
            $this->allCalendars = $this->loadPublicCalendars();
        } else {
            if ($name == '') {
                $this->errorNo = 0;
                $this->errMsg = $this->errMsgList[$this->errorNo];
                return false;
            } else $this->errMsg = false;
            $this->name = $name;
            $this->description = $description;
            $this->color = $color;
            $this->type = $type;
            $this->status = $status;
            $this->show_in_list = $show_in_list;
            $this->public = $public;
            $this->reminder_message_email = $reminder_message_email;
            $this->reminder_message_popup = $reminder_message_popup;
            $this->access_key = $access_key;
            $this->created_on = date('Y-m-d');
            $this->updated_on = '';
        }
        return true;
    }
    public function loadAllCalendars($userID) {
        $sql = "SELECT * FROM  `pec_calendars` WHERE `user_id` = ?";
        $allCals = $this->dbObj->db_query($sql, [$userID]);
        $result = NULL;
        if ($this->dbObj->num_rows($allCals) > 0) {
            while ($res = $this->dbObj->fetch_array_assoc($allCals)) {
                $result[] = $res;
            }
        } else return NULL;
        return $result;
    }
    public function loadPublicCalendars() {
        $sql = "SELECT * FROM  `pec_calendars` WHERE `public` = 1";
        $allCals = $this->dbObj->db_query($sql);
        $result = NULL;
        if ($this->dbObj->num_rows($allCals) > 0) {
            while ($res = $this->dbObj->fetch_array_assoc($allCals)) {
                $result[] = $res;
            }
        } else return NULL;
        return $result;
    }
    public static function getFirstCalendarID($userID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $userID = $_SESSION['userData']['id'];
        $sql = "SELECT * FROM  `pec_calendars` WHERE `user_id` = ? LIMIT 0,1";
        $allCals = $dbObj->db_query($sql, [$userID]);
        $result = NULL;
        if ($dbObj->num_rows($allCals) > 0) {
            while ($res = $dbObj->fetch_array_assoc($allCals)) {
                $result[] = $res;
            }
        } else return false;
        $_SESSION['userData']['active_calendar_id'] = array($result[0]['id']);
        return $result[0]['id'];
    }
    public static function loadSingleCalendarData($calID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $userID = $_SESSION['userData']['id'];
        $sql = "SELECT * FROM  `pec_calendars` WHERE `id` IN (?) AND `user_id` = ?";
        $allCals = $dbObj->db_query($sql, [$calID, $userID]);
        $result = NULL;
        if ($dbObj->num_rows($allCals) > 0) {
            while ($res = $dbObj->fetch_array_assoc($allCals)) {
                $result[$res['id']] = $res['color'];
            }
        } else return NULL;
        return $result;
    }
    public function saveCalendar($params = array()) {
        $params['user_id'] = $_SESSION['userData']['id'];

        $clean_params = array_map(array($this, 'sanitize'), $params);

        return ($this->db->AutoExecute('pec_calendars', $clean_params, 'INSERT') && isset($this->db->_connectionID->insert_id)) ? $this->db->_connectionID->insert_id : $this->db->Insert_ID();
    }
    public function editCalendar($params = array(), $id=0) {

        $clean_params = array_map(array($this, 'sanitize'), $params);

        return ($this->db->AutoExecute('pec_calendars', $clean_params, 'UPDATE', "id=$id")) ? $id : false;
    }

    /**
     *  preventing XSS by escape all characters
     */

    private function sanitize($s) {
        return htmlspecialchars($s);
    }

    public static function setCalToPrivate($calID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $sql = "UPDATE `pec_calendars` SET `public`='0' WHERE `id`=?";
        $isUpdate = $dbObj->db_query($sql,[$calID]);
    }
    public static function setCalToPublic($calID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $sql = "UPDATE `pec_calendars` SET `public`='1' WHERE `id`=?";
        $isUpdate = $dbObj->db_query($sql, [$calID]);
    }
    public static function sendPublicCal($link, $sendTo, $message) {
        $publicCalendarEmail = '';
        require_once (SERVER_HTML_DIR . 'emails/public-calendar-email.html.php');
        $mail = C_Core::sendEmail($sendTo, 'FullCalendar: Someone has sent you a calendar', $publicCalendarEmail);
        if ($mail != 'sent') {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail;
        } else {
            echo 'Email Sent To: ' . $sendTo . '<br />';
        }
    }
    public static function updateCalendar($params = array(), $id=0) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;

        $clean_params = array_map(array($this, 'sanitize'), $params);
        
        return ($dbObj->db->AutoExecute('pec_calendars', $clean_params, 'UPDATE', "id=$id") && isset($dbObj->db->_connectionID->insert_id)) ? $dbObj->db->_connectionID->insert_id : $dbObj->db->Insert_ID();
    }
    public static function getWeeks($timestamp) {
        $maxday = date("t", $timestamp);
        $thismonth = getdate($timestamp);
        $timeStamp = mktime(0, 0, 0, $thismonth['mon'], 1, $thismonth['year']);
        $startday = date('w', $timeStamp);
        $day = $thismonth['mday'];
        $weeks = 0;
        $week_num = 0;
        for ($i = 0;$i < ($maxday + $startday);$i++) {
            if (($i % 7) == 0) {
                $weeks++;
            }
            if ($day == ($i - $startday + 1)) {
                $week_num = $weeks;
            }
        }
        return $week_num;
    }
    public static function exportCalendar($calID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $userID = $_SESSION['userData']['id'];
        $sql = "SELECT * FROM  `pec_calendars` WHERE `id` IN (?) AND `user_id` = ?";
        $allCals = $dbObj->db_query($sql, [$calID, $userID]);
        $sql2 = "SELECT * FROM  `pec_settings` WHERE `user_id` = ?";
        $setCals = $dbObj->db_query($sql2, [$userID]);
        $sql3 = "SELECT * FROM  `pec_events` WHERE `cal_id` = ?";
        $events = $dbObj->db_query($sql3, [$calID]);
        $resultStart = NULL;
        $calName = '';
        $resultStart = "BEGIN:VCALENDAR
PRODID:-//PEC Inc//PHP Event Calendar//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH";
        if ($dbObj->num_rows($allCals) > 0) {
            while ($res = $dbObj->fetch_array_assoc($allCals)) {
                $calName = $res['name'];
                $resultStart.= "X-WR-CALNAME:" . $calName;
            }
        } else $calName = null;
        $tZone = "0000";
        if ($dbObj->num_rows($setCals) > 0) {
            while ($set = $dbObj->fetch_array_assoc($setCals)) {
                $tZone = ($set['time_zone'] > 0) ? '+' . $set['time_zone'] . "00" : $set['time_zone'] . "00";
                $timezone = preg_replace('/[^0-9]/', '', $tZone) * 36;
                $timezone_name = timezone_name_from_abbr(null, $timezone, true);
            }
        } else {
            $tZone = null;
            $timezone_name = null;
        }
        $resultStart.= "
X-WR-TIMEZONE:" . $timezone_name . "
BEGIN:VTIMEZONE
TZID:" . $timezone_name . "
X-LIC-LOCATION:" . $timezone_name . "
BEGIN:STANDARD
TZOFFSETFROM:" . $tZone . "
TZOFFSETTO:" . $tZone . "
TZNAME:" . $timezone_name . "
DTSTART:19700101T000000
END:STANDARD
END:VTIMEZONE";
        $eventDetails = null;
        if ($dbObj->num_rows($events) > 0) {
            while ($event = $dbObj->fetch_array_assoc($events)) {
                $startDate = str_replace("-", "", $event['start_date']);
                $startTime = str_replace(":", "", $event['start_time']);
                $endDate = str_replace("-", "", $event['end_date']);
                $endTime = str_replace(":", "", $event['end_time']);
                if ($startDate > $endDate) {
                    $endDate = $startDate;
                }
                if ($event['allDay'] == 'on') {
                    $start = ';VALUE=DATE:' . $startDate;
                    $end = ';VALUE=DATE:' . $endDate;
                } else {
                    $start = ";TZID=" . $timezone_name . ":" . $startDate . "T" . $startTime . "00";
                    $end = ";TZID=" . $timezone_name . ":" . $endDate . "T" . $endTime . "00";
                }
                if ($event['repeat_type'] == 'none') {
                    $repeatType = null;
                } else $repeatType = "
RRULE:FREQ=" . $event['repeat_type'];
                if ($event['repeat_end_after'] > 0) {
                    $count = ";COUNT=" . $event['repeat_end_after'];
                } else $count = null;
                if ($event['repeat_end_on'] == NULL || $event['repeat_end_on'] == '0000-01-01') {
                    $until = null;
                } else {
                    $untilDate = str_replace("-", "", $event['repeat_end_on']);
                    $until = ";UNTIL=" . $untilDate . "T" . $endTime . "00";
                }
                if ($event['repeat_by'] == 'repeat_by_day_of_the_month' && $event['repeat_type'] == 'monthly') {
                    $day = date('d', $event['start_timestamp']);
                    $repeatBy = ";BYMONTHDAY=" . $day;
                } else if ($event['repeat_by'] == 'repeat_by_day_of_the_week' && $event['repeat_type'] == 'monthly') {
                    $eventDay = date('N', $event['start_timestamp']);
                    $firstDay = (new DateTime($startDate));
                    $firstDay = $firstDay->modify('first day of this month')->format('N');
                    $weekDay = C_Calendar::getWeeks($event['start_timestamp']);
                    $weekDay = ($firstDay > $eventDay) ? $weekDay - 1 : $weekDay;
                    $day = substr(date('D', $event['start_timestamp']), 0, 2);
                    $repeatBy = ";BYDAY=" . $weekDay . $day;
                } else $repeatBy = null;
                if ($event['repeat_type'] == 'everyMWFDay') {
                    $mwf = ";BYDAY=MO,WE,FR";
                    $repeatType = "
RRULE:FREQ=WEEKLY";
                } else if ($event['repeat_type'] == 'everyWeekDay') {
                    $mwf = ";BYDAY=MO,TU,WE,TH,FR";
                    $repeatType = "
RRULE:FREQ=WEEKLY";
                } else if ($event['repeat_type'] == 'everyTTDay') {
                    $mwf = ";BYDAY=TU,TH";
                    $repeatType = "
RRULE:FREQ=WEEKLY";
                } else $mwf = null;
                if ($event['repeat_type'] == 'weekly') {
                    $byDay = "";
                    $byDay.= ($event['repeat_on_sun'] == 1) ? ",SU" : "";
                    $byDay.= ($event['repeat_on_mon'] == 1) ? ",MO" : "";
                    $byDay.= ($event['repeat_on_tue'] == 1) ? ",TU" : "";
                    $byDay.= ($event['repeat_on_wed'] == 1) ? ",WE" : "";
                    $byDay.= ($event['repeat_on_thu'] == 1) ? ",TH" : "";
                    $byDay.= ($event['repeat_on_fri'] == 1) ? ",FR" : "";
                    $byDay.= ($event['repeat_on_sat'] == 1) ? ",SA" : "";
                    $byDay = ";BYDAY=" . substr($byDay, 1);
                } else $byDay = null;
                if ($event['repeat_interval'] > 0) {
                    $interval = ";INTERVAL=" . $event['repeat_interval'];
                } else $interval = null;
                $thisDate = date('Ymd');
                $thisTime = date('His');
                $dtStamp = $thisDate . "T" . $thisTime . "Z";
                $eventDetails.= "
BEGIN:VEVENT
DTSTART" . $start . "
DTEND" . $end . $repeatType . $count . $until . $repeatBy . $interval . $mwf . $byDay . "
DTSTAMP:" . $dtStamp . "
UID:" . $event['id'] . "
CREATED:" . $event['created_on'] . "
DESCRIPTION:" . $event['description'] . "
LAST-MODIFIED:" . $event['updated_on'] . "
LOCATION:" . $event['location'] . "
SEQUENCE:0
STATUS:" . $event['invitation_response'] . "
SUMMARY:" . $event['title'] . "
TRANSP:
END:VEVENT";
            }
        }
        $resultEnd = "
END:VCALENDAR";
        $thisDate = date('Ymd');
        $thisTime = date('His');
        $calName = str_replace(" ", "-", $calName);
        $fileName = $calName . "-" . $thisDate . $thisTime . ".ics";
        $folder = "temp/" . $fileName;
        $directory = BASE_DIR . $folder;
        $file = fopen($directory, "w");
        $data = $resultStart . $eventDetails . $resultEnd;
        fwrite($file, $data);
        fclose($file);
        echo $folder;
    }
}
?><?php