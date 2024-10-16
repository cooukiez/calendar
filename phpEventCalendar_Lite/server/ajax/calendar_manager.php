<?php
require_once("../../conf.php");

if(isset($_POST['create-update-calendar']) && $_POST['create-update-calendar'] == 1){
    //==== Get POST Params
    $name = (isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
    $description = (isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : '';

    $color = (isset($_POST['color']) && $_POST['color'] != '') ? $_POST['color'] : '#3a87ad';
    $type = (isset($_POST['type']) && $_POST['type'] != '') ? $_POST['type'] : 'user';
    $status = (isset($_POST['status']) && $_POST['status'] != '') ? $_POST['status'] : 'on';
    $show_in_list = (isset($_POST['show_in_list']) && $_POST['show_in_list'] != '') ? $_POST['show_in_list'] : '0';
    $public = (isset($_POST['public']) && $_POST['public'] != '') ? $_POST['public'] : 0;
    $reminder_message_email = (isset($_POST['reminder_message_email']) && $_POST['reminder_message_email'] != '') ? $_POST['reminder_message_email'] : '';
    $reminder_message_popup = (isset($_POST['reminder_message_popup']) && $_POST['reminder_message_popup'] != '') ? $_POST['reminder_message_popup'] : '';
    $access_key = (isset($_POST['access_key']) && $_POST['access_key'] != '') ? $_POST['access_key'] : '';
    $created_on = (isset($_POST['created_on']) && $_POST['created_on'] != '') ? $_POST['created_on'] : date('Y-m-d');
    $updated_on = (isset($_POST['updated_on']) && $_POST['updated_on'] != '') ? $_POST['updated_on'] : '';

    //==== Load Calendar Manager
    $calObj = new C_Calendar($name, $description, $color, $type, $status, $show_in_list, $public, $reminder_message_email, $reminder_message_popup, $access_key, $created_on, $updated_on);

    //==== Create Params Array for saving
    $params['name'] = $calObj->name;
    $params['description'] = $calObj->description;
    $params['color'] = $calObj->color;
    $params['type'] = $calObj->type;
    $params['status'] = $calObj->status;
    $params['show_in_list'] = $calObj->show_in_list;
    $params['public'] = $calObj->public;
    $params['reminder_message_email'] = $calObj->reminder_message_email;
    $params['reminder_message_popup'] = $calObj->reminder_message_popup;
    $params['access_key'] = $calObj->access_key;
    $params['created_on'] = $calObj->created_on;
    $params['updated_on'] = $calObj->updated_on;

    //==== Update Calendar
    if((int)$_POST['update-calendar'] > 0){
    }
    //==== Save Calendar
    else {
        $calObj->id = $calObj->saveCalendar($params);
        $params['id'] = $calObj->id;

        //==== Check if the user has any active calendar or not
        $userData = C_User::getUserDetails();

        //==== If there is no active calendar for the user, then set the newly created one. Otherwise leave this section
        if($userData['active_calendar_id']==0){
            C_User::setActiveCalendar($userData['id'],array($calObj->id));
            //==== also update the current session for the user
            $_SESSION['userData']['active_calendar_id'] = array($calObj->id);
        }
        foreach($params as $k=>$v){
            if($v == ' ' || $v == '') unset($params[$k]);
        }
        echo json_encode($params);
    }
}

if(isset($_POST['calendar-settings']) && $_POST['calendar-settings'] == '1'){
    //==== For Update
    if(isset($_POST['calendar-settings-update']) && $_POST['calendar-settings-update'] == '1') {

    }
    //==== For Save
    else {
        //==== update data
        $calObj = new C_Calendar('UPDATE_CALENDAR_PROPERTIES');
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'UPDATE_CAL_PUBLIC'){
    $calID = $_POST['vid'];
    $privacy = $_POST['vpublic'];
    if((int)$privacy == 1){
        C_Calendar::setCalToPublic($calID);
    }
    else {
        C_Calendar::setCalToPrivate($calID);
    }
    echo ((int)$privacy == 1) ? '0':'1';
}

if(isset($_POST['action']) && $_POST['action'] == 'SHARE_CALENDAR'){
  $link = $_POST['link'];
  $sendto = $_POST['email'];
  $message = $_POST['message'];
  C_Calendar::sendPublicCal($link, $sendto, $message);
}

if(isset($_POST['action']) && $_POST['action'] == 'UPDATE_CALENDAR'){
    $cID = $_POST['cID'];
    $params['name'] = $_POST['name'];
    $params['color'] = $_POST['clr'];
    $params['public'] = $_POST['privacy'];
    $params['description'] = $_POST['cal_desc'];
    $params['user_id'] = $_SESSION['userData']['id'];
    C_Calendar::updateCalendar($params,$cID);
}

if(isset($_POST['action']) && $_POST['action'] == 'EXPORT_CALENDAR'){
    $cID = $_POST['cID'];
    $directory = C_Calendar::exportCalendar($cID);
    return $directory;
}
?>