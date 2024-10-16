<?php
require_once("../../conf.php");
/*=====================================================================================
| SAVE EVENT & UPDATE EVENT
|*=====================================================================================*/
if(isset($_POST['update-event'])){
    //==== Get POST Params
    $title = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
    $start_date = (isset($_POST['start-date']) && $_POST['start-date'] != '') ? $_POST['start-date'] : '';
    $start_time = (isset($_POST['start-time']) && $_POST['start-time'] != '') ? $_POST['start-time'] : '';
    $start = $start_date.' '.$start_time;

    $allDay = (isset($_POST['allDay']) && $_POST['allDay'] != '') ? $_POST['allDay'] : '';

    if($allDay == 'on' || $allDay == '1'){
        //$end_date = '';
        //$end_time = '';
        //$end = '';
        $end_date = (isset($_POST['end-date']) && $_POST['end-date'] != '') ? $_POST['end-date'] : '';
        $end_time = (isset($_POST['end-time']) && $_POST['end-time'] != '') ? $_POST['end-time'] : '';
        $end = $end_date.' '.$end_time;
    }
    else {
        $end_date = (isset($_POST['end-date']) && $_POST['end-date'] != '') ? $_POST['end-date'] : '';
        $end_time = (isset($_POST['end-time']) && $_POST['end-time'] != '') ? $_POST['end-time'] : '';
        $end = $end_date.' '.$end_time;
    }

    $url = (isset($_POST['url']) && $_POST['url'] != '') ? $_POST['url'] : '';
    $className = (isset($_POST['className']) && $_POST['className'] != '') ? $_POST['className'] : '';
    $editable = (isset($_POST['editable']) && $_POST['editable'] != '') ? $_POST['editable'] : '';
    $startEditable = (isset($_POST['startEditable']) && $_POST['startEditable'] != '') ? $_POST['startEditable'] : '';
    $durationEditable = (isset($_POST['durationEditable']) && $_POST['durationEditable'] != '') ? $_POST['durationEditable'] : '';
    $source = (isset($_POST['source']) && $_POST['source'] != '') ? $_POST['source'] : '';
    $color = (isset($_POST['color']) && $_POST['color'] != '') ? $_POST['color'] : '';
    $backgroundColor = (isset($_POST['backgroundColor']) && $_POST['backgroundColor'] != '') ? $_POST['backgroundColor'] : '';
    $borderColor = (isset($_POST['borderColor']) && $_POST['borderColor'] != '') ? $_POST['borderColor'] : '';
    $textColor = (isset($_POST['textColor']) && $_POST['textColor'] != '') ? $_POST['textColor'] : '';
    $description = (isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : '';
    $image = (isset($_POST['imageName']) && $_POST['imageName'] != '') ? $_POST['imageName'] : '';

    $location = (isset($_POST['location']) && $_POST['location'] != '') ? $_POST['location'] : '';
    $privacy = (isset($_POST['privacy']) && $_POST['privacy'] != '') ? $_POST['privacy'] : '';
    $repeat_type = (isset($_POST['repeat_type']) && $_POST['repeat_type'] != '') ? $_POST['repeat_type'] : 'none';
    $repeat_interval = (isset($_POST['repeat_interval']) && $_POST['repeat_interval'] != '') ? $_POST['repeat_interval'] : '';

    $repeat_by = (isset($_POST['repeat_by']) && $_POST['repeat_by'] != '') ? $_POST['repeat_by'] : '';

    if($repeat_type != 'monthly') {
        $repeat_on_sun = (isset($_POST['repeat_on_sun']) && $_POST['repeat_on_sun'] != '') ? $_POST['repeat_on_sun'] : '0';
        $repeat_on_mon = (isset($_POST['repeat_on_mon']) && $_POST['repeat_on_mon'] != '') ? $_POST['repeat_on_mon'] : '0';
        $repeat_on_tue = (isset($_POST['repeat_on_tue']) && $_POST['repeat_on_tue'] != '') ? $_POST['repeat_on_tue'] : '0';
        $repeat_on_wed = (isset($_POST['repeat_on_wed']) && $_POST['repeat_on_wed'] != '') ? $_POST['repeat_on_wed'] : '0';
        $repeat_on_thu = (isset($_POST['repeat_on_thu']) && $_POST['repeat_on_thu'] != '') ? $_POST['repeat_on_thu'] : '0';
        $repeat_on_fri = (isset($_POST['repeat_on_fri']) && $_POST['repeat_on_fri'] != '') ? $_POST['repeat_on_fri'] : '0';
        $repeat_on_sat = (isset($_POST['repeat_on_sat']) && $_POST['repeat_on_sat'] != '') ? $_POST['repeat_on_sat'] : '0';
    }
    else if ($repeat_type == 'monthly'){
        $repeat_on_sun = '0';
        $repeat_on_mon = '0';
        $repeat_on_tue = '0';
        $repeat_on_wed = '0';
        $repeat_on_thu = '0';
        $repeat_on_fri = '0';
        $repeat_on_sat = '0';

        if($repeat_by == 'repeat_by_day_of_the_month'){

        }
        else if($repeat_by == 'repeat_by_day_of_the_week'){

        }
    }

    $repeat_start_date = (isset($_POST['repeat_start_date']) && $_POST['repeat_start_date'] != '') ? $_POST['repeat_start_date'] : '';

    $repeat_end_on = (isset($_POST['repeat_end_on']) && $_POST['repeat_end_on'] != '') ? $_POST['repeat_end_on'] : '';
    $repeat_end_after = (isset($_POST['repeat_end_after']) && $_POST['repeat_end_after'] != '') ? $_POST['repeat_end_after'] : '';
    $repeat_never = (isset($_POST['repeat_never']) && $_POST['repeat_never'] != '') ? $_POST['repeat_never'] : '';

    //==== reminder values
    $reminder_type = (isset($_POST['reminder_type']) && $_POST['reminder_type'] != '') ? $_POST['reminder_type'] : '';
    $reminder_time = (isset($_POST['reminder_time']) && $_POST['reminder_time'] != '') ? $_POST['reminder_time'] : '';
    $reminder_time_unit = (isset($_POST['reminder_time_unit']) && $_POST['reminder_time_unit'] != '') ? $_POST['reminder_time_unit'] : '';

    $free_busy = (isset($_POST['free_busy']) && $_POST['free_busy'] != '') ? $_POST['free_busy'] : '';
    $guests = (isset($_POST['guests']) && $_POST['guests'] != '') ? $_POST['guests'] : '';


    //==== Load Event Manager

    $eventObj = new C_Events(0,$title, $start, $end, $url, $allDay, $className, $editable, $startEditable,
        $durationEditable,$source,$color, $backgroundColor,$borderColor,$textColor,$description,
        $free_busy,$location,$privacy,$repeat_start_date, $repeat_end_on, $repeat_end_after,
        $repeat_never,$repeat_by,$repeat_type, $repeat_interval,
        $repeat_on_sun,$repeat_on_mon,$repeat_on_tue,$repeat_on_wed,$repeat_on_thu,$repeat_on_fri,$repeat_on_sat, $image);

    //==== Create Params Array for saving
    $params['title'] = $eventObj->title;
    $params['start_date'] = $eventObj->start_date;
    $params['start_time'] = $eventObj->start_time;
    $params['start_timestamp'] = $eventObj->start_timestamp;
    $params['end_date'] = $eventObj->end_date;
    $params['end_time'] = $eventObj->end_time;
    $params['end_timestamp'] = $eventObj->end_timestamp;
    $params['backgroundColor'] = $eventObj->backgroundColor;
    $params['textColor'] = $eventObj->textColor;
    $params['description'] = $eventObj->description;
    $params['free_busy'] = $eventObj->free_busy;
    $params['location'] = $eventObj->location;
    $params['privacy'] = $eventObj->privacy;
    $params['image'] = $eventObj->image;

    //$params['reminder_type'] = $eventObj->reminder_type;
    //$params['reminder_time'] = $eventObj->reminder_time;
    //$params['reminder_time_unit'] = $eventObj->reminder_time_unit;

    if($eventObj->repeat_type != 'none'){
        $params['repeat_type'] = $eventObj->repeat_type;
        $params['repeat_start_date'] = $eventObj->repeat_start_date;
        $params['repeat_end_on'] = $eventObj->repeat_end_on;
        $params['repeat_end_after'] = $eventObj->repeat_end_after;
        $params['repeat_never'] = $eventObj->repeat_never;
        $params['repeat_by'] = $eventObj->repeat_by;
        $params['repeat_on_sun'] = $eventObj->repeat_on_sun;
        $params['repeat_on_mon'] = $eventObj->repeat_on_mon;
        $params['repeat_on_tue'] = $eventObj->repeat_on_tue;
        $params['repeat_on_wed'] = $eventObj->repeat_on_wed;
        $params['repeat_on_thu'] = $eventObj->repeat_on_thu;
        $params['repeat_on_fri'] = $eventObj->repeat_on_fri;
        $params['repeat_on_sat'] = $eventObj->repeat_on_sat;
        $params['repeat_interval'] = $eventObj->repeat_interval;
    }
    else
        $params['repeat_type'] = 'none';


    if(isset($eventObj->backgroundColor))
        $params['borderColor'] = $eventObj->backgroundColor;
    else
        $params['borderColor'] = '';

    $params['url'] = $eventObj->url;
    $params['allDay'] = $eventObj->allDay;

    //==================================================================================================================
    //==== Update Event
    //==================================================================================================================
    if((int)$_POST['update-event'] > 0){
        //==== Get currentView from POST
        $currentView = $_POST['currentView'];
        //==== Set currentView to the Session
        $_SESSION['currentView'] = $currentView;
        //==== Unset the currentView param from $_POST
        unset($_POST['currentView']);

        //==== Get current editing event id
        $uid = (int)$_POST['update-event'];

        //==== If Any Calendar is selected while an event is being created
        $returnEventData = false;
        if(isset($_POST['selected_calendars']) && count($_POST['selected_calendars']) > 0){
            foreach($_POST['selected_calendars'] as $k => $cid){
                $params['cal_id']   = $cid;
                $eventObj->calendar_selected = true;
                //===check whether new calendar is in activated calendars.
                if(in_array($cid,$_SESSION['userData']['active_calendar_id'])) $returnEventData = true;
                $eventObj->id       = $eventObj->editEvent($params,$uid);
            }

        }

        foreach($params as $k=>$v){
            if($v == ' ' || $v == '') unset($params[$k]);
        }

        $jsonParam = array();

        $jsonParam['id']       = $uid;
        $jsonParam['start']    = trim($eventObj->start_date.' '.$eventObj->start_time);
        $jsonParam['end']      = trim($eventObj->end_date.' '.$eventObj->end_time);
        $jsonParam['title']    = $eventObj->title;
        $jsonParam['backgroundColor'] = $eventObj->backgroundColor;
        $jsonParam['borderColor']   = $eventObj->backgroundColor;

        //==== Handling Repeating Events
        $eventValues = array(
            'id' => (empty($eventObj->id) ? $eventObj->id:$uid),
            'title' => $eventObj->title,
            'start' => '',
            'end' => '',
            'borderColor' => $eventObj->backgroundColor,
            'textColor' => $eventObj->textColor,
            'backgroundColor' => $eventObj->backgroundColor,
            'allDay' => $eventObj->allDay
        );

        //==============================================================================================================
        //===== handling repeating events
        //==============================================================================================================
        $eventParams = $params;
        $eventParams['id'] = (empty($eventObj->id) ? $eventObj->id:$uid);
        $repeatEvents = $eventObj->handleRepeatEvents($eventParams,$eventValues,$eventObj->start_time,$eventObj->end_time);



        unset($params['cal_id']);
        unset($params['repeat_type']);
        unset($params['start_date']);
        unset($params['url']);
        unset($params['start_time']);
        unset($params['start_timestamp']);
        unset($params['end_date']);
        unset($params['end_time']);
        unset($params['end_timestamp']);

        foreach($jsonParam as $k=>$v){
            if($v == ' ' || $v == '') unset($jsonParam[$k]);
        }
        $jsonParam['allDay']   = ($eventObj->allDay == 'on') ? 'true':'';

        //==== if new calendar id is not in the activated calendar list then do not return event data to JSON
        if(!$returnEventData) {
            echo '[]';
            die;
        }
        if(!is_null($repeatEvents)) echo json_encode($repeatEvents);
        else echo '['.json_encode($jsonParam).']';
        //echo "<script type='text/javascript'>location.reload();</script>";
        die;

        //echo '['.json_encode($jsonParam).']';
    }
    //==== Save Event
    else {

        if(isset($_SESSION['currentLoadedCalendar'])) $calID = $_SESSION['currentLoadedCalendar'];
        else if(isset($_SESSION['userData']['active_calendar_id'])) $calID = $_SESSION['userData']['active_calendar_id'];
        else {
            //===get all calendars
            $allCalls = $eventObj->loadAllCalendars($_SESSION['userData']['id']);
            //===get first calendar id
            $calID = $allCalls[0]['id'];
        }

        foreach($params as $k=>$v){
            if($v == ' ' || $v == '') unset($params[$k]);
        }

        //==============================================================================================================
        //==== Saving Event
        //==============================================================================================================

        //==== If No Calendar is selected
        $paramsWhenCalendarIsSelected = NULL;
//        print_r($params);
        if(count(@$_POST['selected_calendars']) <= 0 ){
            //===get all calendars
            $allCalls = $eventObj->loadAllCalendars($_SESSION['userData']['id']);
            //===get first calendar id
            $calID = $allCalls[0]['id'];

            $params['cal_id']   = $calID;
            $eventObj->id = $eventObj->saveEvent($params);
            $params['id'] = $eventObj->id;
        }
        //==== If Any Calendar is selected while an event is being created
        else if(isset($_POST['selected_calendars']) && count($_POST['selected_calendars']) > 0){
            foreach($_POST['selected_calendars'] as $k => $cid){
                $params['cal_id']   = $cid;
                $eventObj->calendar_selected = true;
                $eventObj->id = $id = $eventObj->saveEvent($params);

                //===Only store current activated calendars in session
                if(count($_SESSION['userData']['active_calendar_id']) > 0){
                    if(in_array($cid,$_SESSION['userData']['active_calendar_id']))$paramsWhenCalendarIsSelected[] = $id;
                }
                else {
                    $paramsWhenCalendarIsSelected[] = $id;
                }

            }
        }


        $params['start']    = trim($eventObj->start_date.' '.$eventObj->start_time);
        $params['end']      = trim($eventObj->end_date.' '.$eventObj->end_time);

        $jsonParam = array();

        $jsonParam['start']    = trim($eventObj->start_date.' '.$eventObj->start_time);
        $jsonParam['end']      = trim($eventObj->end_date.' '.$eventObj->end_time);
        $jsonParam['title']    = $eventObj->title;
        $jsonParam['backgroundColor'] = $eventObj->backgroundColor;
        $jsonParam['borderColor']   = $eventObj->backgroundColor;

        //==== Handling Repeating Events
        $eventValues = array(
            'id' => (empty($eventObj->id) ? $eventObj->id:$id),
            'title' => $eventObj->title,
            'start' => '',
            'end' => '',
            'borderColor' => $eventObj->backgroundColor,
            'textColor' => $eventObj->textColor,
            'backgroundColor' => $eventObj->backgroundColor,
            'allDay' => $eventObj->allDay
        );

        //==============================================================================================================
        //===== handling repeating events
        //==============================================================================================================
        $eventParams = $params;
        $eventParams['id'] = $eventObj->id;
        $repeatEvents = $eventObj->handleRepeatEvents($eventParams,$eventValues,$eventObj->start_time,$eventObj->end_time);

        /*
        echo '<pre>';
        print_r($jsonParam);
        echo '</pre>';
        */

        foreach($jsonParam as $k=>$v){
            if($v == ' ' || $v == '') unset($jsonParam[$k]);
        }
        $jsonParam['allDay']   = ($eventObj->allDay == 'on') ? 'true':'';

        unset($params['cal_id']);
        unset($params['repeat_type']);
        unset($params['url']);
        unset($params['start_date']);
        unset($params['start_time']);
        unset($params['start_timestamp']);
        unset($params['end_date']);
        unset($params['end_time']);
        unset($params['end_timestamp']);

        //==============================================================================================================
        //====Creating JSON for recently created events
        //==============================================================================================================
        //====If Calendar is selected
        if($eventObj->calendar_selected){
            $newEvents = NULL;
            //==== If single calendar is being selected
            if(count($paramsWhenCalendarIsSelected) ==1){
                $jsonParam['id']        = $paramsWhenCalendarIsSelected[0];
                if(!is_null($repeatEvents)) echo json_encode($repeatEvents);
                else echo '['.json_encode($jsonParam).']';
                die;
            }
            //==== If Multiple calendars are being selected
            else {
                if(isset($paramsWhenCalendarIsSelected) && count($paramsWhenCalendarIsSelected) > 0){
                    foreach($paramsWhenCalendarIsSelected as $k => $eventID){
                        $jsonParam['id']       = $eventID;
                        $newEvents[]           = $jsonParam;
                    }
                    echo json_encode($newEvents);
                    die;
                }
                else {
                    echo json_encode(array('title'=>'NO_EVENT_FOUND_FOR_SELECTED_CALENDARS'));
                    die;
                }
            }
        }
        //====If No calendar is selected
        else {
            $jsonParam['id']       = $eventObj->id;
            echo '['.json_encode($jsonParam).']';
            die;
        }
    }
}
/*=====================================================================================
| SAVE MOVED EVENT BASED ON DROP
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'SAVE_MOVED_EVENT'){

    $eid = $_POST['eventID'];
    //===check the event if it is a repeating event
    $eventData = C_Events::loadSingleEventData($eid);
    if($eventData['repeat_type']!='none'){
        echo 'repeating';
        die;
    }

    $title = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
    $start_date = (isset($_POST['sdate']) && $_POST['sdate'] != '') ? $_POST['sdate'] : '';
    $start_time = (isset($_POST['stime']) && $_POST['stime'] != '') ? $_POST['stime'] : '';
    $start = $start_date.' '.$start_time;

    $allDay = (isset($_POST['allDay']) && $_POST['allDay'] != '') ? $_POST['allDay'] : '';

    if($allDay == '1'){
        $end_date = (isset($_POST['edate']) && $_POST['edate'] != '') ? $_POST['edate'] : '';
        $end_time = (isset($_POST['etime']) && $_POST['etime'] != '') ? $_POST['etime'] : '';
        $end = $end_date.' '.$end_time;
        $allDay = 'on';
    }
    else {
        $end_date = (isset($_POST['edate']) && $_POST['edate'] != '') ? $_POST['edate'] : '';
        $end_time = (isset($_POST['etime']) && $_POST['etime'] != '') ? $_POST['etime'] : '';
        $end = $end_date.' '.$end_time;
        $allDay = '';
    }

    $eventObj = new C_Events(0,$title, $start, $end);

    //==== Create Params Array for saving
    $params['title'] = $eventObj->title;
    $params['start_date'] = $eventObj->start_date;
    $params['start_time'] = $eventObj->start_time;
    $params['start_timestamp'] = $eventObj->start_timestamp;
    $params['end_date'] = $eventObj->end_date;
    $params['end_time'] = $eventObj->end_time;
    $params['end_timestamp'] = $eventObj->end_timestamp;
    $params['allDay'] = $allDay;
    $eventObj->id       = $eventObj->editEvent($params,$eid);

    if($eventObj->id) echo $eventObj->id;
    else echo 'failed';
}

/*=====================================================================================
| LOAD EVENTS BASED ON SEARCH KEY
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_EVENTS_BASED_ON_SEARCH_KEY'){

    //==== Some security checking here
    $searchKey = trim(preg_replace( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i' , '', htmlentities(strip_tags($_POST['searchKey']),ENT_QUOTES) ));

    //==== get all events for the selected calendars
    $allEvents = new C_Events($searchKey,'LOAD_MY_EVENTS_BASED_SEARCH_KEY');


//    echo '[{title:NO___CALENDER___FOUND}]';
//    die;
    if($allEvents->myEvents == NULL) echo json_encode(array('title'=>'NO___EVENT___FOUND'));
    else echo json_encode($allEvents->myEvents);
}

/*=====================================================================================
| LOAD EVENTS BASED ON CALENDAR ID
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_EVENTS_BASED_ON_CALENDAR_ID'){

    //==== if calendarID is NULL then fetch the first calendar id for the user
    if(!isset($_POST['calendarID']) || is_null($_POST['calendarID']) || !$_POST['calendarID']) {
        $firstCalID = C_Calendar::getFirstCalendarID($_SESSION['userData']['id']);
        $_POST['calendarID'] = $firstCalID;
    }

    //==== update active calendar for this user
    if(!is_array($_POST['calendarID'])) $callIDs = array($_POST['calendarID']);
    else $callIDs = $_POST['calendarID'];
    C_User::setActiveCalendar($_SESSION['userData']['id'],$callIDs);
    //==== also update the current session for the user
    $_SESSION['userData']['active_calendar_id'] = $callIDs;

    //==== find if one or more calendar(s) is/are having external URL(s), Ex: google URL
    $activeExternalURLForCalendars = C_Events::findExternalURLForCalendars($callIDs);

    //==== send a message to javascript to reload the page as one or more calendars is/are having external URL(s)
    if($activeExternalURLForCalendars){
        echo json_encode(array('title'=>'CALENDARS___HAVE___URL'));
        die;
    }
    //==== get all events for the selected calendars
    $allEvents = new C_Events($_POST['calendarID'],'LOAD_MY_EVENTS');


//    echo '[{title:NO___CALENDER___FOUND}]';
//    die;

    if($allEvents->myEvents == NULL) echo '[{title:NO___EVENT___FOUND}]';
    else echo json_encode($allEvents->myEvents);
}


/*=====================================================================================
| LOAD PUBLIC EVENTS BASED ON CALENDAR ID
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_PUBLIC_EVENTS_BASED_ON_CALENDAR_ID'){

    $calObj = new C_Calendar('LOAD_PUBLIC_CALENDARS');

    if(!isset($_POST['calendarID']) || is_null($_POST['calendarID']) || !$_POST['calendarID']) {
        //==== find all public calendars
        foreach($calObj->allCalendars as $cID => $calData){
            $callIDs[] = $calData['id'];
        }
    }
    else $callIDs = $_POST['calendarID'];


    //==== find if one or more calendar(s) is/are having external URL(s), Ex: google URL
    $activeExternalURLForCalendars = C_Events::findExternalURLForCalendars($callIDs);

    //==== send a message to javascript to reload the page as one or more calendars is/are having external URL(s)
    if($activeExternalURLForCalendars){
        echo json_encode(array('title'=>'CALENDARS___HAVE___URL'));
        die;
    }
    //==== get all events for the selected calendars
    $allEvents = new C_Events($callIDs,'LOAD_GUEST_EVENTS');


//    echo '[{title:NO___CALENDER___FOUND}]';
//    die;

    if($allEvents->myEvents == NULL) echo '[{title:NO___EVENT___FOUND}]';
    else echo json_encode($allEvents->myEvents);
}


/*=====================================================================================
| LOAD A SINGLE EVENT BASED ON EVENT ID
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_SINGLE_EVENT_BASED_ON_EVENT_ID'){
    //=== load event data
    $eventData = C_Events::loadSingleEventData($_POST['eventID']);

    //=== strip slashes
    $eventData['title'] = stripslashes($eventData['title'] ?? '');
    $eventData['location'] = stripslashes($eventData['location'] ?? '');
    $eventData['description'] = stripslashes($eventData['description'] ?? '');


    if($eventData == NULL) echo '[{title:NO___EVENT___FOUND}]';
    else echo json_encode($eventData);
}


/*=====================================================================================
| LOAD A SINGLE EVENT BASED ON EVENT ID
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_SINGLE_EVENT_BASED_ON_EVENT_ID_PUBLIC'){
    $eventData = C_Events::loadSingleEventData($_POST['eventID']);
    //=== strip slashes
    $eventData['title'] = stripslashes($eventData['title'] ?? '');
    $eventData['location'] = stripslashes($eventData['location'] ?? '');
    $eventData['description'] = stripslashes($eventData['description'] ?? '');

    if($eventData == NULL) echo '[{title:NO___EVENT___FOUND}]';
    else echo json_encode($eventData);
}


/*=====================================================================================
| LOAD A SINGLE EVENT BASED ON EVENT ID
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_SELECTED_CALENDAR_FROM_SESSION'){
    if(count($_SESSION['userData']['active_calendar_id']) > 0)    echo json_encode($_SESSION['userData']['active_calendar_id']);
    else if(C_Calendar::getFirstCalendarID($_SESSION['userData']['id'])) echo json_encode(array($_SESSION['userData']['id']));
    else echo 'NO_SELECTED_CALENDAR_FOUND';
}

/*=====================================================================================
| LOAD SELECTED CALENDAR COLOR
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'LOAD_SELECTED_CALENDAR_COLOR'){
    if(count($_SESSION['userData']['active_calendar_id']) > 0) {
        $selectedCals = implode(',',$_SESSION['userData']['active_calendar_id']);
        $calData = C_Calendar::loadSingleCalendarData($selectedCals);

        //=== For phase 1 only 1 calendar can be selected, so return the 1st data only
        echo $getFirstCalData = current($calData);

    }
    else echo 'NO_SELECTED_CALENDAR_FOUND';
}

/*=====================================================================================
| REMOVE EVENT
|*=====================================================================================*/
if(isset($_POST['action']) && $_POST['action'] == 'REMOVE_THIS_EVENT'){
    if(isset($_SESSION['userData']['id'])) {
        $eventID = $_POST['eventID'];
        $isDelete = C_Events::removeEvent($eventID);
        if($isDelete) echo $eventID;
    }
    else echo 'NO_SELECTED_EVENT_FOUND';
}





?>