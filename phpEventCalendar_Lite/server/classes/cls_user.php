<?php
class C_User {
    public $db;
    public $dbObj;
    public $userData;
    public $loggedIn;
    public $errorMsg;
    public $successMsg;
    public function __construct($username, $password) {
        if (empty($username) || empty($password)) {
            $this->errorMsg = 'Username OR Password is Missing';
            $this->loggedIn = false;
            return false;
        } else {
            @$this->userData = new stdClass();

            @$this->userData->username = trim($username);
            @$this->userData->password = md5(trim($password));
            @$this->loggedIn = 'in_progress';
        }
        $this->dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $this->db = $this->dbObj->db;
    }
    public function checkUser($username, $password) {
        $sql = "SELECT * FROM  `pec_users` WHERE `username` = ? AND `password` = ? ";
        $isUser = $this->dbObj->db_query($sql,[$username, $password]);
        if ($this->dbObj->num_rows($isUser) > 0) {
            $this->userData->data = $isUser;
            $this->loggedIn = true;
            $this->successMsg = 'Welcome ' . $username;
            return true;
        } else {
            $this->userData = NULL;
            $this->errorMsg = 'Incorrect Username or Password';
            $this->loggedIn = false;
            return false;
        }
    }
    public static function getUserDetails($userID = 0) {
        if (isset($_SESSION['userData'])) return $_SESSION['userData'];
    }
    public static function setActiveCalendar($userID, $calIDs) {
        $dbObj = new C_Database(PEC_DB_HOST, PEC_DB_USER, PEC_DB_PASS, PEC_DB_NAME, PEC_DB_TYPE, PEC_DB_CHARSET);
        $db = $dbObj->db;
        $cids = implode(',', $calIDs);
        $sql = "UPDATE `pec_users` SET `active_calendar_id`= ? WHERE `id`= ? ";
        $isUser = $dbObj->db_query($sql, [$cids, $userID]);
    }
    public function userLogOut() {
    }
    public function getUserPermissions() {
    }
    public function getUserAllCalendars() {
    }
    public function getUserCalendar() {
    }
    private function storeUserDataIntoSession() {
    }
}