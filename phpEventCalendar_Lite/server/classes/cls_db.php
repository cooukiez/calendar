<?php
/**
 * ADODB Layer
 ************************* working with this function in database layer class ************************
 *                                                                                                   *
 *  Connect to the Database                                                                          *
 *  Go to http://phplens.com/lens/adodb/docs-adodb.htm#connect_ex for database connection reference  *
 *  to other types of databases and simply modify/add to the Switch statement.                       *
 *                                                                                                   *
 *  There are multiple ways to connect Oracle db                                                     *
 *  see "http://phplens.com/adodb/code.initialization.html#oci8"                                     *
 *  and change it properly to suit your needs                                                        *
 *                                                                                                   *
 *****************************************************************************************************
 * @author: phplens
 * @url: http://phplens.com/lens/adodb/docs-adodb.htm#connect_ex
 */

/**
 * this is nessecory for PHP that running on Windows
 *
 * @ignore
 */
if (!session_id()) {
    session_start();
}

/**
 * Class C_Database Connect to the Database
 *
 * Using this DB layer, the system can be connected with following DB Servers
 * - Access
 * - ODBC MSSQL Native
 * - ODBC MSSQL
 * - Postgres
 * - DB2 DSNLESS
 * - IBASE
 * - OCI805
 * - SQLITE
 * - Informix
 * - Informix72
 * - ODBC
 * - MySQL
 * - MySQLi
 *
 * @author: phplens
 */
class C_Database
{
    /**
     * @var string $hostName hostname
     */
    public $hostName;

    /**
     * @var string $userName username
     */
    public $userName;

    /**
     * @var string $password password
     */
    public $password;

    /**
     * @var string $databaseName name of the database
     */
    public $databaseName;

    /**
     * @var string $tableName name of the DB table
     */
    public $tableName;

    /**
     * @var string $link database link
     */
    public $link;

    /**
     * @var string $dbType Database Type, by default it is MySQL
     */
    public $dbType;

    /**
     * @var string $charset Character Set of DB
     */
    public $charset;

    /**
     * @var object $db Database object
     */
    public $db;

    /**
     * @var object $result Database query result set
     */
    public $result;

    /**
     * Constructs DB object
     * By default it creates mysql DB object with necessary credentials provided
     *
     * @param $host
     * @param $user
     * @param $pass
     * @param $dbName
     * @param string $db_type
     * @param string $charset
     *
     * @author Richard Z.C. <info@phpeventcalendar.com>
     */
    public function __construct($host, $user, $pass, $dbName, $db_type = "mysql", $charset = "")
    {
        $this->hostName = $host;
        $this->userName = $user;
        $this->password = $pass;
        $this->databaseName = $dbName;
        $this->dbType = $db_type;
        $this->charset = $charset;

        $this->_db_connect();
    }

    public function _db_connect()
    {
        switch ($this->dbType) {
            case "access":
                $this->db = ADONewConnection($this->dbType);
                $dsn = "Driver={Microsoft Access Driver (*.mdb)};Dbq=" . $this->databaseName . ";Uid=" . $this->userName . ";Pwd=" . $this->password . ";";
                $this->db->Connect($dsn);
                break;
            case "odbc_mssql_native":
                $this->db = ADONewConnection('odbc_mssql');
                // DSN connectivity through SQL Native Client 10.0 ODBC Driver
                $dsn = "Driver={SQL Server};Server=" . $this->hostName . ";Database=" . $this->databaseName . ";";
                $this->db->Connect($dsn, $this->userName, $this->password);
                break;
            case "odbc_mssql":
                $this->db = ADONewConnection($this->dbType);
                // DSN connectivity through unixODBC
                $this->db->Connect($this->hostName, $this->userName, $this->password);
                break;
            case "postgres":
                $this->db = ADONewConnection($this->dbType);
                $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Error: Could not connect to the database");
                if (!empty($this->charset)) {
                    $this->db->Execute("SET NAMES '$this->charset'");
                }
                break;
            case "db2":
                $this->db = ADONewConnection($this->dbType);
                $dsn = "driver={IBM db2 odbc DRIVER};Database=" . $this->databaseName . ";hostname=" . $this->hostName . ";port=50000;protocol=TCPIP;uid=" . $this->userName . "; pwd=" . $this->password;
                $this->db->Connect($dsn);
                break;
            case 'db2-dsnless':
                $this->db = ADONewConnection('db2');
                $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName);
                break;
            case "ibase":
                $this->db = ADONewConnection($this->dbType);
                $this->db->Connect($this->hostName . $this->databaseName, $this->userName, $this->password);
                break;
            case "oci805":
                // Host name and SID
                $this->db = ADONewConnection($this->dbType); // Christopher: It�s missing the code to initialize the db object for oci8 db type:
                $ret = $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName);
                if (!$ret) {
                    // Host Address and Service Name
                    // <servicename> is passed in databaseName
                    $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName);
                }

                // TNS Name defined in tnsnames.ora (or ONAMES or HOSTNAMES), eg. 'myTNS'
                // $this->db->Connect(false, $this->userName, $this->password, 'myTNS');

                break;
            case "sqlite":
                $this->db = ADONewConnection('sqlite');
                $this->db->Connect($this->hostName); // e.g. c:\sqllite.db - sqlite will create if does not exist
                break;
            case "informix":
                $this->db = ADONewConnection('informix');
                $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Error: Could not connect to the database");
                break;
            case "informix72":
                $this->db = ADONewConnection('informix72');
                $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Error: Could not connect to the database");
                break;
            // the generic odbc presumes a correctly configured odbc.ini in /etc/
            case "odbc":
                $this->db = ADONewConnection($this->dbType);
                $dsn = "DSN=" . $this->hostName . ";uid=" . $this->userName . "; pwd=" . $this->password;
                $this->db->Connect($dsn);
                break;
            // default should be mysql and all other databases using the following form of connection
            case "mysql":
            case "mysqli":
            default:
                $this->db = ADONewConnection('mysqli'); // PHP 5.5 deprecates mysql extension. Switching to mysqli
                $this->db->Connect($this->hostName, $this->userName, $this->password, $this->databaseName) or die("Error: Could not connect to the database");
                if (!empty($this->charset)) {
                    $this->db->Execute("SET NAMES '$this->charset'");
                }
        }
    }

    /**
     * Executes Database SQL
     * Desc: query database, shows errors in details if any
     *
     * @param $query_str - sql statement
     * @param $input_arr - inputarr when using Prepare() 
     * @return mixed
     * @author phplens
     */
    public function db_query($query_str, $input_arr = array())
    {
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);

        if(!empty($input_arr)){

            $clean_input_arr = array_map(array($this, 'sanitize'), $input_arr);

            $result = $this->db->Execute($query_str, $clean_input_arr) or die(
            (C_Utility::is_debug()) ?
                "\n" . 'PEC_DEBUG: C_Database->db_query() - ' . $this->db->ErrorMsg() . "\n" . 'SQL: ' . $query_str :
                "\n" . 'PEC_ERROR: Could not execute query. Error 101.' . "\n");
        }else {
            $result = $this->db->Execute($query_str) or die(
            (C_Utility::is_debug()) ?
                "\n" . 'PEC_DEBUG: C_Database->db_query() - ' . $this->db->ErrorMsg() . "\n" . 'SQL: ' . $query_str :
                "\n" . 'PEC_ERROR: Could not execute query. Error 101.' . "\n");
        }

        $this->result = $result;
        return $result;
    }

    /**
     *  preventing XSS by escape all characters
     */

    private function sanitize($s) {
        return htmlspecialchars($s);
    }

    /**
     * Selects from a result set with limit option
     * Selects from a result set with limit option
     *
     * @param $query_str
     * @param $size
     * @param $starting_row
     * @return mixed
     *
     * @author: phplens
     */
    public function select_limit($query_str, $size, $starting_row)
    {
        $this->db->SetFetchMode(ADODB_FETCH_BOTH);
        $result = $this->db->SelectLimit($query_str, $size, $starting_row) or die(
        (C_Utility::is_debug()) ?
            "\n" . 'PEC_DEBUG: C_Database->select_limit() - ' . $this->db->ErrorMsg() . "\n" :
            "\n" . 'PEC_ERROR: Could not execute query. Error 102' . "\n");

        $this->result = $result;
        return $result;
    }

    /**
     * helper function to get array from select_limit function
     * Desc: helper function to get array from select_limit function
     *
     * @param $query_str
     * @param $size
     * @param $starting_row
     * @return mixed
     *
     * @author: phplens
     */
    public function select_limit_array($query_str, $size, $starting_row)
    {
        $result = $this->select_limit($query_str, $size, $starting_row);
        $resultArray = $result->GetArray();

        $this->result = $resultArray;
        return $resultArray;
    }

    /**
     * fetch a SINGLE record from database as row
     * Desc: fetch a SINGLE record from database as row
     * Note: the parameter is passed as reference
     *
     * @param $result
     * @return mixed
     * @author phplens
     */
    public function fetch_row(&$result)
    {
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if (!$result->EOF) {
            $rs = $result->fields;
            $result->MoveNext();
            return $rs;
        }
    }

    /**
     * Desc: fetch a SINGLE record from database as array
     * Note: the parameter is passed as reference
     *
     * @param $result
     * @return mixed
     *
     * @author phplens
     */
    public function fetch_array(&$result)
    {
        $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
        if (!$result->EOF) {
            $rs = $result->fields;
            $result->MoveNext();
            return $rs;
        }
    }

    /**
     * Desc: fetch a SINGLE record from database as associative array
     * Note: the parameter is passed as reference
     *
     * @param $result
     * @return mixed
     *
     * @author phplens
     */
    public function fetch_array_assoc(&$result)
    {
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if (!$result->EOF) {
            $rs = $result->fields;
            $result->MoveNext();
            return $rs;
        }
    }

    /**
     * number of rows query returned
     * Desc: number of rows query returned
     * @param $result
     * @return mixed
     *
     * @author phplens
     */
    public function num_rows($result)
    {
        return $result->RecordCount();
    }

    /**
     * Helper function. query then, fetch the FIRST record from database as associative array
     *
     * Desc: helper function. query then, fetch the FIRST record from database as associative array
     * @param $query_str
     * @return mixed
     * @author phplens
     */
    public function query_then_fetch_array_first($query_str)
    {
        $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
        $result = $this->db->Execute($query_str) or die('PEC_ERROR: query_then_fetch_array_first() - ' . $this->db->ErrorMsg());
        // die("Error: Could not execute query $query_str");
        if (!$result->EOF) {
            $rs = $result->fields;
            $result->MoveNext();
            return $rs;
        }
    }

    /**
     * A specific field name (column name) with that index in the recordset
     * Desc: a specific field name (column name) with that index in the recordset
     *
     * @param $result
     * @param $index
     * @return string
     *
     * @author phplens
     */
    public function field_name($result, $index)
    {
        $obj_field = new ADOFieldObject();
        $obj_field = $result->FetchField($index);
        return isset($obj_field->name) ? $obj_field->name : "";
    }

    /**
     * The type of a specific field name (column name) with that index in the recordset
     * Desc: the type of a specific field name (column name) with that index in the recordset
     *
     * @param $result
     * @param $index
     * @return string
     *
     * @author phplens
     */
    public function field_nativetype($result, $index)
    {
        $obj_field = new ADOFieldObject();
        $obj_field = $result->FetchField($index);
        return isset($obj_field->type) ? $obj_field->type : "";
    }

    /**
     * Return corresponding field index by field name
     * Desc: return corresponding field index by field name
     *
     * @param $result
     * @param $field_name
     * @return int
     *
     * @author phplens
     */
    public function field_index($result, $field_name)
    {
        $field_count = $this->num_fields($result);
        $i = 0;
        for ($i = 0; $i < $field_count; $i++) {
            if ($field_name == $this->field_name($result, $i))
                return $i;
        }
        return -1;
    }

}