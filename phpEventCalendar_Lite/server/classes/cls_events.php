<?php 
class C_Events extends C_Calendar {
    public $id;
    public $title;
    public $allDay;
    public $start;
    public $start_date;
    public $start_time;
    public $start_timestamp;
    public $end_date;
    public $end_time;
    public $end_timestamp;
    public $end;
    public $url;
    public $className;
    public $editable;
    public $startEditable;
    public $durationEditable;
    public $source;
    public $color;
    public $backgroundColor;
    public $borderColor;
    public $textColor;
    public $description;
    public $free_busy;
    public $location;
    public $privacy;
    public $image;
    public $reminder_type;
    public $repeat_start_date;
    public $repeat_end_on;
    public $repeat_end_after;
    public $repeat_never;
    public $repeat_by;
    public $reminder_time;
    public $reminder_time_unit;
    public $repeat;
    public $repeat_type;
    public $repeat_on_sun;
    public $repeat_on_mon;
    public $repeat_on_tue;
    public $repeat_on_wed;
    public $repeat_on_thu;
    public $repeat_on_fri;
    public $repeat_on_sat;
    public $repeat_interval;
    public $calendar_selected;
    public $errorNo;
    public $errMsg = false;
    public $errMsgList = array(0 => 'Required params are missing', 1 => 'DB Error', 2 => 'Method Not Found');
    public $db;
    public $dbObj;
    public $myEvents;
    protected $eventIndex;
    protected $loadEventsOnPageLoad = false;
    public function __construct($calendarID = 0, $title = '', $start = '', $end = '', $url = '', $allDay = '', $className = '', $editable = '', $startEditable = '', $durationEditable = '', $source = '', $color = '', $backgroundColor = '', $borderColor = '', $textColor = '', $description = '', $free_busy = 'free', $location = '', $privacy = 'public', $repeat_start_date = '', $repeat_end_on = '', $repeat_end_after = '', $repeat_never = '', $repeat_by = '', $repeat_type = 'none', $repeat_interval = '', $repeat_on_sun = 0, $repeat_on_mon = 0, $repeat_on_tue = 0, $repeat_on_wed = 0, $repeat_on_thu = 0, $repeat_on_fri = 0, $repeat_on_sat = 0, $image = '') {
        if ($title == 'LOAD_MY_EVENTS') {
            if ($calendarID == 0) $this->loadEventsOnPageLoad = true;
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
            if ($calendarID > 0) $calID = $calendarID;
            else if (isset($_SESSION['userData']['active_calendar_id'])) $calID = $_SESSION['userData']['active_calendar_id'];
            else $calID = 0;
            return $allEvents = $this->loadAllEvents($calID);
        }
        if ($title == 'LOAD_MY_EVENTS_BASED_SEARCH_KEY') {
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
            $calID = 0;
            $searchKey = $calendarID;
            return $allEvents = $this->loadAllEvents($calID, $searchKey);
        }
        if ($title == 'LOAD_PUBLIC_EVENTS') {
            if ($calendarID == 0) $this->loadEventsOnPageLoad = true;
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
            if ($calendarID > 0) $calID = $calendarID;
            else if (isset($_SESSION['userData']['active_calendar_id'])) $calID = $_SESSION['userData']['active_calendar_id'];
            else $calID = 0;
            return $allEvents = $this->loadAllPublicEvents($calID);
        }
        if ($title == 'LOAD_GUEST_EVENTS') {
            if ($calendarID == 0) $this->loadEventsOnPageLoad = true;
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
            if ($calendarID > 0) $calID = $calendarID;
            return $allEvents = $this->loadAllPublicEvents($calID);
        }
        if ($title == 'GENERAL_PURPOSE') {
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
            return NULL;
        } else {
            if ($title == '' || $start == '') {
                $this->errorNo = 0;
                $this->errMsg = $this->errMsgList[$this->errorNo];
                return false;
            } else $this->errMsg = false;
            $this->title = $title;
            $this->calendar_selected = false;
            $this->start = $start;
            $this->start_timestamp = strtotime($start);
            $this->start_date = date('Y-m-d', $this->start_timestamp);
            $this->start_time = date('H:i', $this->start_timestamp);
            $this->end = $end;
            $this->end_timestamp = strtotime($end);
            $this->end_date = date('Y-m-d', $this->end_timestamp);
            $this->end_time = date('H:i', $this->end_timestamp);
            $this->url = $url;
            $this->allDay = $allDay;
            $this->className = $className;
            $this->editable = $editable;
            $this->startEditable = $startEditable;
            $this->durationEditable = $durationEditable;
            $this->source = $source;
            $this->color = $color;
            $this->backgroundColor = $backgroundColor;
            $this->borderColor = $borderColor;
            $this->textColor = $textColor;
            $this->description = $description;
            $this->description = $description;
            $this->free_busy = $free_busy;
            $this->location = $location;
            $this->privacy = $privacy;
            $this->image = $image;
            $this->repeat_start_date = $repeat_start_date;
            $this->repeat_end_on = $repeat_end_on;
            $this->repeat_end_after = $repeat_end_after;
            $this->repeat_never = $repeat_never;
            $this->repeat_by = $repeat_by;
            $this->repeat_on_sun = $repeat_on_sun;
            $this->repeat_on_mon = $repeat_on_mon;
            $this->repeat_on_tue = $repeat_on_tue;
            $this->repeat_on_wed = $repeat_on_wed;
            $this->repeat_on_thu = $repeat_on_thu;
            $this->repeat_on_fri = $repeat_on_fri;
            $this->repeat_on_sat = $repeat_on_sat;
            $this->repeat_type = $repeat_type;
            $this->repeat_interval = $repeat_interval;
            $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
            $this->db = $this->dbObj->db;
        }
        return true;
    }
    public static function loadSingleEventData($eventID) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $sql = "SELECT * FROM  `pec_events` WHERE `id`=?";
        $eventData = $dbObj->db_query($sql,[$eventID]);
        if ($dbObj->num_rows($eventData) > 0) {
            return $dbObj->fetch_array($eventData);
        } else return NULL;
    }

    public function loadAllEvents($calID = 0, $searchKey = '') {

		$params = [];

        $userID = $_SESSION['userData']['id'];
        $sql = "SELECT `pe`.* FROM  `pec_events` as `pe` LEFT JOIN `pec_calendars` `pc` ON (`pe`.`cal_id` = `pc`.`id`) WHERE `pc`.`user_id`=?";
        
        // save binding param
        $uID = 'userID';
        array_push($params, $$uID);

        if (!is_array($calID) && $calID > 0) {
        	$sql.= " AND `pe`.`cal_id` IN (?)";

        	$cID = 'calID';
            array_push($params, $$cID);
        }
        else if (is_array($calID)) {
           
            $calIDs = implode(',', array_fill(0, count($calID), '?'));
            
            if ($calIDs == '') {
                $sql .= "";
            } else { 

                $sql .= " AND `pe`.`cal_id` IN ($calIDs)";

                
                $ccID = 'calID';
                $params = array_merge($params, $$ccID);

            }
        }

        if (isset($searchKey) && $searchKey != '') {

            $sql .= " AND `pe`.`title` LIKE ? ";

            $searchKey = "%{$searchKey}%";

            $sKey = 'searchKey';
            array_push($params, $$sKey);
        }

        $sql.= " ORDER BY `pe`.`start_date` ASC";
        
        $allEvents = $this->dbObj->db_query($sql, $params);

        if ($this->dbObj->num_rows($allEvents) > 0) {
            return $this->prepareEventsForCalendarToShow($allEvents);
        } else {
	        return NULL;
	    }
    }
    public function loadAllPublicEvents($calID = 0) {

        $sql = "SELECT `pe`.* FROM `pec_events` as `pe` LEFT JOIN `pec_calendars` `pc` ON (`pe`.`cal_id` = `pc`.`id`) WHERE ( `pe`.`privacy` = 'public' OR `pc`.`public` = 1 ) ";

        $calIdsBindStr = '';

        if($calID > 0 && is_array($calID)) {

            $calIdsBindStr = implode(',', array_fill(0, count($calID), '?'));

        }

        if($calIdsBindStr != '') {
            $sql .= " AND `pc`.`id` IN ($calIdsBindStr) ";
        }

        $sql .= " ORDER BY `pe`.`start_date` ASC";

        $allEvents = $this->dbObj->db_query($sql, $calID);

        if ($this->dbObj->num_rows($allEvents) > 0) {
            return $this->prepareEventsForCalendarToShow($allEvents);
        } else return NULL;
        
    }
    public function getWeekPositionInMonth($date, $rollover) {
        switch ($rollover) {
            case 0:
                $rollover = 'sunday';
            break;
            case 1:
                $rollover = 'monday';
            break;
            case 2:
                $rollover = 'tuesday';
            break;
            case 3:
                $rollover = 'wednesday';
            break;
            case 4:
                $rollover = 'thursday';
            break;
            case 5:
                $rollover = 'friday';
            break;
            case 6:
                $rollover = 'saturday';
            break;
            default:
                $rollover = 'monday';
            break;
        }
        $cut = substr($date, 0, 8);
        $daylen = 86400;
        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = round(($timestamp - $first) / $daylen);
        $i = 1;
        $weeks = 1;
        for ($i;$i <= $elapsed;$i++) {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);
            $day = strtolower(date("l", $daytimestamp));
            if ($day == strtolower($rollover)) $weeks++;
        }
        $date_parts = explode('-', $date);
        $date_parts[2] = '01';
        $first_of_month = implode('-', $date_parts);
        $day_first_of_month = strtolower(date("l", strtotime($first_of_month)));
        if ($day_first_of_month == strtolower($rollover)) $weeks = $weeks - 1;
        return $weeks;
    }
    public function getWeekPositionBasedOnDayInMonth($cDate, $dayNameOfTheMonthForStartDate) {
        $dayNameOfTheMonthForStartDate = strtolower($dayNameOfTheMonthForStartDate);
        $repeatLoop = strtotime($cDate);
        $dayFromDate = (int)date('j', $repeatLoop);
        $date_parts = explode('-', $cDate);
        $date_parts[2] = '01';
        $first_of_month = implode('-', $date_parts);
        $loopDTTime = strtotime($first_of_month);
        $weekCounter = 0;
        for ($i = $loopDTTime;$i <= $loopDTTime + ((int)date('t', $repeatLoop)) * 60 * 60 * 24;$i = $i + 60 * 60 * 24) {
            $cDayName = strtolower(date("l", $i));
            $loopDay = (int)date('j', $i);
            if ($loopDay > $dayFromDate) {
                break;
            }
            if ($dayNameOfTheMonthForStartDate == $cDayName) $weekCounter++;
        }
        return $weekCounter;
    }
    public function handleRepeatEvents($res, $eventValues, $start_time = '', $end_time = '') {
        return null;
    }
    public function prepareEventsForCalendarToShow($events) {
        if ($this->dbObj->num_rows($events) <= 0) return NULL;
        $myEvents = [];
        $repeatParams = NULL;
        $this->eventIndex = 0;
        while ($res = $this->dbObj->fetch_array_assoc($events)) {
            $id = $res['id'];
            $title = stripslashes($res['title'] ?? '');
            $url = $res['url'];
            $location = stripslashes($res['location'] ?? '');
            $image = stripslashes($res['image'] ?? '');
            $start_date = $res['start_date'];
            $start_time = $res['start_time'];
            $start_timestamp = $res['start_timestamp'];
            $start = $start_date . ' ' . $start_time;
            $end = '';
            if ($res['end_date'] != NULL) {
                $end_date = $res['end_date'];
                $end_time = $res['end_time'];
                $end_timestamp = $res['end_timestamp'];
                $end = $end_date . ' ' . $end_time;
            }
            $url = $res['url'];
            $borderColor = $res['borderColor'];
            $textColor = $res['textColor'];
            $backgroundColor = $res['backgroundColor'];
            $allDay = isset($res['allDay']) ? $res['allDay'] : '';
            $desc = stripslashes($res['description'] ?? '');
            $eventValues = array('id' => $id, 'title' => $title, 'location' => $location, 'image' => $image, 'start' => '', 'end' => '', 'borderColor' => $borderColor, 'textColor' => $textColor, 'backgroundColor' => $backgroundColor, 'allDay' => $allDay, 'description' => $desc);
            if (!isset($end_time) || is_null($end_time)) $end_time = '';
            $repeatEvents = $this->handleRepeatEvents($res, $eventValues, $start_time, $end_time);
            $eventValues['start'] = $start;
            $eventValues['end'] = $end;
            if (is_null($repeatEvents)) 
                $myEvents[$this->eventIndex] = $eventValues;
            else 
                $myEvents[$this->eventIndex] = array();
            
            if (!is_null($repeatEvents)) {
                if (count($myEvents[$this->eventIndex]) <= 0) unset($myEvents[$this->eventIndex]);
                $myEvents = array_merge($myEvents, $repeatEvents);
            }
            $this->eventIndex++;
        }
        function fixem($a, $b) {
            if ($a["start"] == $b["start"]) {
                return 0;
            }
            return ($a["start"] < $b["start"]) ? -1 : 1;
        }
        usort($myEvents, "fixem");
        $this->myEvents = $myEvents;
    }
    public function saveEvent($params = array()) {

        $clean_params = array_map(array($this, 'sanitize'), $params);


        return ($this->db->AutoExecute('pec_events', $clean_params, 'INSERT') && isset($this->db->_connectionID->insert_id)) ? $this->db->_connectionID->insert_id : $this->db->Insert_ID();
    }

    /**
     *  preventing XSS by escape all characters
     */

    private function sanitize($s) {
        return htmlspecialchars($s);
    }


    public function editEvent($params = array(), $id=0) {

        $clean_params = array_map(array($this, 'sanitize'), $params);

        return ($this->db->AutoExecute('pec_events', $clean_params, 'UPDATE', "id=$id")) ? $id : false;
    }
    public static function removeEvent($eventID) {
        if (!isset($_SESSION['userData'])) return false;
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $sql = "DELETE FROM `pec_events` WHERE `id`=?";
        return $isDelete = $dbObj->db_query($sql,[$eventID]);
    }
    public static function findExternalURLForCalendars($calIds = '') {
        if(!isset($_SESSION['userData'])) return false;
        //====DB
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;

        $activeExternalURLForCalendars = false;
        $activeExternalURLForCalendarsData = null;

        if($calIds=='' || (int)$calIds == 0 || $calIds[0]=='') {

            $sql = "SELECT `id`,`type`,`description`,`color` FROM `pec_calendars` WHERE `type` = 'url'";

            $activeExternalURLForCalendarsData = $dbObj->db_query($sql);
        
        }
        else {
            
            // creates a string containing ?,?,? 
            $calIdsBindStr = implode(',', array_fill(0, count($calIds), '?'));
            
            // $calIds = implode(',', $calIds);
            $sql = "SELECT `id`,`type`,`description`,`color` FROM `pec_calendars` WHERE `id` IN (". $calIdsBindStr .") AND `type` = 'url'";

            $activeExternalURLForCalendarsData = $dbObj->db_query($sql, $calIds);

        }


        if ($dbObj->num_rows($activeExternalURLForCalendarsData) > 0) {
            $activeExternalURLForCalendars =  $activeExternalURLForCalendarsData;
        } else $activeExternalURLForCalendars = false;

        return $activeExternalURLForCalendars;
    }
}