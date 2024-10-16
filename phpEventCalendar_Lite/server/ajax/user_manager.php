<?php
    require_once("../../conf.php");

    $userObj = new C_User($_POST['username'],$_POST['password']);

    if(!$userObj->loggedIn){
        unset($_SESSION['userData']);
        echo 'error#'.$userObj->errorMsg;
    }
    else if($userObj->loggedIn == 'in_progress'){
        $checkUser = $userObj->checkUser($userObj->userData->username, $userObj->userData->password);

        if($checkUser && $userObj->loggedIn){
            //==== store in session if login is successful
            $_SESSION['userData'] = $userObj->userData->data->fields;
            $_SESSION['userData']['active_calendar_id'] = explode(',',$_SESSION['userData']['active_calendar_id']);
            $_SESSION['userData']['loggedIn'] = $userObj->loggedIn;
            echo 'success#'.$userObj->successMsg;
        }
        else if(!$userObj->loggedIn){
            unset($_SESSION['userData']);
            echo 'error#'.$userObj->errorMsg;
        }
    }
?>