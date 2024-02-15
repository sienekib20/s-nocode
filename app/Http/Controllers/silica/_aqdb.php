<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Database\Factory\DB;
use Sienekib\Mehael\Support\Auth;

class _aqdb extends \mysqli {

// single instance of self shared among all instances
    private static $instance = null;
// db connection config vars
//    private $user = "silicauser";
    private $user = "";
    private $pass = "";
    private $dbName = "";
    private $dbHost = "";
    
    
// This method must be static, and must return an instance of the object if the object
// does not already exist.

    public static function getInstance() {
        //  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

// The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
// thus eliminating the possibility of duplicate objects.

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

// private constructor
    private function __construct() {

        $this->user = env('SDB_USERNAME');
        $this->pass = env('SDB_PASSWORD');
        $this->dbName = env('SDB_DATABASE');
        $this->dbHost = env('SDB_HOST');

        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);

        //  parent::__construct($this->dbHost, ini_get("mysql.default.user"), ini_get("mysql.default.password"), $this->dbName);

        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        
    }

    public function converteArrayParaUtf8($result) {
        array_walk_recursive($result, function(&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $result;
    }

    public function create_tables() {
        $this->supplierTable();
        $this->set_auto_increment("company", "id", "5001");
        $this->set_auto_increment("product", "id", "300001");
        $this->set_auto_increment("supplier", "id", "1000");
        return null;
    }

    public function set_auto_increment($table, $id, $value) {
        $sql = "SELECT AUTO_INCREMENT "
                . "FROM information_schema.TABLES "
                . "WHERE TABLE_SCHEMA = 'gmeao_silicadb' AND TABLE_NAME = '" . $table . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
        }
        $actualValue = $row[0];

        $result = $this->query("SELECT MAX(" . $id . ") "
                . "FROM " . $table . " ");
        $row = $result->fetch_row();
        $maxValue = $row[0];

        $autoProposed = $value > $maxValue ? $value : $maxValue;

        if ($autoProposed > $actualValue) {
            $this->query("ALTER TABLE " . $table . " AUTO_INCREMENT = " . $autoProposed . ";");
            return 1;
        } else {
            return 0;
        }
    }

    private function validateDuplicateElement($companyId, $elementId, $fldId,
            $valueToCheck, $tblToCheck, $fldToCheck) {

        $companyId = $this->real_escape_string($companyId);
        $elementId = $this->real_escape_string($elementId);
        $fldId = $this->real_escape_string($fldId);
        $valueToCheck = $this->real_escape_string($valueToCheck);
        $tblToCheck = $this->real_escape_string($tblToCheck);
        $fldToCheck = $this->real_escape_string($fldToCheck);

        $where = "";
        if ($companyId != -1) {
            $where = " companyid = '" . $companyId . "' AND ";
        }

        $sql = "SELECT " . $fldToCheck . " \n"
                . "FROM " . $tblToCheck . " \n"
                . "WHERE " . $where . " (CAST(" . $fldToCheck . " AS CHAR)) <> '' "
                . " AND " . $fldToCheck . " = '" . $valueToCheck . "'  "
                . " AND (CAST(" . $fldId . " AS CHAR)) <> '" . $elementId . "' \n"
                . "LIMIT 1;";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            mysqli_free_result($result);
            return 1;
        } else {
            return 0;
        }
    }

    private function validateSession($sessionId) {

        $sessionId = $this->real_escape_string($sessionId);

        $sql = "SELECT id \n"
                . "FROM systemuserloged \n"
                . "WHERE sessionid = '" . $sessionId . "' AND  (DATEDIFF(NOW(), sessiontime)) < 1 \n"
                . "LIMIT 1;";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            mysqli_free_result($result);
            return 1;
        }
        return 0;
    }

    private function checkCustomerInDataBase($customerId) {

        $customerId = $this->real_escape_string($customerId);

        $sql = "SELECT customerid \n"
                . "FROM customer \n"
                . "WHERE customerid = '" . $customerId . "' \n"
                . "LIMIT 1;";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            mysqli_free_result($result);
            return 1;
        } else {
            return 0;
        }
    }

    private function checkOriginationIsCanceled($originationOn) {

        $originationOn = $this->real_escape_string($originationOn);
        $invoiceType = substr($originationOn, 0, 2);

        $table = "invoice";
        if (($invoiceType == "PP") || ($invoiceType == "OR") || ($invoiceType == "NE")) {
            $table = "workingdocument";
        }

        $sql = "SELECT UPPER(invoicestatus) AS invoicestatus \n"
                . "FROM " . $table . " \n"
                . "WHERE invoicenumber = '" . $originationOn . "' \n"
                . "LIMIT 1;";

        $result = $this->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $status = $row['invoicestatus'];
            if ($status != "A") {
                return 0;
            }
        }
        return 1;
    }

    public function getAutocompleteField($companyId, $table, $field) {

        $companyId = $this->real_escape_string($companyId);
        $table = $this->real_escape_string($table);
        $field = $this->real_escape_string($field);

        $where = "";
        if ($companyId != -1) {
            $where = "AND companyid = '" . $companyId . "' ";
        }

        $sql = "SELECT DISTINCT " . $field . " \n"
                . "FROM " . $table . " "
                . "WHERE id > 0  " . $where
                . "ORDER BY " . $field . ";";
        $result = $this->query($sql);

        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= "-/-" . $row[$field];
        }
        $str .= "";
        mysqli_free_result($result);
        return $str;
    }

    public function getRandomString($stringSize) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $stringSize; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public function check_credentials($name, $password, $whoCall, $sessionId) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $whoCall = $this->real_escape_string($whoCall);
        // $openUser = $this->real_escape_string($openUser);
        $browser = $_SERVER['HTTP_USER_AGENT'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        $sql = "SELECT 1 AS status, userid, passwordsalt, password, \n"
                . "(CASE WHEN needchangepass = 1 THEN 1 ELSE 0 END) AS defaultPass \n"
                . names("directoruser")
                . names("administratoruser")
                . "FROM systemuser AS SU \n"
                . "WHERE (BINARY username = '" . $name . "' OR BINARY email = '" . $name . "' ) AND openuser = 0  ";
        
        $result = $this->query($sql);         
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);

            $salt = $row['passwordsalt'];
            $passwordNW = $salt . $password;
            $decrytedPass = _rsaCryptGeneral::getInstance()->decryptRSA_general($row['password']);

            if ($decrytedPass == $passwordNW) {
                $userId = $row['userid'];
                //Auto logout all
                $sql = "UPDATE systemuserloged SET \n"
                        . "onlinestatus = 0, \n"
                        . "sessionouttime = NOW() \n"
                        . "WHERE TIMESTAMPDIFF(MINUTE, sessiontime, sessionouttime) <= 1 AND onlinestatus = 1 AND \n"
                        . "CAST(sessiontime AS DATE) < CAST(NOW() AS DATE); \n";
                //Auto logout
                $sql .= "UPDATE systemuserloged SET \n"
                        . "onlinestatus = 0 \n"
                        . "WHERE userid = '" . $userId . "'; \n";

                $sql .= "INSERT INTO systemuserloged (\n"
                        . "browser, idaddress, sessionid, sessiontime, \n"
                        . "userid, onlinestatus) \n"
                        . "SELECT '" . $browser . "', '" . $ip_address . "', '" . $sessionId . "', NOW(), \n"
                        . "'" . $userId . "', '1' \n"
                        . "FROM insert_table; \n";

                if ($whoCall == "EMAIL") {
                    $sql .= "UPDATE systemuser SET \n"
                            . "needconfirmemail = 0 \n"
                            . "WHERE userid = '" . $userId . "'; \n";
                }
                //Delete old register
                $firstDay = date("Y-m-d", strtotime("first day of this month"));
                $sql .= "DELETE FROM systemuserloged \n"
                        . "WHERE CAST(sessiontime AS DATE) < DATE_ADD(CAST('" . $firstDay . "' AS DATE), INTERVAL -90 DAY); \n";

                $this->multi_query($sql);

                $row['passwordsalt'] = "";
                $row['password'] = "";
                return $row;
            }
        }
        return array("status" => 0, "id" => -1, "fullName" => "", "defaultPass" => 0,
            "coordinatoruser" => 0, "manageruser" => 0, "directoruser" => 0, "administratoruser" => 0);
    }

    public function systemUserNowState($sessionId) {
        $sessionId = $this->real_escape_string($sessionId);
        $browser = $_SERVER['HTTP_USER_AGENT'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        function namesNew($field) {
            return ", (SELECT U.userfullname FROM systemuser AS U WHERE U.userid = SU." . $field . " LIMIT 1) AS " . $field . "Name \n";
        }

        function userMunicipProv($municProv) {
            return "(COALESCE((SELECT CD." . $municProv . " FROM companydependency AS CD WHERE CD.id = SU.workplaceid LIMIT 1), -1)) ";
        }

        $sql = "SELECT 1 AS status, userid, email, userfullname, accountstatus, needconfirmemail, billingprofile, "
                . "(COALESCE((SELECT CD.coordinatoruser FROM companydependency AS CD WHERE CD.id = SU.workplaceid LIMIT 1), -1)) AS coordinatoruser, \n"
                . "manageruser, \n"
                . "directoruser, directorgeneraluser, administratoruser, partnershipuser, "
                . "openuser, companyid, workplaceid, permissionlevel, photo, \n"
                . userMunicipProv("designation") . " AS dependency, \n"
                . userMunicipProv("municipalityid") . " AS municipalityid, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = " . userMunicipProv("municipalityid") . " LIMIT 1) AS municipalityname, \n"
                . userMunicipProv("provinceid") . " AS provinceid, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = " . userMunicipProv("provinceid") . ") AS provinceName, \n"
                . "(SELECT PS.id FROM productstock AS PS WHERE PS.dependencyid = SU.workplaceid AND billingstock = 1 LIMIT 1) AS billingStockId, \n"
                . "(CASE WHEN (password = '1234' OR username = companyid) then 1 ELSE 0 END) AS defaultPass \n"
                . "FROM systemuser AS SU \n"
                . "WHERE userid = (SELECT userid FROM systemuserloged WHERE browser = '" . $browser . "' AND idaddress = '" . $ip_address . "' AND sessionid = '" . $sessionId . "');";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['photo'] == "") {
                $row['photo'] = "0000_default.png";
            }
            $row['photo'] = systemUserDocsPath() . $row['photo'];
            $row = $this->converteArrayParaUtf8($row);
            return $row;
        } else {
// $sql = "DELETE FROM systemuserloged WHERE '" . $browser . "' AND idaddress = '" . $ip_address . "; ";
            $this->query($sql);
            return array("status" => 0, "id" => -1, "fullName" => "", "defaultPass" => 0,
                "coordinatoruser" => 0, "manageruser" => 0, "directoruser" => 0, "administratoruser" => 0);
        }
    }

    public function systemUserLogout($sessionId) {

        $sessionId = $this->real_escape_string($sessionId);
        $sql = "UPDATE systemuserloged SET \n"
                . "onlinestatus = '0', "
                . "sessionouttime = NOW()  \n"
                . "WHERE sessionid = '" . $sessionId . "';";

        $this->query($sql);
    }

    public function companyGetInfo($companyId) {
        $companyId = $this->real_escape_string($companyId);
        $sql = "SELECT *, "
                . "(SELECT C.country FROM country AS C WHERE C.id = CO.documentcountry LIMIT 1) AS countryName, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = documentprovince LIMIT 1) AS provinceName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = documentmunicipality LIMIT 1) AS municipalityName \n"
                . "FROM company AS CO \n"
                . "WHERE companyid = '" . $companyId . "' \n"
                . "LIMIT 1";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $row['logopath'] = companyDocsPath();
            $row = $this->converteArrayParaUtf8($row);
            return $row;
        }
    }

    public function companyNew($userId, $companyName, $request) {
        $userId = $this->real_escape_string($userId);
        $companyName = $this->real_escape_string($companyName);
        $request = $this->real_escape_string($request);
        $sql = "INSERT INTO company (companyname, entryuser, request) "
                . "SELECT '" . $companyName . "', '" . $userId . "', '" . $request . "'  "
                . "FROM insert_table ;";
        $this->query($sql);

        $sql = "SELECT MAX(id) FROM company;";
        $companyId = 0;
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $companyId = $row[0];
            mysqli_free_result($result);
        }

        return $companyId;
    }

    public function companySaveInfo($arrayCompanyInfo, $sender, $userId, $request) {
        $createAdmin = 0;
        $companyId = $this->real_escape_string($arrayCompanyInfo['companyId']);
        $companyName = $this->real_escape_string($arrayCompanyInfo['companyName']);
        $companyTaxId = $this->real_escape_string($arrayCompanyInfo['companyTaxId']);
        $companyType = $this->real_escape_string($arrayCompanyInfo['companyType']);
        $userId = $this->real_escape_string($userId);
        $request = $this->real_escape_string($request);
        if ($sender == "UDACONCAD") {
            $gmecustomerid = $this->real_escape_string($arrayCompanyInfo['gmecustomerid']);
            $fundid = $this->real_escape_string($arrayCompanyInfo['fundid']);
            $systemtype = $this->real_escape_string($arrayCompanyInfo['systemtype']);
            $personname = $this->real_escape_string($arrayCompanyInfo['personname']);
            $personfunction = $this->real_escape_string($arrayCompanyInfo['personfunction']);
            $identificationdoc = $this->real_escape_string($arrayCompanyInfo['identificationdoc']);
            $identificationnumber = $this->real_escape_string($arrayCompanyInfo['identificationnumber']);
            $personnationality = $this->real_escape_string($arrayCompanyInfo['personnationality']);
            $personphone1 = $this->real_escape_string($arrayCompanyInfo['personphone1']);
            $personphone2 = $this->real_escape_string($arrayCompanyInfo['personphone2']);
            $personemail = $this->real_escape_string($arrayCompanyInfo['personemail']);

            if ($this->validateDuplicateElement(-1, $companyId, "id", $companyName, "company", "companyname") == 1) {
                return json_encode(array("status" => 0, "msg" => 'Nome já cadastrada', "companyId" => $companyId));
            }

            if ($this->validateDuplicateElement(-1, $companyId, "id", $companyTaxId, "company", "companytaxid") == 1) {
                return json_encode(array("status" => 0, "msg" => 'NIF já cadastrado.', "companyId" => $companyId));
            }
        }
        if ($sender == "COMCAD") {
            $fiscalYear = $this->real_escape_string($arrayCompanyInfo['fiscalYear']);
            $ivaGroup = $this->real_escape_string($arrayCompanyInfo['ivaGroup']);
            $irtGroup = $this->real_escape_string($arrayCompanyInfo['irtGroup']);
            $iiGroup = $this->real_escape_string($arrayCompanyInfo['iiGroup']);
            $contabilityStatus = $this->real_escape_string($arrayCompanyInfo['contabilityStatus']);
        }
        $billingCountry = $this->real_escape_string($arrayCompanyInfo['billingCountry']);
        $billingProvince = $this->real_escape_string($arrayCompanyInfo['billingProvince']);
        $billingMunicipality = $this->real_escape_string($arrayCompanyInfo['billingMunicipality']);
        $billingCity = $this->real_escape_string($arrayCompanyInfo['billingCity']);
        $billingDistrict = $this->real_escape_string($arrayCompanyInfo['billingDistrict']);
        $billingComuna = $this->real_escape_string($arrayCompanyInfo['billingComuna']);
        $billingNeiborhood = $this->real_escape_string($arrayCompanyInfo['billingNeiborhood']);
        $billingStreetName = $this->real_escape_string($arrayCompanyInfo['billingStreetName']);
        $billingBuildNumber = $this->real_escape_string($arrayCompanyInfo['billingBuildNumber']);
        $billingPostalCode = $this->real_escape_string($arrayCompanyInfo['billingPostalCode']);
        $billingPhone1 = $this->real_escape_string($arrayCompanyInfo['billingPhone1']);
        $billingPhone2 = $this->real_escape_string($arrayCompanyInfo['billingPhone2']);
        $billingPhone3 = $this->real_escape_string($arrayCompanyInfo['billingPhone3']);
        $billingEmail = $this->real_escape_string($arrayCompanyInfo['billingEmail']);
        $billingWebsite = $this->real_escape_string($arrayCompanyInfo['billingWebsite']);
        $logofilename = $this->real_escape_string($arrayCompanyInfo['logofilename']);

        if (!is_numeric($companyId)) {
            $createAdmin = 1;
            $companyId = $this->companyNew($userId, $companyName, $request);
        }

        $sql = "UPDATE company SET \n"
                . "companyid = id, "
                . "companyname = '" . $companyName . "', "
                . "companytaxid = '" . $companyTaxId . "', "
                . "companytype = '" . $companyType . "', \n";
        if ($sender == "UDACONCAD") {
            $sql .= "gmecustomerid = '" . $gmecustomerid . "', "
                    . "fundid = '" . $fundid . "', "
                    . "systemtype = '" . $systemtype . "', "
                    . "personname = '" . $personname . "', "
                    . "personfunction = '" . $personfunction . "', "
                    . "identificationdoc = '" . $identificationdoc . "', "
                    . "identificationnumber = '" . $identificationnumber . "', \n"
                    . "personnationality = '" . $personnationality . "', "
                    . "personphone1 = '" . $personphone1 . "', "
                    . "personphone2 = '" . $personphone2 . "', "
                    . "personemail = '" . $personemail . "',  \n";
        }
        if ($sender == "COMCAD") {
            $sql .= "fiscalyear = '" . $fiscalYear . "', "
                    . "ivagroup = '" . $ivaGroup . "', "
                    . "irtgroup = '" . $irtGroup . "', "
                    . "iigroup = '" . $iiGroup . "', "
                    . "contabilitystatus = '" . $contabilityStatus . "', \n";
        }
        $sql .= "billingcountry = '" . $billingCountry . "', "
                . "billingprovince = '" . $billingProvince . "', "
                . "billingmunicipality = '" . $billingMunicipality . "', "
                . "billingcity = '" . $billingCity . "', "
                . "billingdistrict = '" . $billingDistrict . "', "
                . "billingcomuna = '" . $billingComuna . "', \n"
                . "billingneiborhood = '" . $billingNeiborhood . "', "
                . "billingstreetname = '" . $billingStreetName . "', "
                . "billingbuildingnumber = '" . $billingBuildNumber . "', \n"
                . "billingpostalcode = '" . $billingPostalCode . "', "
                . "billingtelephone1 = '" . $billingPhone1 . "', "
                . "billingtelephone2 = '" . $billingPhone2 . "', "
                . "billingtelephone3 = '" . $billingPhone3 . "', "
                . "billingemail = '" . $billingEmail . "', "
                . "billingwebsite = '" . $billingWebsite . "', \n";
        if (($sender == "COMCAD") || ($createAdmin == 1)) {
            $sql .= "logofilename = '" . $logofilename . "', \n";
        }
        $sql .= "statususer = '" . $userId . "', "
                . "statusdate = NOW()  \n"
                . "WHERE id = '" . $companyId . "'; \n";
        if ($createAdmin == 1) {

            $sql .= "INSERT INTO customer (companyid, customerid, companyname) \n"
                    . "SELECT '" . $companyId . "', '9999', 'Consumidor final'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM customer WHERE customerid = '9999' AND companyid = '" . $companyId . "'); \n";

            $sql .= "INSERT INTO productstock (companyid, desigination, address, billingstock) \n"
                    . "SELECT '" . $companyId . "', 'Estoque de facturação', 'Loja local', 1 \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM productstock WHERE companyid = '" . $companyId . "'); \n";

            $sql .= "UPDATE customer SET contractorid = '" . $companyId . "' WHERE customerid = '" . $gmecustomerid . "'; \n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => 'Actualizado com sucesso', "companyId" => $companyId));
    }

    public function companySelfSaveInfo($arrayCompanyInfo, $silicaUser) {

        $companyId = $this->real_escape_string($arrayCompanyInfo['companyId']);
        $companyName = $this->real_escape_string($arrayCompanyInfo['companyName']);
        $companyTaxId = $this->real_escape_string($arrayCompanyInfo['companyTaxId']);
        $companyType = $this->real_escape_string($arrayCompanyInfo['companyType']);
        $businessline = $this->real_escape_string($arrayCompanyInfo['businessline']);
        $billingProvince = $this->real_escape_string($arrayCompanyInfo['billingProvince']);
        $billingMunicipality = $this->real_escape_string($arrayCompanyInfo['billingMunicipality']);
        $billingEmail = $this->real_escape_string($arrayCompanyInfo['billingEmail']);
        $silicaUser = $this->real_escape_string($silicaUser);
        $customerId = $this->customerNewId(5000, $silicaUser, 0, 0, 0, 0, 20);

//Register as customer
        $sql = "UPDATE customer SET "
                . "customertype = '" . $companyType . "', "
                . "customertaxid = '" . $companyTaxId . "', "
                . "companyname = '" . $companyName . "', "
                . "billingprovince = '" . $billingProvince . "', "
                . "billingmunicipality = '" . $billingMunicipality . "', "
                . "email = '" . $billingEmail . "', "
                . "registerid = '" . $companyId . "', "
                . "contractorid = '" . $companyId . "' \n"
                . "WHERE customerid = '" . $customerId . "'; \n";
        //Update company info
        $sql .= "UPDATE company SET \n"
                . "companyid = id, "
                . "gmecustomerid = '" . $customerId . "', "
                . "companyname = '" . $companyName . "', "
                . "businessname = '" . $companyName . "', "
                . "companytaxid = '" . $companyTaxId . "', "
                . "companytype = '" . $companyType . "', "
                . "businessline = '" . $businessline . "', "
                . "billingprovince = '" . $billingProvince . "', "
                . "billingmunicipality = '" . $billingMunicipality . "' \n"
                . "WHERE id = '" . $companyId . "'; \n";
        //Update Dependency
        $sql .= "UPDATE companydependency SET "
                . "provinceid = '" . $billingProvince . "', "
                . "municipalityid = '" . $billingMunicipality . "' \n"
                . "WHERE companyid = '" . $companyId . "'; \n";

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => 'Actualizado com sucesso', "nus" => $companyId, "cid" => $customerId));
    }

    public function companyGetCustomerIdByContractor($contractorId) {
        $contractorId = $this->real_escape_string($contractorId);

        $sql = "SELECT *, 1 AS status \n"
                . "FROM customer \n"
                . "WHERE contractorid = '" . $contractorId . "' \n"
                . "LIMIT 1";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $row = $this->converteArrayParaUtf8($row);
            return $row;
        }
    }

    public function companyDependencyNew($companyId, $provinceId, $municipalityId, $designation) {
        $companyId = $this->real_escape_string($companyId);
        $provinceId = $this->real_escape_string($provinceId);
        $municipalityId = $this->real_escape_string($municipalityId);
        $designation = $this->real_escape_string($designation);

        $sql = " INSERT INTO companydependency (companyid, provinceid, municipalityid, designation) \n"
                . "SELECT '" . $companyId . "', '" . $provinceId . "', '" . $municipalityId . "', '" . $designation . "'  "
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM companydependency   "
                . "WHERE companyid = '" . $companyId . "' AND provinceid = '" . $provinceId . "' AND municipalityid = '" . $municipalityId . "' AND designation = '" . $designation . "'); \n";
        $this->query($sql);
        $sql = "SELECT id FROM companydependency WHERE companyid = '" . $companyId . "' AND provinceid = '" . $provinceId . "' AND municipalityid = '" . $municipalityId . "' AND designation = '" . $designation . "'; \n";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function companyDependencySave($arrayDependency) {

        $companyId = $this->real_escape_string($arrayDependency['companyid']);
        $dependencyId = $this->real_escape_string($arrayDependency['dependencyid']);
        $provinceId = $this->real_escape_string($arrayDependency['provinceid']);
        $municipalityId = $this->real_escape_string($arrayDependency['municipalityid']);
        $designation = $this->real_escape_string($arrayDependency['designation']);
        $coordinatorUser = $this->real_escape_string($arrayDependency['coordinatoruser']);
        $printlayout = $this->real_escape_string($arrayDependency['printlayout']);
        $address = $this->real_escape_string($arrayDependency['address']);
        $phone1 = $this->real_escape_string($arrayDependency['phone1']);
        $phone2 = $this->real_escape_string($arrayDependency['phone2']);
        $phone3 = $this->real_escape_string($arrayDependency['phone3']);
        $email = $this->real_escape_string($arrayDependency['email']);
        $website = $this->real_escape_string($arrayDependency['website']);
        $facebook = $this->real_escape_string($arrayDependency['facebook']);
        $whatsapp = $this->real_escape_string($arrayDependency['whatsapp']);
        $slogan = $this->real_escape_string($arrayDependency['slogan']);

        if ($this->validateDuplicateElement($companyId, $dependencyId, "id",
                        $designation, "companydependency", "designation") == 1) {
            return json_encode(array("status" => 0, "msg" => "Nome já cadastrada."));
        }

        if (!is_numeric($dependencyId)) {
            $dependencyId = $this->companyDependencyNew($companyId, $provinceId, $municipalityId, $designation);
        }

        $sql = "UPDATE companydependency SET \n"
                . "provinceid = '" . $provinceId . "', "
                . "municipalityid = '" . $municipalityId . "', "
                . "designation = '" . $designation . "', "
                . "coordinatoruser = '" . $coordinatorUser . "', \n"
                . "printlayout = '" . $printlayout . "', "
                . "address = '" . $address . "', "
                . "phone1 = '" . $phone1 . "', "
                . "phone2 = '" . $phone2 . "', "
                . "phone3 = '" . $phone3 . "', "
                . "email = '" . $email . "', "
                . "website = '" . $website . "', "
                . "facebook = '" . $facebook . "', "
                . "whatsapp = '" . $whatsapp . "', "
                . "slogan = '" . $slogan . "'  \n"
                . "WHERE id = " . $dependencyId . "; ";

        $sql .= "UPDATE systemuser SET manageruser = '" . $coordinatorUser . "' \n"
                . "WHERE companyid = '" . $companyId . "' AND workplaceid = '" . $dependencyId . "'; ";

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Dependência actualizada com sucesso."));
    }

    public function companyDependecyGetList($companyId, $dependencyId, $toPrint = false) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $where = "";

        if ($dependencyId != -1) {
            $where = " AND CD.id = '" . $dependencyId . "' ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = CD.provinceid LIMIT 1) AS province, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CD.municipalityid LIMIT 1) AS municipality, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CD.coordinatoruser LIMIT 1) AS coordinator \n"
                . "FROM companydependency AS CD  \n"
                . "WHERE CD.companyid = '" . $companyId . "'  " . $where
                . "ORDER BY provinceid, municipalityid, designation ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            if ($toPrint) {
                return $row;
            }
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyDependencyDelete($dependecyId) {
        $dependecyId = $this->real_escape_string($dependecyId);
        $sql = "DELETE FROM companydependency "
                . "WHERE id = " . $dependecyId . ";";
        $result = $this->multi_query($sql);
        return 1;
    }

    public function companyProvinceGetList($companyId, $provinceId) {
        $sql = "INSERT INTO companyprovince (companyid, provinceid, provincetype)  \n"
                . "SELECT '" . $companyId . "', id, (CASE WHEN (id = 2 OR id = 10 OR id = 11) THEN '2' ELSE '1' END) \n"
                . "FROM province AS P \n"
                . "WHERE NOT EXISTS (SELECT id FROM companyprovince WHERE companyid = '" . $companyId . "' AND provinceid = P.id);";
        $this->query($sql);

        $provinceId = $this->real_escape_string($provinceId);
        $where = "";

        if ($provinceId != "") {
            $where = " AND id = '" . $provinceId . "' ";
        }
        $sql = "SELECT *, \n"
                . "(CASE WHEN provincetype = 2 THEN 'Região' ELSE 'Província' END) AS type, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = CP.provinceid LIMIT 1) AS province, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CP.manageruser LIMIT 1) AS manager \n"
                . "FROM companyprovince AS CP \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY provinceid ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyProvinceSave($provinceId, $managerUser) {

        $provinceId = $this->real_escape_string($provinceId);
        $managerUser = $this->real_escape_string($managerUser);

        $sql = "UPDATE companyprovince SET \n"
                . "manageruser = '" . $managerUser . "' \n"
                . "WHERE id = " . $provinceId . ";";
        $result = $this->multi_query($sql);
        return 1;
    }

    public function companyHierarchyGetList($companyId) {

        $companyId = $this->real_escape_string($companyId);
        $sql = "SELECT * \n"
                . "FROM companyhierarchy  \n"
                . "WHERE companyid = '" . $companyId . "'; \n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyHierarchySave($companyId, $administratorUser, $directorGeneralUser) {

        $companyId = $this->real_escape_string($companyId);
        $administratorUser = $this->real_escape_string($administratorUser);
        $directorGeneralUser = $this->real_escape_string($directorGeneralUser);

        $sql = "UPDATE companyhierarchy SET \n"
                . "userid = '" . $administratorUser . "' \n"
                . "WHERE companyid = '" . $companyId . "' AND billingprofile = 7; \n";
        $sql .= "UPDATE companyhierarchy SET \n"
                . "userid = '" . $directorGeneralUser . "' \n"
                . "WHERE companyid = '" . $companyId . "' AND billingprofile = 6; \n";

        $sql .= "INSERT INTO companyhierarchy (companyid, billingprofile, userid)  \n"
                . "SELECT '" . $companyId . "', '7', '" . $administratorUser . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM companyhierarchy WHERE companyid = '" . $companyId . "' AND billingprofile = 7); \n";
        $sql .= "INSERT INTO companyhierarchy (companyid, billingprofile, userid)  \n"
                . "SELECT '" . $companyId . "', '6', '" . $directorGeneralUser . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM companyhierarchy WHERE companyid = '" . $companyId . "' AND billingprofile = 6); \n";

        $sql .= "UPDATE systemuser SET \n"
                . "administratoruser = '" . $administratorUser . "' \n"
                . "WHERE companyid = '" . $companyId . "'; \n";
        $sql .= "UPDATE systemuser SET \n"
                . "directorgeneraluser = '" . $directorGeneralUser . "' \n"
                . "WHERE companyid = " . $companyId . "; \n";

        $result = $this->multi_query($sql);
        return 1;
    }

    public function companyPartnershipGet($companyId) {
        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT *, 1 AS status, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CPP.partnerid LIMIT 1) AS userfullname \n"
                . "FROM companypartnershippartner AS CPP \n"
                . "WHERE CPP.partnershipid = (SELECT MAX(CP.id) FROM companypartnership AS CP WHERE CP.companyid = '" . $companyId . "' LIMIT 1)  "
                . "ORDER BY userfullname ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyPartnershipSave($companyId, $arrayPartners) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "INSERT INTO companypartnership (companyid)  \n"
                . "SELECT '" . $companyId . "' \n"
                . "FROM insert_table; \n";
        $this->query($sql);

        $sql = "SELECT MAX(id) \n"
                . "FROM companypartnership \n"
                . "WHERE companyid = '" . $companyId . "';";
        $result = $this->query($sql);

        $partnershipId = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $partnershipId = $row[0];
        } else {
            return json_encode(array("status" => 0, "msg" => 'Não foi possível criar uma nova estrutura societária.'));
        }

        $sql = "";
        foreach ($arrayPartners as $key => $partner) {
            $partnerId = $this->real_escape_string($partner['partnerid']);
            $percentage = $this->real_escape_string($partner['percentage']);

            $sql .= "INSERT INTO companypartnershippartner ("
                    . "partnershipid, partnerid, percentage)  \n"
                    . "SELECT '" . $partnershipId . "', '" . $partnerId . "', '" . $percentage . "'  \n"
                    . "FROM insert_table ; \n";
        }

        $sql .= "UPDATE systemuser SET partnershipuser = '" . $partnershipId . "' \n"
                . "WHERE companyid = '" . $companyId . "'; \n";

        $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => 'Estrutura societária guardada com sucesso.'));
    }

    public function companyValidateNUSforUser($companyId) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT id \n"
                . "FROM company \n"
                . "WHERE companyid = '" . $companyId . "' \n"
                . "LIMIT 1 ;";

        $result = $this->query($sql);
        if ($result->num_rows <= 0) {
            return -1;
        }
        mysqli_free_result($result);

        $sql = "SELECT userid \n"
                . "FROM systemuser AS U \n"
                . "WHERE companyid = '" . $companyId . "' \n"
                . "LIMIT 1 ;";

        $result2 = $this->query($sql);
        if ($result2->num_rows > 0) {
            return 0;
        }
        mysqli_free_result($result2);
        return 1;
    }

    public function companyFundList() {


        $sql = "SELECT * "
                . "FROM fund "
                . "ORDER BY designation;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyFundSaveStatus($fundCode, $fundPerc, $fundValue) {
        $fundCode = $this->real_escape_string($fundCode);
        $fundPerc = $this->real_escape_string($fundPerc);
        $fundValue = $this->real_escape_string($fundValue);

        $sql = "UPDATE fund SET percentsujest = '" . $fundPerc . "', active = " . $fundValue . " "
                . "WHERE fundcode = " . $fundCode . ";";

        return $this->query($sql);
    }

    public function companyWorkScheduleList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);

        $where = "";

        if ($dependencyId != -1) {
            for ($index = 0; $index < 7; $index++) {
                $sql = "INSERT INTO companyworkschedule (\n"
                        . "companyid, dependencyid, weekday, \n"
                        . "starttime, endtime) \n"
                        . "SELECT '" . $companyId . "', '" . $dependencyId . "', '" . $index . "', \n"
                        . "'2021-01-01 07:00', '2021-01-01 18:00' \n"
                        . "FROM insert_table \n"
                        . "WHERE NOT EXISTS(SELECT id FROM companyworkschedule  "
                        . "WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "' AND weekday = '" . $index . "'); \n";
                $this->query($sql);
            }

            $where .= " AND CWS.dependencyid = '" . $dependencyId . "' ";
        }

        $sql = "SELECT CWS.companyid, CWS.dependencyid, CD.designation, CWS.weekday, \n"
                . "CWS.starttime, CWS.endtime, CWS.useworkschedule \n"
                . "FROM companyworkschedule AS CWS, companydependency AS CD \n"
                . "WHERE CWS.companyid = '" . $companyId . "' AND CWS.dependencyid = CD.id " . $where . "  \n"
                . "ORDER BY CWS.dependencyid, weekday; \n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function companyWorkScheduleSave($arrayScheduleInfo, $useWorkSchedule) {

        $useWorkSchedule = $this->real_escape_string($useWorkSchedule);
        $registerNumber = $this->getRandomString(10) . round(microtime(true) * 1000);
        $sql = "";
        foreach ($arrayScheduleInfo as $key => $day) {
            $companyid = $this->real_escape_string($day['companyid']);
            $dependencyid = $this->real_escape_string($day['dependencyid']);
            $weekday = $this->real_escape_string($day['weekday']);
            $starttime = $this->real_escape_string($day['starttime']);
            $endtime = $this->real_escape_string($day['endtime']);

            $sql .= "UPDATE companyworkschedule SET \n"
                    . "starttime = '" . $starttime . "', "
                    . "endtime = '" . $endtime . "', "
                    . "useworkschedule = '" . $useWorkSchedule . "', "
                    . "registernumber = '" . $registerNumber . "' \n"
                    . "WHERE companyid = '" . $companyid . "' AND dependencyid = '" . $dependencyid . "' \n"
                    . "AND weekday = '" . $weekday . "'; \n";
        }

        $sql .= "SELECT registernumber \n"
                . "FROM companyworkschedule \n"
                . "WHERE registernumber = '" . $registerNumber . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar alteração."));
        }
        return json_encode(array("status" => 1, "msg" => "Alteração guardada com sucesso"));
    }

    public function companyWorkScheduleGet($companyId, $dependencyId, $weekday) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $weekday = $this->real_escape_string($weekday);

        $sql = "SELECT * \n"
                . "FROM companyworkschedule  \n"
                . "WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "' \n"
                . "AND weekday = '" . $weekday . "'; \n";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
        return array("starttime" => "2021-01-01 00:01", "endtime" => "2021-01-01 23:59", "useworkschedule" => 0);
    }

    private function set_customerID($serie, $table = "customer", $extra = "") {
        $sequence = 1;
        $fldSerie = "serie";
        $fldSequenc = "sequencenumber";
        if ($extra != "") {
            $fldSerie = "cilserie";
            $fldSequenc = "cilsequencenumber";
        }
        $sql = "SELECT " . $fldSerie . ", MAX(" . $fldSequenc . ") \n"
                . "FROM " . $table . "  \n"
                . "WHERE " . $fldSerie . " = '" . $serie . "';";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $sequence = $row[1] + 1;
        }
        mysqli_free_result($result);
        return array("serie" => $serie, "sequence" => $sequence, "customerId" => $serie . sprintf('%04s', $sequence));
    }

    public function customerNewId($companyid, $userId, $coordinatorUser,
            $managerUser, $directorGeneralUser, $administratorUser, $request) {

        $companyid = $this->real_escape_string($companyid);
        $userId = $this->real_escape_string($userId);
        $coordinatorUser = $this->real_escape_string($coordinatorUser);
        $managerUser = $this->real_escape_string($managerUser);
        $directorGeneralUser = $this->real_escape_string($directorGeneralUser);
        $administratorUser = $this->real_escape_string($administratorUser);
        $request = $this->real_escape_string($request);

        $result = $this->set_customerID();
        $serie = $result['serie'];
        $sequence = $result['sequence'];
        $customerID = $result['customerId'];

        $sql = "INSERT INTO customer (companyid, serie, sequencenumber, customerid, "
                . "entryUser, coordinatorUser, managerUser, "
                . "directorGeneralUser, administratorUser, request) \n"
                . "SELECT '" . $companyid . "', '" . $serie . "', '" . $sequence . "', '" . $customerID . "', "
                . "'" . $userId . "', '" . $coordinatorUser . "', '" . $managerUser . "', "
                . "'" . $directorGeneralUser . "', '" . $administratorUser . "', '" . $request . "'  \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM customer WHERE customerid = " . $customerID . "); ";
        $this->query($sql);

        return $customerID;
    }

    public function customerSaveCustomerInfo($arrayCustomerInfo, $dictonaryCustomer) {

        $companyid = $this->real_escape_string($arrayCustomerInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayCustomerInfo['dependencyid']);
        $linkrequestid = $this->real_escape_string($arrayCustomerInfo['linkrequestid']);

        $serie = $this->real_escape_string($arrayCustomerInfo['serie']);
        $customerid = $this->real_escape_string($arrayCustomerInfo['customerid']);
        $customertype = $this->real_escape_string($arrayCustomerInfo['customertype']);
        $companyname = $this->real_escape_string($arrayCustomerInfo['companyname']);
        $customertaxid = $this->real_escape_string($arrayCustomerInfo['customertaxid']);

        $billingprovince = $this->real_escape_string($arrayCustomerInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arrayCustomerInfo['billingmunicipality']);
        $billingcomuna = $this->real_escape_string($arrayCustomerInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayCustomerInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayCustomerInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayCustomerInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayCustomerInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arrayCustomerInfo['billingpostalcode']);

        $telephone1 = $this->real_escape_string($arrayCustomerInfo['telephone1']);
        $telephone2 = $this->real_escape_string($arrayCustomerInfo['telephone2']);
        $telephone3 = $this->real_escape_string($arrayCustomerInfo['telephone3']);
        $email = $this->real_escape_string($arrayCustomerInfo['email']);

        $userId = $this->real_escape_string($arrayCustomerInfo['userId']);
        $managerUser = $this->real_escape_string($arrayCustomerInfo['managerUser']);
        $request = $this->real_escape_string($arrayCustomerInfo['request']);
        $registerid = $this->real_escape_string($arrayCustomerInfo['registerid']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if ($this->validateDuplicateElement($companyid, $customerid, "customerid",
                        $customertaxid, "customer", "customertaxid") == 1) {
            echo json_encode(array("status" => 0, "msg" => "O BI / NIF já foi cadastrado."));
            return false;
        }

        /*   if ($this->validateDuplicateElement($companyid, $customerid, "customerid",
          $companyname, "customer", "companyname") == 1) {
          echo json_encode(array("status" => 0, "msg" => "Este " . $dictonaryCustomer . " já foi cadastrado."));
          return false;
          } */

        $sql = "";
        if ($customerid == "NOVO*") {
            $result = $this->set_customerID($serie);
            $sequence = $result['sequence'];
            $customerid = $result['customerId'];
            $sql .= "INSERT INTO customer (\n"
                    . "companyid, dependencyid, linkrequestid, \n"
                    . "serie, sequencenumber, customerid, \n"
                    . "entryUser, managerUser, statususer, request, \n"
                    . "registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $linkrequestid . "', \n"
                    . "'" . $serie . "', '" . $sequence . "', '" . $customerid . "', \n"
                    . "'" . $userId . "', '" . $managerUser . "', '" . $userId . "', '" . $request . "', \n"
                    . "'" . $registerNumber . "'  \n"
                    . "FROM insert_table; ";
            $customerid = "(SELECT customerid FROM customer WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $customerid = "'" . $customerid . "'";
        }

        $sql .= "UPDATE customer SET \n"
                . "customertype = " . $customertype . ", "
                . "customertaxid = '" . $customertaxid . "', "
                . "companyname = '" . $companyname . "', "
                . "billingprovince = '" . $billingprovince . "', "
                . "billingmunicipality = '" . $billingmunicipality . "', "
                . "billingcomuna = '" . $billingcomuna . "', "
                . "billingneiborhood = '" . $billingneiborhood . "', "
                . "billingstreetname = '" . $billingstreetname . "', "
                . "billingblock = '" . $billingblock . "', "
                . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
                . "billingpostalcode = '" . $billingpostalcode . "', \n"
                . "telephone1 = '" . $telephone1 . "', "
                . "telephone2 = '" . $telephone2 . "', "
                . "telephone3 = '" . $telephone3 . "', "
                . "email = '" . $email . "', "
                . "registerid = '" . $registerid . "', "
                . "statususer = '" . $userId . "',\n"
                . "statusdate = NOW() "
                . "WHERE customerid = " . $customerid . "; \n";

        $sql .= "SELECT id \n"
                . "FROM customer \n"
                . "WHERE customerid = " . $customerid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o cliente."));
        }
        return json_encode(array("status" => 1, "msg" => "Cliente guardado com sucesso"));
    }

    public function customerSaveCustomerLoteInfo($arrayCustomerInfo) {

        $companyid = $this->real_escape_string($arrayCustomerInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayCustomerInfo['dependencyid']);

        $serie = $this->real_escape_string($arrayCustomerInfo['serie']);
        $sequencenumber = $this->real_escape_string($arrayCustomerInfo['sequencenumber']);
        $customerid = $this->real_escape_string($arrayCustomerInfo['customerid']);
        $customertype = $this->real_escape_string($arrayCustomerInfo['customertype']);
        $companyname = $this->real_escape_string($arrayCustomerInfo['companyname']);
        $customertaxid = $this->real_escape_string($arrayCustomerInfo['customertaxid']);

        $billingprovince = $this->real_escape_string($arrayCustomerInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arrayCustomerInfo['billingmunicipality']);
        $billingcomuna = $this->real_escape_string($arrayCustomerInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayCustomerInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayCustomerInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayCustomerInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayCustomerInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arrayCustomerInfo['billingpostalcode']);

        $telephone1 = $this->real_escape_string($arrayCustomerInfo['telephone1']);
        $telephone2 = $this->real_escape_string($arrayCustomerInfo['telephone2']);
        $telephone3 = $this->real_escape_string($arrayCustomerInfo['telephone3']);
        $email = $this->real_escape_string($arrayCustomerInfo['email']);

        $userId = $this->real_escape_string($arrayCustomerInfo['userId']);
        $managerUser = $this->real_escape_string($arrayCustomerInfo['managerUser']);
        $request = $this->real_escape_string($arrayCustomerInfo['request']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        //Check customer id
        $sql = "SELECT customerid \n"
                . "FROM customer \n"
                . "WHERE customerid = '" . $customerid . "'; ";
        $checkResult = $this->query($sql);
        if ($checkResult->num_rows > 0) {
            mysqli_free_result($checkResult);
            echo json_encode(array("status" => 0, "msg" => "O nº de cliente já foi cadastrado."));
            return false;
        }
        /*  if ($this->validateDuplicateElement($companyid, $customerid, "customerid",
          $customertaxid, "customer", "customertaxid") == 1) {
          echo json_encode(array("status" => 0, "msg" => "O BI / NIF já foi cadastrado."));
          return false;
          } */


        $sql = "INSERT INTO customer (\n"
                . "companyid, dependencyid, \n"
                . "serie, sequencenumber, customerid, \n"
                . "entryUser, managerUser, statususer, request, \n"
                . "registernumber) \n"
                . "SELECT '" . $companyid . "', '" . $dependencyid . "',  \n"
                . "'" . $serie . "', '" . $sequencenumber . "', '" . $customerid . "', \n"
                . "'" . $userId . "', '" . $managerUser . "', '" . $userId . "', '" . $request . "', \n"
                . "'" . $registerNumber . "'  \n"
                . "FROM insert_table; ";
        $customerid = "(SELECT customerid FROM customer WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";


        $sql .= "UPDATE customer SET \n"
                . "customertype = " . $customertype . ", "
                . "customertaxid = '" . $customertaxid . "', "
                . "companyname = '" . $companyname . "', "
                . "billingprovince = '" . $billingprovince . "', "
                . "billingmunicipality = '" . $billingmunicipality . "', "
                . "billingcomuna = '" . $billingcomuna . "', "
                . "billingneiborhood = '" . $billingneiborhood . "', "
                . "billingstreetname = '" . $billingstreetname . "', "
                . "billingblock = '" . $billingblock . "', "
                . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
                . "billingpostalcode = '" . $billingpostalcode . "', \n"
                . "telephone1 = '" . $telephone1 . "', "
                . "telephone2 = '" . $telephone2 . "', "
                . "telephone3 = '" . $telephone3 . "', "
                . "email = '" . $email . "', "
                . "statususer = '" . $userId . "',\n"
                . "statusdate = NOW() "
                . "WHERE customerid = " . $customerid . "; \n";

        $sql .= "SELECT id \n"
                . "FROM customer \n"
                . "WHERE customerid = " . $customerid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o cliente."));
        }
        return json_encode(array("status" => 1, "msg" => "Cliente guardado com sucesso"));
    }

    public function customerDeleteCustomerInfo($customerId, $dictonaryCustomer) {

        $customerId = $this->real_escape_string($customerId);

        $sql = "DELETE FROM customer WHERE customerid = '" . $customerId . "'; ";
        $this->query($sql);

        $sql = "SELECT id FROM customer WHERE customerid = '" . $customerId . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            echo json_encode(array("status" => 0, "msg" => "Não foi possível eliminar else " . $dictonaryCustomer . "."));
            return false;
        }

        echo json_encode(array("status" => 1, "msg" => $dictonaryCustomer . " eliminado com sucesso."));
        return 1;
    }

    public function customerListSearch($arrayFilterInfo) {

        $customerType = $this->real_escape_string($arrayFilterInfo['customerType']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $SearchLimit = $this->real_escape_string($arrayFilterInfo['searchLimit']);
        $searchTag = $this->real_escape_string($arrayFilterInfo['searchTag']);
        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $contractorId = $this->real_escape_string($arrayFilterInfo['contractorId']);
        $portalStatus = $this->real_escape_string($arrayFilterInfo['portalStatus']);

        $where = "";
        if ($customerType != -1) {
            $where = " AND customertype = " . $customerType . " ";
        }
        if ($neiborhood != "") {
            $where = " AND billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($searchTag != "") {
            $where .= " AND (CAST(customerid AS CHAR) = '" . $searchTag . "' OR "
                    . "companyname LIKE '%" . $searchTag . "%' OR "
                    . "customertaxid = '" . $searchTag . "') ";
        }
        if ($contractorId != -1) {
            $where .= " AND contractorid < 1 ";
        }
        if ($portalStatus != -1) {
            $where .= " AND portalstatus <= 0 ";
        }

        $limit = is_numeric($SearchLimit) ? $SearchLimit : 5;

        $sql = "SELECT *, \n"
                . "(SELECT COUNT(CT.id) FROM contract AS CT WHERE CT.customerid = C.customerid LIMIT 1) AS contracts, \n"
                . "(COALESCE((SELECT LR.requesttype FROM linkrequest AS LR WHERE LR.id = C.linkrequestid LIMIT 1), 1)) AS consumertype, \n"
                . "(COALESCE((SELECT LR.cil FROM linkrequest AS LR WHERE LR.id = C.linkrequestid LIMIT 1), '')) AS cil \n"
                . "FROM customer AS C \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY companyname "
                . "LIMIT " . $limit . ";";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function customerAutocompleteList($companyId) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT companyname \n"
                . "FROM customer "
                . "WHERE companyid = '" . $companyId . "' "
                . "ORDER BY companyname;";
        $result = $this->query($sql);

        $arrayCustomer = array();
        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= "-/-" . $row['companyname'];
// array_push($arrayCustomer, json_encode($row));
        }
        $str .= "";
        mysqli_free_result($result);
        return $str;
    }

    public function customers_list($orderBy) {

        $sql = "SELECT customerid, customertype, companyname, customertaxid "
                . "FROM customer "
                . "ORDER BY companyname "
                . "LIMIT 5;";
        return $this->query($sql);
    }

    public function customerGetCustomerList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $listType = $this->real_escape_string($arrayFilterInfo['listType']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $customerType = $this->real_escape_string($arrayFilterInfo['customerType']);
        $customerId = $this->real_escape_string($arrayFilterInfo['customerId']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $fldAmount = "(0)";
        $where = "";
        $orderBy = "C.companyname ";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND C.dependencyid = '" . $dependencyId . "' ";
        }
        if ($customerType != -1) {
            $where .= " AND C.customertype = '" . $customerType . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND C.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND C.billingneiborhood = '" . $neiborhood . "' ";
        }

        if ($listType == 3) {
            $dates = "";
            if ($filterByDate == 1) {
                $dates = " AND (CAST(I.invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
            }
            $fldAmount = "(COALESCE((SELECT SUM(I.totalInvoice) FROM invoice AS I WHERE I.customerid = C.customerid AND UPPER(I.invoicestatus) != 'A'  "
                    . " ), 0)) ";

            $orderBy = "totalamount DESC, C.companyname ASC";
        } else {
            if ($filterByDate == 1) {
                $where .= "AND (CAST(C.entryDate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n";
            }
        }

        if ($customerId != -1) {
            $where = " AND C.customerid = '" . $customerId . "' ";
        } else {
            $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "C.request, C.statususer, "
                . "(COALESCE((SELECT COUNT(CT.id) FROM contract AS CT WHERE CT.customerid = C.customerid LIMIT 1), 0)) AS contracts, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = C.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = C.billingmunicipality LIMIT 1) AS billingMunicipality, \n"
                . "C.customerid, C.companyname, C.customertaxid, C.customertype,  C.statusdate,  \n"
                . "C.billingmunicipality, C.billingcomuna, C.billingstreetname, C.billingblock, \n"
                . "C.billingbuildingnumber, C.billingpostalcode, "
                . "C.billingneiborhood, C.telephone1, C.telephone2, C.telephone3, C.email, \n"
                . $fldAmount . " AS totalamount";

        if ($onlynumber == 1) {
            $fields = " COUNT(C.id), (0) AS totalamount ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM customer AS C \n"
                . "WHERE C.companyid = '" . $companyId . "'  " . $where
                . "ORDER BY " . $orderBy . " \n"
                . $limit
                . "; ";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerHistoricGet($companyId, $customerId) {

        $companyId = $this->real_escape_string($companyId);
        $customerId = $this->real_escape_string($customerId);

        function strHistoric($order, $table, $company, $customer) {
            return "SELECT (" . $order . ") AS docOrder, invoicetype, invoicenumber, invoicestatus, invoiceStatusDate, Sourceidstatus, "
                    . "invoicedate, sourceid, shelflife, reference, request, totalInvoice, paymentamount, \n"
                    . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName \n"
                    . "FROM " . $table . " AS I  \n"
                    . "WHERE companyid = '" . $company . "' AND customerid = '" . $customer . "' \n"
                    . "AND invoicestatus != 'A' \n";
        }

        $sql = strHistoric(1, "workingdocument", $companyId, $customerId)
                . "UNION \n"
                . strHistoric(2, "invoice", $companyId, $customerId)
                . "ORDER BY docOrder, invoicetype, invoicedate; ";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function customerBadPayerList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $countryId = $this->real_escape_string($arrayFilterInfo['countryId']);
        $provinceId = $this->real_escape_string($arrayFilterInfo['provinceId']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        if ($countryId != -1) {
            $where .= " AND C.billingcountry = '" . $countryId . "' ";
        }
        if ($provinceId != -1) {
            $where .= " AND C.billingprovince = '" . $provinceId . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND C.billingmunicipality = '" . $municipalityId . "' ";
        }

        $fldAmount = "(COALESCE((SELECT SUM(I.paymentamount - I.realpaymentamount) FROM invoicepaymentschedule AS I WHERE I.customerid = C.customerid   "
                . "AND ((I.paymentamount - I.realpaymentamount) > 0)), 0)) ";
        $orderBy = "totalamount DESC, C.companyname ASC";

        $fields = "C.request, C.entryUser, "
                . "C.customerid, C.companyname, C.customertaxid, C.customertype,  C.entryDate,  \n"
                . "C.customeradditionalreference, C.customeradditionalnumber, C.telephone1, C.telephone2, C.telephone3, C.email, \n"
                . $fldAmount . " AS totalamount ";

        if ($onlynumber == 1) {
            $fields = " COUNT(C.id) ";
            $orderBy = "1";
            $limit = "";
        }


        $sql = "SELECT " . $fields . " \n"
                . "FROM customer AS C \n"
                . "WHERE C.companyid = '" . $companyId . "' AND C.customerid != 9999  " . $where . " AND (" . $fldAmount . " > 0) \n"
                . "ORDER BY " . $orderBy . " \n"
                . $limit
                . "; ";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerGetInfo($customerId) {
        $customerId = $this->real_escape_string($customerId);
        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = C.entryUser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = C.statususer LIMIT 1) AS operatorName, \n"
                . "(COALESCE((SELECT SUM(CA.amount) FROM customeraccount AS CA WHERE CA.customerid = C.customerid LIMIT 1), 0)) AS creditaccount \n"
                . "FROM customer AS C \n"
                . "WHERE customerid = '" . $customerId . "';";
        return $this->query($sql);
    }

    public function customerGetAdditionalRefList($companyId) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT DISTINCT UPPER(customeradditionalreference) AS customeradditionalreference  \n"
                . "FROM customer AS C \n"
                . "WHERE C.companyid = '" . $companyId . "' AND customeradditionalreference != '' \n"
                . "ORDER BY customeradditionalreference; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerAssignToServiceList($companyId, $sectionId) {

        $companyId = $this->real_escape_string($companyId);
        $sectionId = $this->real_escape_string($sectionId);

        $sql = "SELECT *, CAS.statususer AS statussourceid, \n"
                . "(SELECT PS.chargetype FROM productsection AS PS WHERE PS.id = CAS.productsectionid LIMIT 1) AS chargetype, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CAS.statususer LIMIT 1) AS operatorName \n"
                . "FROM customerassigntoservice CAS, customer AS C \n"
                . "WHERE CAS.customerid = C.customerid AND CAS.companyid = '" . $companyId . "' AND CAS.productsectionid = '" . $sectionId . "'  \n"
                . "ORDER BY CAS.statusdate DESC, C.companyname;";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function customerAssignCustomerToService($companyId, $sectionId, $customerId, $userId) {

        $companyId = $this->real_escape_string($companyId);
        $sectionId = $this->real_escape_string($sectionId);
        $customerId = $this->real_escape_string($customerId);
        $userId = $this->real_escape_string($userId);
        $lastyear = date("Y");
        $lastmonth = date("m");
        $lastday = date("d");

        $sql = "SELECT customerid \n"
                . "FROM customer AS C \n"
                . "WHERE companyid = '" . $companyId . "' "
                . "AND (CAST(customerid AS CHAR) = '" . $customerId . "' OR customertaxid = '" . $customerId . "') \n"
                . "LIMIT 1;";
        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $customerId = $row['customerid'];

            $sql = "SELECT id \n"
                    . "FROM customerassigntoservice  \n"
                    . "WHERE companyid = '" . $companyId . "' AND productsectionid = '" . $sectionId . "' AND customerid = '" . $customerId . "'   \n"
                    . "LIMIT 1";
            $result2 = $this->query($sql);
            if ($result2->num_rows > 0) {
                return json_encode(array("status" => 0, "msg" => "Já vinculado."));
            }

            $sql = "INSERT INTO customerassigntoservice ("
                    . "companyid, customerid, productsectionid, "
                    . "sourceid, assignstatus, statususer, "
                    . "lastyear, lastmonth, lastday) \n"
                    . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $sectionId . "', "
                    . "'" . $userId . "', 1, '" . $userId . "', "
                    . "'" . $lastyear . "', '" . $lastmonth . "', '" . $lastday . "'  \n"
                    . "FROM insert_table; ";

            $this->multi_query($sql);
            return json_encode(array("status" => 1, "msg" => "Vinculado com sucesso."));
        }
        return json_encode(array("status" => 0, "msg" => "Não encontrado."));
    }

    public function customerUnAssignCustomerToService($companyId, $sectionId, $customerId, $userId, $nextStatus) {

        $companyId = $this->real_escape_string($companyId);
        $sectionId = $this->real_escape_string($sectionId);
        $customerId = $this->real_escape_string($customerId);
        $userId = $this->real_escape_string($userId);
        $nextStatus = $this->real_escape_string($nextStatus);
        $lastyear = date("Y");
        $lastmonth = date("m");
        $lastday = date("d");

        $reAssign = "";
        $msgAssign = "Desvinculado";
        if ($nextStatus == 1) {
            $reAssign = ", " . "lastyear = '" . $lastyear . "', lastmonth = '" . $lastmonth . "', lastday = '" . $lastday . "' \n";
            $msgAssign = "Re-vinculado";
        }

        $sql = "UPDATE customerassigntoservice SET  \n"
                . "assignstatus = '" . $nextStatus . "', statusdate = NOW(), statususer = '" . $userId . "' " . $reAssign
                . "WHERE companyid = '" . $companyId . "' AND customerid = '" . $customerId . "' "
                . "AND productsectionid = '" . $sectionId . "'; ";

        $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => $msgAssign . " com sucesso."));
    }

    public function customerGetPeriodicService($companyId, $customerId) {
        $companyId = $this->real_escape_string($companyId);
        $customerId = $this->real_escape_string($customerId);

        function strComissinField($field, $extraCoalesce = 1, $ivaCoalesce = 0) {
            $coalesce = $extraCoalesce ? "(SELECT " . $field . " FROM productpricepercentsuggest AS PPS WHERE PPS.companyid = PS.companyid LIMIT 1), " : "";
            if ($ivaCoalesce == 1) {
                $coalesce = "(14), ";
            }
            return ", (COALESCE((SELECT " . $field . " FROM productprice AS PP WHERE PP.productid = (SELECT P.id FROM product AS P WHERE CAST(P.productcode AS CHAR) = CONCAT(PS.companyid, PS.id) LIMIT 1) LIMIT 1), "
                    . $coalesce . " 0)) AS " . $field . " \n";
        }

        $strProductExemption = "M02";

        $sql = "SELECT PS.id, PS.section, PS.chargetype, CAS.productsectionid, CAS.lastdate, \n"
                . "PS.chargetype, PS.chargesequence, PS.installmentindicator, PS.managernationaluser, PS.directoruser, PS.photo, \n"
                . "('S') AS producttype, 0 AS productstockactual, ('" . $strProductExemption . "') AS productexemption, 0 AS gmelicense,\n"
                . "(SELECT PE.exemptionreason FROM productexemptioncode AS PE WHERE PE.exemptioncode = '" . $strProductExemption . "' LIMIT 1) AS exemptionreason "
                . strComissinField('warehouseprice', 0) . strComissinField('indirectcost') . strComissinField("commercialcost")
                . strComissinField('estimatedprofit') . strComissinField("unitprice", 0) . strComissinField("silicacomission")
                . strComissinField("sellercomission") . strComissinField("managercomission")
                . strComissinField("fundinvestment") . strComissinField("fundreserve", 0) . strComissinField("fundsocialaction", 0)
                . strComissinField("descount", 0) . strComissinField("iva", 0, 1) . strComissinField("pvp", 0)
                . "FROM customerassigntoservice AS CAS, productsection AS PS  \n"
                . "WHERE CAS.productsectionId = PS.id AND CAS.customerid = '" . $customerId . "' AND CAS.assignstatus = 1  "
                . "AND PS.companyid = '" . $companyId . "' AND PS.chargetype > 1  \n"
                . "ORDER BY section;";
        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerQueryByServiceList($companyId, $sectionId, $reference,
            $additionalReference, $classification1, $classification2,
            $countryId, $provinceId, $municipalityId) {

        $companyId = $this->real_escape_string($companyId);
        $sectionId = $this->real_escape_string($sectionId);
        $reference = $this->real_escape_string($reference);
        $additionalReference = $this->real_escape_string($additionalReference);
        $classification1 = $this->real_escape_string($classification1);
        $classification2 = $this->real_escape_string($classification2);
        $countryId = $this->real_escape_string($countryId);
        $provinceId = $this->real_escape_string($provinceId);
        $municipalityId = $this->real_escape_string($municipalityId);

        $where = "";
        if ($countryId != -1) {
            $where .= " AND C.billingcountry = '" . $countryId . "' ";
        }
        if ($provinceId != -1) {
            $where .= " AND C.billingprovince = '" . $provinceId . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND C.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($additionalReference != "") {
            $where .= " AND C.customeradditionalreference = '" . $additionalReference . "' ";
        }
        if ($classification1 != "") {
            $where .= " AND C.customerclassification1 = '" . $classification1 . "' ";
        }
        if ($classification2 != "") {
            $where .= " AND C.customerclassification1 = '" . $classification2 . "' ";
        }

        function fldReferenceStatus($ref, $fld) {
            $invoiceNumber = " (SELECT IL.xxxxxx FROM invoiceline AS IL WHERE IL.customerid = C.customerid AND IL.productCode = '" . $ref . "' AND UPPER(IL.status)='N' LIMIT 1) ";

            if ($fld == "printnumber") {
                $invoiceNumber = str_replace("xxxxxx", "invoiceNumber", $invoiceNumber);
                return "(SELECT I.printnumber FROM invoice AS I WHERE I.invoicenumber = " . $invoiceNumber . " LIMIT 1) AS printnumber, \n";
            } else {
                $invoiceNumber = str_replace("xxxxxx", $fld, $invoiceNumber);
            }
            if ($fld == "subtotalLine") {
                return "(COALESCE(" . $invoiceNumber . ", 0)) AS subtotalLine, \n";
            } else {
                return $invoiceNumber . " AS " . $fld . ", \n";
            }
        }

        $sql = "SELECT *, CAS.statususer AS statussourceid, (CAST(CAS.entrydate AS DATE))AS assigndate, \n"
                . fldReferenceStatus($reference, "productDescription")
                . fldReferenceStatus($reference, "subtotalLine")
                . fldReferenceStatus($reference, "printnumber")
                . "(SELECT PS.chargetype FROM productsection AS PS WHERE PS.id = CAS.productsectionid LIMIT 1) AS chargetype, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CAS.statususer LIMIT 1) AS operatorName \n"
                . "FROM customerassigntoservice CAS, customer AS C \n"
                . "WHERE CAS.customerid = C.customerid AND CAS.companyid = '" . $companyId . "' AND CAS.productsectionid = '" . $sectionId . "'  \n"
                . "AND assignstatus = 1 "
                . $where
                . "ORDER BY CAS.statusdate DESC, C.companyname;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function customerAccountListGet($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $countryId = $this->real_escape_string($arrayFilterInfo['countryId']);
        $provinceId = $this->real_escape_string($arrayFilterInfo['provinceId']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $additionalReference = $this->real_escape_string($arrayFilterInfo['additionalReference']);
        $customerId = $this->real_escape_string($arrayFilterInfo['customerId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);

        $where = "";
        $limit = "";
        if ($countryId != -1) {
            $where .= " AND C.billingcountry = '" . $countryId . "' ";
        }
        if ($provinceId != -1) {
            $where .= " AND C.billingprovince = '" . $provinceId . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND C.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($additionalReference != "") {
            $where .= " AND C.customeradditionalreference = '" . $additionalReference . "' ";
        }
        if ($customerId != -1) {
            $where = " AND C.customerid = '" . $customerId . "' ";
        } else {
            $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " \n";
        }

        $fields = "*, \n"
                . "(COALESCE((SELECT SUM(CA.balanceamount) FROM customeraccount AS CA WHERE CA.customerid = C.customerid AND CA.companyid = C.companyid LIMIT 1), 0)) AS account ";

        if ($onlynumber == 1) {
            $fields = " COUNT(C.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM customer AS C \n"
                . "WHERE C.companyid = '" . $companyId . "'  " . $where
                . "ORDER BY companyname \n"
                . $limit
                . "; ";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerAccountSingleDetails($companyId, $customerId) {

        $companyId = $this->real_escape_string($companyId);
        $customerId = $this->real_escape_string($customerId);

        $sql = "SELECT * \n"
                . "FROM customeraccount AS CA \n"
                . "WHERE CA.companyid = '" . $companyId . "' AND CA.customerid = '" . $customerId . "'  \n"
                . "ORDER BY id; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerGetCreditAccount($companyId, $customerId) {
        $companyId = $this->real_escape_string($companyId);
        $customerId = $this->real_escape_string($customerId);

        $sql = "SELECT (COALESCE(SUM(balanceamount), 0)) AS credit \n"
                . "FROM customeraccount AS CA \n"
                . "WHERE CA.companyid = '" . $companyId . "' AND CA.customerid = '" . $customerId . "';";

        $result = $this->query($sql);

        return array("credit" => mysqli_fetch_array($result)[0]);
    }

    public function update_user($strUserID, $strUserFullName, $strUserName, $strUserPassWord,
            $strUserEmail, $strUserTitle, $strUserSignCharge) {

        $strUserID = $this->real_escape_string($strUserID);
        $strUserFullName = $this->real_escape_string($strUserFullName);
        $strUserName = $this->real_escape_string($strUserName);
        $strUserPassWord = $this->real_escape_string($strUserPassWord);
        $strUserEmail = $this->real_escape_string($strUserEmail);
        $strUserTitle = $this->real_escape_string($strUserTitle);
        $strUserSignCharge = $this->real_escape_string($strUserSignCharge);

        $sql = "UPDATE systemuser SET "
                . "userfullname = '" . $strUserFullName . "', "
                . "username = '" . $strUserName . "', "
                . "password = '" . $strUserPassWord . "', "
                . "email = '" . $strUserEmail . "', "
                . "title = '" . $strUserTitle . "', "
                . "sigintitle = '" . $strUserSignCharge . "' "
                . "WHERE userid = " . $strUserID . "; ";

        $up = $this->query($sql);

        return true;
    }

    public function get_user_by_id($userId) {
        $userId = $this->real_escape_string($userId);
        $sql = "SELECT userfullname, username, password, email, title, sigintitle "
                . "FROM systemuser WHERE userid = " . $userId . ";";
        return $this->query($sql);
    }

    /* SUPPLIER   */

    public function supplierNewSupplier($companyId, $sourceid, $supplierName) {
        $companyId = $this->real_escape_string($companyId);
        $sourceid = $this->real_escape_string($sourceid);
        $supplierName = $this->real_escape_string($supplierName);
        $sql = "INSERT INTO supplier (companyid, sourceid, companyname) "
                . "SELECT '" . $companyId . "', '" . $sourceid . "', '" . $supplierName . "' \n"
                . "FROM insert_table  \n"
                . "WHERE NOT EXISTS  "
                . "(SELECT id FROM supplier WHERE companyid = '" . $companyId . "' AND companyname = '" . $supplierName . "'); \n";
        $supplierID = $this->query($sql);

        $sql = "SELECT id FROM supplier WHERE companyid = '" . $companyId . "' AND companyname = '" . $supplierName . "';";

        $supplierID = $this->query($sql);

        if ($supplierID->num_rows > 0) {
            $row = $supplierID->fetch_row();
            return $row[0];
        } else {
            return null;
        }
    }

    public function supplierSaveSupplier($arraySupplierInfo) {

        $supplierId = $this->real_escape_string($arraySupplierInfo['supplierId']);
        $companyid = $this->real_escape_string($arraySupplierInfo['companyid']);
        $sourceid = $this->real_escape_string($arraySupplierInfo['sourceid']);
        $suppliertaxid = $this->real_escape_string($arraySupplierInfo['suppliertaxid']);
        $companyname = $this->real_escape_string($arraySupplierInfo['companyname']);

        $billingcountry = $this->real_escape_string($arraySupplierInfo['billingcountry']);
        $billingprovince = $this->real_escape_string($arraySupplierInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arraySupplierInfo['billingmunicipality']);
        $billingcity = $this->real_escape_string($arraySupplierInfo['billingcity']);
        $billingdistrict = $this->real_escape_string($arraySupplierInfo['billingdistrict']);
        $billingcomuna = $this->real_escape_string($arraySupplierInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arraySupplierInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arraySupplierInfo['billingstreetname']);
        $billingbuildingnumber = $this->real_escape_string($arraySupplierInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arraySupplierInfo['billingpostalcode']);

        $telephone1 = $this->real_escape_string($arraySupplierInfo['telephone1']);
        $telephone2 = $this->real_escape_string($arraySupplierInfo['telephone2']);
        $telephone3 = $this->real_escape_string($arraySupplierInfo['telephone3']);
        $email = $this->real_escape_string($arraySupplierInfo['email']);
        $website = $this->real_escape_string($arraySupplierInfo['website']);
        $contactperson = $this->real_escape_string($arraySupplierInfo['contactperson']);
        $contactpersoncharge = $this->real_escape_string($arraySupplierInfo['contactpersoncharge']);

        if ($this->validateDuplicateElement($companyid, $supplierId, "id", $suppliertaxid,
                        "supplier", "suppliertaxid") == 1) {
            echo json_encode(array("status" => 0, "msg" => 'O NIF já foi cadastrado.'));
            return false;
        }


        if ($this->validateDuplicateElement($companyid, $supplierId, "companyname", $companyname,
                        "supplier", "companyname") == 1) {
            echo json_encode(array("status" => 0, "msg" => 'Este fornecedor já foi cadastrado.'));
            return false;
        }

        if (!is_numeric($supplierId)) {
            $supplierId = $this->supplierNewSupplier($companyid, $sourceid, $companyname);
        }

        $sql = "UPDATE supplier SET "
                . "suppliertaxid = '" . $suppliertaxid . "', "
                . "companyname = '" . $companyname . "', "
                . "billingcountry = '" . $billingcountry . "', "
                . "billingprovince = '" . $billingprovince . "', "
                . "billingmunicipality = '" . $billingmunicipality . "', "
                . "billingcity = '" . $billingcity . "', "
                . "billingdistrict = '" . $billingdistrict . "', "
                . "billingcomuna = '" . $billingcomuna . "', "
                . "billingneiborhood = '" . $billingneiborhood . "', "
                . "billingstreetname = '" . $billingstreetname . "', "
                . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
                . "billingpostalcode = '" . $billingpostalcode . "', "
                . "telephone1 = '" . $telephone1 . "', "
                . "telephone2 = '" . $telephone2 . "', "
                . "telephone3 = '" . $telephone3 . "', "
                . "email = '" . $email . "', "
                . "website = '" . $website . "', "
                . "contactperson = '" . $contactperson . "', "
                . "contactpersoncharge = '" . $contactpersoncharge . "' "
                . "WHERE id = " . $supplierId . "; ";

        $up = $this->query($sql);

        echo json_encode(array("status" => 1, "msg" => 'Fornecedor actualizado com sucesso.'));
        return true;
    }

    public function supplierHistoricGet($companyId, $supplierId) {

        $companyId = $this->real_escape_string($companyId);
        $supplierId = $this->real_escape_string($supplierId);

        function strSupHistoric($order, $table, $company, $supplier) {
            return "SELECT (" . $order . ") AS docOrder, invoicetype, invoicenumber, invoicestatus, invoicedate, sourceid, \n"
                    . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName \n"
                    . "FROM " . $table . " AS I  \n"
                    . "WHERE companyid = '" . $company . "' AND supplierid = '" . $supplier . "' \n";
        }

        $sql = strSupHistoric(1, "entranceofgoods", $companyId, $supplierId)
                . "UNION \n"
                . strSupHistoric(2, "movimentofgoods", $companyId, $supplierId)
                . "ORDER BY docOrder, invoicetype, invoicedate; ";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function supplier_list($orderBy) {

        $sql = "SELECT id, companyname, suppliertaxid "
                . "FROM supplier "
                . "ORDER BY companyname;";
        return $this->query($sql);
    }

    public function get_supplier_by_id($supplierid) {
        $supplierid = $this->real_escape_string($supplierid);
        $sql = "SELECT * "
                . "FROM supplier WHERE id = " . $supplierid . ";";
        return $this->query($sql);
    }

    public function new_product($companyId, $productDescription) {
        $companyId = $this->real_escape_string($companyId);
        $productDescription = $this->real_escape_string($productDescription);
        $sql = "INSERT INTO product (companyid, productdescription) \n"
                . "SELECT '" . $companyId . "', '" . $productDescription . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS  "
                . "(SELECT id FROM product WHERE companyid = '" . $companyId . "' AND productdescription = '" . $productDescription . "');";
        $produtoId = $this->query($sql);

        $sql = "SELECT id FROM product WHERE companyid = '" . $companyId . "' AND productdescription = '" . $productDescription . "';";

        $produtoId = $this->query($sql);

        if ($produtoId->num_rows > 0) {
            $row = $produtoId->fetch_row();
            return $row[0];
        } else {
            return null;
        }
    }

    public function productProductSave($companyId, $arrayProduct) {

        $companyId = $this->real_escape_string($companyId);
        $productid = $this->real_escape_string($arrayProduct['productid']);
        $producttype = $this->real_escape_string($arrayProduct['producttype']);
        $productcategory = $this->real_escape_string($arrayProduct['productcategory']);
        $productnumbercode = $this->real_escape_string($arrayProduct['productnumbercode']);
        $productfamily = $this->real_escape_string($arrayProduct['productfamily']);
        $productbrand = $this->real_escape_string($arrayProduct['productbrand']);
        $productmodel = $this->real_escape_string($arrayProduct['productmodel']);
        $productrelativesize = $this->real_escape_string($arrayProduct['productrelativesize']);
        $productunit = $this->real_escape_string($arrayProduct['productunit']);
        $productdescription = $this->real_escape_string($arrayProduct['productdescription']);
        $productnote = $this->real_escape_string($arrayProduct['productnote']);
        $productkeyword1 = $this->real_escape_string($arrayProduct['productkeyword1']);
        $productkeyword2 = $this->real_escape_string($arrayProduct['productkeyword2']);
        $productkeyword3 = $this->real_escape_string($arrayProduct['productkeyword3']);
        $productweight = $this->real_escape_string($arrayProduct['productweight']);
        $productnetweight = $this->real_escape_string($arrayProduct['productnetweight']);
        $productlenght = $this->real_escape_string($arrayProduct['productlenght']);
        $productwidth = $this->real_escape_string($arrayProduct['productwidth']);
        $productheight = $this->real_escape_string($arrayProduct['productheight']);
        $productsection = $this->real_escape_string($arrayProduct['productsection']);
        $productstock = $this->real_escape_string($arrayProduct['productstock']);
        $productminimostock = $this->real_escape_string($arrayProduct['productminimostock']);
        $supplierid = $this->real_escape_string($arrayProduct['supplierid']);
        $devolutiondays = $this->real_escape_string($arrayProduct['devolutiondays']);
        $productivacategory = $this->real_escape_string($arrayProduct['productivacategory']);
        $photo = $this->real_escape_string($arrayProduct['photo']);

        if ($this->validateDuplicateElement($companyId, $productid, 'id',
                        $productnumbercode, 'product', "productnumbercode") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Código de barras já cadastrado.'));
        }
        if ($this->validateDuplicateElement($companyId, $productid, 'id',
                        $productdescription, 'product', "productdescription") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Produto já cadastrado.'));
        }

        if (!is_numeric($productid)) {
            $productid = $this->new_product($companyId, $productdescription);
        }


        $sql = "UPDATE product SET \n"
                . "productcode = id, "
                . "productnumbercode = '" . $productnumbercode . "', "
                . "productivacategory = '" . $productivacategory . "', "
                . "producttype = '" . $producttype . "', "
                . "productcategory = '" . $productcategory . "', "
                . "productfamily = '" . $productfamily . "', "
                . "productbrand = '" . $productbrand . "', "
                . "productmodel = '" . $productmodel . "', "
                . "productrelativesize = '" . $productrelativesize . "', "
                . "productunit = '" . $productunit . "', \n"
                . "productdescription = '" . $productdescription . "', "
                . "productnote = '" . $productnote . "', \n"
                . "productkeyword1 = '" . $productkeyword1 . "', "
                . "productkeyword2 = '" . $productkeyword2 . "', "
                . "productkeyword3 = '" . $productkeyword3 . "', "
                . "productweight = '" . $productweight . "', "
                . "productnetweight = '" . $productnetweight . "', "
                . "productlenght = '" . $productlenght . "', \n"
                . "productwidth = '" . $productwidth . "', "
                . "productheight = '" . $productheight . "', "
                . "productsection = '" . $productsection . "', "
                . "productstock = '" . $productstock . "', "
                . "productminimostock = '" . $productminimostock . "', "
                . "supplierid = '" . $supplierid . "', "
                . "devolutiondays = '" . $devolutiondays . "',  "
                . "photo = '" . $photo . "' \n"
                . "WHERE id = '" . $productid . "'; ";

        $result = $this->query($sql);

        return json_encode(array("status" => 1, "msg" => 'Producto guardado com sucesso.', "productId" => $productid));
    }

    public function productDelete($strProductID) {
        $strProductID = $this->real_escape_string($strProductID);
        $sql = "DELETE FROM product WHERE id = " . $strProductID . "; ";
        $this->query($sql);

        $sql = "SELECT id FROM product WHERE id = '" . $strProductID . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            echo json_encode(array("status" => 0, "msg" => "Não foi possível eliminar este produto / serviço."));
            return false;
        }

        echo json_encode(array("status" => 1, "msg" => "Produto / serviço eliminado com sucesso."));

        return true;
    }

    public function productProductSaveLote($companyId, $arrayProductLines) {

        $companyId = $this->real_escape_string($companyId);
        $allRN = "'-'";
        $sql = "";

        /* LINES  */
        foreach ($arrayProductLines as $key => $arrayProduct) {
            $producttype = $this->real_escape_string($arrayProduct['producttype']);
            $productcategory = $this->real_escape_string($arrayProduct['productcategory']);
            $productnumbercode = $this->real_escape_string($arrayProduct['productnumbercode']);
            $productfamily = $this->real_escape_string($arrayProduct['productfamily']);
            $productbrand = $this->real_escape_string($arrayProduct['productbrand']);
            $productmodel = $this->real_escape_string($arrayProduct['productmodel']);
            $productunit = $this->real_escape_string($arrayProduct['productunit']);
            $productdescription = $this->real_escape_string($arrayProduct['productdescription']);
            $productsection = $this->real_escape_string($arrayProduct['productsection']);
            $productstock = $this->real_escape_string($arrayProduct['productstock']);
            $productivacategory = $this->real_escape_string($arrayProduct['productivacategory']);
            $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

            $warehouseprice = $this->real_escape_string($arrayProduct['warehouseprice']);
            $estimatedprofit = $this->real_escape_string($arrayProduct['estimatedprofit']);
            $iva = $this->real_escape_string($arrayProduct['iva']);
            $allRN .= ", '" . $registerNumber . "'";

            $sql .= "INSERT INTO product (\n"
                    . "companyid, productdescription, productnumbercode, productivacategory, producttype, \n"
                    . "productcategory, productfamily, productbrand, productmodel, \n"
                    . "productunit, productsection, productstock, registernumber ) \n"
                    . "SELECT '" . $companyId . "', "
                    . "'" . $productdescription . "', '" . $productnumbercode . "', '" . $productivacategory . "', '" . $producttype . "', \n"
                    . "'" . $productcategory . "', '" . $productfamily . "', '" . $productbrand . "', '" . $productmodel . "', \n"
                    . "'" . $productunit . "', '" . $productsection . "', '" . $productstock . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS "
                    . "(SELECT id FROM product WHERE companyid = '" . $companyId . "' AND (productdescription = '" . $productdescription . "') ); ";    //OR productnumbercode = '" . $productnumbercode . "'

            $sql .= "INSERT INTO productprice (\n"
                    . "productid, warehouseprice, estimatedprofit, iva) \n"
                    . "SELECT (SELECT id FROM product WHERE registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                    . "'" . $warehouseprice . "', '" . $estimatedprofit . "', '" . $iva . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM productprice WHERE productid = (SELECT id FROM product WHERE registernumber = '" . $registerNumber . "' LIMIT 1)); \n";
        }

        $sql .= "UPDATE product SET \n"
                . "productcode = id \n"
                . "WHERE registernumber IN (" . $allRN . "); \n";

        $sql .= "SELECT id \n"
                . "FROM product \n"
                . "WHERE registernumber IN (" . $allRN . "); \n";


        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o(s) produto(s). Provavelmente algum produto está em duplicado."));
        }

        return json_encode(array("status" => 1, "msg" => "Produto(s) guardado(s) com sucesso."));
    }

    public function productGetProductList($companyId, $productSection = -1, $productCategory = -1, $searchLimit = -1, $searchTag = "",
            $family = -1, $brand = -1, $stock = -1, $workplaceId = -1,
            $productId = -1, $productType = -1, $expirationDate = "") {

        $companyId = $this->real_escape_string($companyId);
        $productId = $this->real_escape_string($productId);
        $productType = $this->real_escape_string($productType);
        $productSection = $this->real_escape_string($productSection);
        $productCategory = $this->real_escape_string($productCategory);
        $searchTag = $this->real_escape_string($searchTag);
        $searchLimit = $this->real_escape_string($searchLimit);
        $family = $this->real_escape_string($family);
        $brand = $this->real_escape_string($brand);
        $stock = $this->real_escape_string($stock);
        $expirationDate = $this->real_escape_string($expirationDate);
        $workplaceId = $this->real_escape_string($workplaceId);

        $where = "";
        if ($productId != -1) {
            $where .= " AND P.id = '" . $productId . "'  ";
        }
        if ($productType != -1) {
            $where .= " AND P.producttype = '" . $productType . "'  ";
        }

        if ($productSection != -1) {
            $where .= " AND P.productsection = '" . $productSection . "'  ";
        }
        if ($productCategory != -1) {
            $where .= " AND P.productcategory = '" . $productCategory . "'  ";
        }
        if ($family != -1) {
            $where .= " AND P.productfamily = '" . $family . "'  ";
        }
        if ($brand != -1) {
            $where .= " AND P.productbrand = '" . $brand . "'  ";
        }
        if ($stock != -1) {
            $where .= " AND P.productstock = '" . $stock . "'  ";
        }
        if ($expirationDate != "") {
            $where .= " AND CAST(expirationdate AS DATE) = CAST('" . $expirationDate . "' AS DATE) ";
        }
        if ($searchTag != "") {
            $where .= " AND (CAST(P.id AS CHAR) = '" . $searchTag . "' OR P.productnumbercode = '" . $searchTag . "' OR P.productdescription LIKE '%" . $searchTag . "%') ";
        }

        $fldExistence = "(COALESCE((SELECT SUM(PSA.quantity) FROM productstockactual AS PSA WHERE PSA.productid = P.id  "
                . "AND PSA.stockid = (SELECT PS.id FROM productstock AS PS WHERE PS.dependencyid = '" . $workplaceId . "' AND PS.billingstock = 1 LIMIT 1)) , 0)) AS quantity ";

        $limit = "";
        if ($searchLimit >= 1) {
            $limit = "LIMIT " . $searchLimit . "  \n";
        }

        //  . "(COALESCE((SELECT PP.pvp FROM productprice AS PP WHERE PP.productid = P.id LIMIT 1), 0)) AS PVP, \n"

        $sql = "SELECT *, \n"
                . "(SELECT PS.section FROM productsection AS PS WHERE PS.id = P.productsection LIMIT 1) AS strproductsection, \n"
                . "(SELECT PC.category FROM productivacategory AS PC WHERE PC.id = P.productivacategory LIMIT 1) AS strproductivacategory "
                . strField('warehouseprice', 0) . strField('indirectcost') . strField("commercialcost")
                . strField('estimatedprofit') . strField("unitprice", 0) . strField("silicacomission")
                . strField("sellercomission") . strField("managercomission")
                . strField("fundinvestment") . strField("fundreserve") . strField("fundsocialaction")
                . strField("descount", 0) . strField("iva", 0, 1) . strField("pvp", 0) . ", "
                . $fldExistence . ", \n"
                . "productstock, productweight, productnetweight \n"
                . "FROM product AS P \n"
                . "WHERE P.companyid = '" . $companyId . "' " . $where . " \n"
                . "ORDER BY productdescription  \n"
                . $limit . ";";

        $result = $this->query($sql);

        $array = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($array, json_encode($row));
        }
        mysqli_free_result($result);
        return $array;
    }

    public function productAutocompleteList($companyId) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT DISTINCT id, productdescription, productnumbercode  \n"
                . "FROM product AS P \n"
                . "WHERE P.companyid = '" . $companyId . "'  \n"
                . "ORDER BY productdescription ;";

        $result = $this->query($sql);

        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= '-/-' . $row['id'] . '-/-' . $row['productdescription'] . '-/-' . $row['productnumbercode'];
        }
        mysqli_free_result($result);
        return $str;
    }

    public function productPriceSave($arrayProductPrice) {

        $sql = "";
        foreach ($arrayProductPrice as $key => $pcLine) {
            $productid = $this->real_escape_string($pcLine['productid']);
            $warehouseprice = $this->real_escape_string($pcLine['warehouseprice']);
            $indirectcost = $this->real_escape_string($pcLine['indirectcost']);
            $commercialcost = $this->real_escape_string($pcLine['commercialcost']);
            $estimatedprofit = $this->real_escape_string($pcLine['estimatedprofit']);
            $unitprice = $this->real_escape_string($pcLine['unitprice']);
            $sellercomission = $this->real_escape_string($pcLine['sellercomission']);
            $managercomission = $this->real_escape_string($pcLine['managercomission']);
            $fundinvestment = $this->real_escape_string($pcLine['fundinvestment']);
            $fundreserve = $this->real_escape_string($pcLine['fundreserve']);
            $fundsocialaction = $this->real_escape_string($pcLine['fundsocialaction']);
            $descount = $this->real_escape_string($pcLine['descount']);
            $iva = $this->real_escape_string($pcLine['iva']);
            $pvp = $this->real_escape_string($pcLine['pvp']);

            $sql .= "INSERT INTO productprice (productid) \n"
                    . "SELECT " . $productid . "  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM productprice WHERE productid = '" . $productid . "'); \n";

            $sql .= "UPDATE productprice SET \n"
                    . "warehouseprice = '" . $warehouseprice . "', "
                    . "indirectcost = '" . $indirectcost . "', "
                    . "commercialcost = '" . $commercialcost . "', "
                    . "estimatedprofit = '" . $estimatedprofit . "', "
                    . "unitprice = '" . $unitprice . "', \n"
                    . "sellercomission = '" . $sellercomission . "', "
                    . "managercomission = '" . $managercomission . "', \n"
                    . "fundinvestment = '" . $fundinvestment . "', "
                    . "fundreserve = '" . $fundreserve . "', "
                    . "fundsocialaction = '" . $fundsocialaction . "', "
                    . "descount = '" . $descount . "', "
                    . "iva = '" . $iva . "', "
                    . "pvp = '" . $pvp . "' \n"
                    . "WHERE productid = '" . $productid . "'; \n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Preços actualizados com sucesso."));
    }

    public function productPricePercentSuggestGet($companyId) {
        $companyId = $this->real_escape_string($companyId);

        $sql = "INSERT INTO productpricepercentsuggest (companyid) \n"
                . "SELECT '" . $companyId . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS(SELECT id FROM productpricepercentsuggest WHERE companyid = '" . $companyId . "'); \n";
        $this->query($sql);

        $sql = "SELECT * \n"
                . "FROM productpricepercentsuggest    \n"
                . "WHERE companyid = '" . $companyId . "';";

        $result = $this->query($sql);

        $row = mysqli_fetch_array($result);
        $row = $this->converteArrayParaUtf8($row);
        mysqli_free_result($result);
        return $row;
    }

    public function productPricePercentSuggestSave($companyId, $arrayPercentSuggest) {
        $companyId = $this->real_escape_string($companyId);

        $indirectcost = $this->real_escape_string($arrayPercentSuggest['indirectcost']);
        $commercialcost = $this->real_escape_string($arrayPercentSuggest['commercialcost']);
        $estimatedprofit = $this->real_escape_string($arrayPercentSuggest['estimatedprofit']);
        $sellercomission = $this->real_escape_string($arrayPercentSuggest['sellercomission']);
        $managercomission = $this->real_escape_string($arrayPercentSuggest['managercomission']);
        $fundinvestment = $this->real_escape_string($arrayPercentSuggest['fundinvestment']);
        $fundreserve = $this->real_escape_string($arrayPercentSuggest['fundreserve']);
        $fundsocialaction = $this->real_escape_string($arrayPercentSuggest['fundsocialaction']);


        $sql = "UPDATE productpricepercentsuggest SET  \n"
                . "indirectcost = '" . $indirectcost . "', "
                . "commercialcost = '" . $commercialcost . "', "
                . "estimatedprofit = '" . $estimatedprofit . "', "
                . "sellercomission = '" . $sellercomission . "', "
                . "managercomission = '" . $managercomission . "', "
                . "fundinvestment = '" . $fundinvestment . "', "
                . "fundreserve = '" . $fundreserve . "', "
                . "fundsocialaction = '" . $fundsocialaction . "'  "
                . "WHERE companyid = '" . $companyId . "'; \n";
        $result = $this->multi_query($sql);
        return 1;
    }

    public function productGetFullDescription($productid, $companyId = "", $businessId = "",
            $isList = 0, $stockId = -1, $workPlaceId = -1) {

        $productid = $this->real_escape_string($productid);
        $companyId = $this->real_escape_string($companyId);
        $businessId = $this->real_escape_string($businessId);
        $stockId = $this->real_escape_string($stockId);
        $workPlaceId = $this->real_escape_string($workPlaceId);

        if ($companyId == "") {
            $companyId = "5000";
        }

        function managers($field) {
            return "(COALESCE((SELECT PS." . $field . " FROM productsection AS PS WHERE PS.id = P.productsection LIMIT 1), 0)) AS " . $field . ", ";
        }

        $strProductExemption = " (SELECT PC.productexemption FROM productivacategory AS PC WHERE PC.id = P.productivacategory LIMIT 1) ";
        $stockSource = $stockId;
        if ($stockId == -1) {
            $stockSource = "(SELECT ST.id FROM productstock AS ST WHERE ST.dependencyid = '" . $workPlaceId . "' AND ST.companyid = '" . $companyId . "' AND ST.billingstock = 1)";
        }
        $strProductStockActual = " (COALESCE((SELECT SUM(PSA.quantity) AS quantity  FROM productstockactual AS PSA WHERE PSA.stockid IN (" . $stockSource . ") AND PSA.productid = P.id LIMIT 1), 0)) AS productstockactual ";

        $where = "";
        $limit = "";
        if ($productid != "") {
            if ($isList == 1) {
                $where .= " AND (P.id IN (" . $productid . ")) ";
            } else {
                $where .= " AND (CAST(P.id AS CHAR) = '" . $productid . "' OR BINARY P.productnumbercode = '" . $productid . "' OR BINARY P.productdescription = '" . $productid . "') ";
                $limit = "LIMIT 1";
            }
        }
        if ($businessId != "") {
            $where .= " AND businessid = '" . $businessId . "' ";
        }


        $sql = "SELECT * " . strField('warehouseprice', 0) . strField('indirectcost') . strField("commercialcost")
                . strField('estimatedprofit') . strField("unitprice", 0) . strField("silicacomission")
                . strField("sellercomission") . strField("managercomission")
                . strField("fundinvestment") . strField("fundreserve") . strField("fundsocialaction")
                . strField("descount", 0) . strField("iva", 0, 1) . strField("pvp", 0) . ", "
                . $strProductExemption . " AS productexemption, "
                . "(SELECT PE.exemptionreason FROM productexemptioncode AS PE WHERE PE.exemptioncode = " . $strProductExemption . " LIMIT 1) AS exemptionreason, "
                . $strProductStockActual
                . "FROM product AS P "
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY P.productdescription  "
                . $limit . ";";

        $result = $this->query($sql);
        if ($isList == 1) {
            $arrayResult = array();
            while ($row = mysqli_fetch_array($result)) {
                $row = $this->converteArrayParaUtf8($row);
                if ($row['photo'] == "") {
                    $row['photo'] = "image.svg";
                }
                $row['photo'] = productPhotoPath() . $row['photo'];
                array_push($arrayResult, json_encode($row));
            }
            mysqli_free_result($result);
            return json_encode($arrayResult);
        } else {
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                if ($row['photo'] == "") {
                    $row['photo'] = "image.svg";
                }
                $row['photo'] = productPhotoPath() . $row['photo'];
                $row = $this->converteArrayParaUtf8($row);
                return json_encode($row);
            }
        }
    }

    public function productGetProductSection($companyId, $sectionId, $chargeType = -1) {
        $companyId = $this->real_escape_string($companyId);
        $sectionId = $this->real_escape_string($sectionId);
        $chargeType = $this->real_escape_string($chargeType);
        $where = "";
        if ($sectionId != "") {
            $where = " AND id = '" . $sectionId . "' ";
        }
        if ($chargeType == 1) {//Diário e Mensal
            $where .= " AND chargetype > 1 ";
        } elseif ($chargeType == 0) {//padrão
            $where .= " AND chargetype <= 1 ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = PS.managernationaluser LIMIT 1) AS manager  \n"
                . "FROM productsection AS PS  \n"
                . "WHERE (companyid = '" . $companyId . "' OR id = 1) " . $where
                . "ORDER BY section";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function productNewSection($companyId, $section) {
        $companyId = $this->real_escape_string($companyId);
        $section = $this->real_escape_string($section);
        $sql = " INSERT INTO productsection (companyid, section) \n"
                . "SELECT '" . $companyId . "', '" . $section . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM productsection WHERE companyid = '" . $companyId . "' AND section='" . $section . "');";
        $this->query($sql);
        $sql = "SELECT id FROM productsection WHERE companyid = '" . $companyId . "' AND section ='" . $section . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function productSaveSection($companyId, $sectionID, $section, $managerNationalUser, $directorUser, $photo,
            $chargetype, $chargesequence, $installmentindicator) {

        $companyId = $this->real_escape_string($companyId);
        $sectionID = $this->real_escape_string($sectionID);
        $section = $this->real_escape_string($section);
        $managerNationalUser = $this->real_escape_string($managerNationalUser);
        $directorUser = $this->real_escape_string($directorUser);
        $photo = $this->real_escape_string($photo);
        $chargetype = $this->real_escape_string($chargetype);
        $chargesequence = $this->real_escape_string($chargesequence);
        $installmentindicator = $this->real_escape_string($installmentindicator);

        if ($this->validateDuplicateElement($companyId, $sectionID, "id",
                        $section, "productsection", "section") == 1) {
            return json_encode(array("status" => 0, "msg" => "Secção já cadastrada."));
        }

        if (!is_numeric($sectionID)) {
            $sectionID = $this->productNewSection($companyId, $section);
        }

        $sql = "UPDATE productsection SET \n"
                . "section = '" . $section . "', "
                . "chargetype = '" . $chargetype . "', "
                . "chargesequence = '" . $chargesequence . "', "
                . "installmentindicator = '" . $installmentindicator . "', \n"
                . "managernationaluser = '" . $managerNationalUser . "', "
                . "directoruser = '" . $directorUser . "',  "
                . "photo = '" . $photo . "' \n"
                . "WHERE id= " . $sectionID . ";";

        $productId = $companyId . $sectionID;
        if ($chargetype <= 1) {
            $sql .= "DELETE FROM product WHERE productcode = '" . $productId . "'; \n";
        } else {
            $sql .= "UPDATE product SET productdescription = '" . $section . "', photo = '" . $photo . "' \n"
                    . "WHERE productcode = '" . $productId . "'; \n";
            $sql .= "INSERT INTO product (\n"
                    . "companyid, productcode, productsection, productstock, photo, "
                    . "productdescription, productnote, periodicservice) \n"
                    . "SELECT '" . $companyId . "', '" . $productId . "', '" . $sectionID . "', 0, '" . $photo . "', "
                    . "'" . $section . "', 'POR FAVOR, NÃO ELIMINAR. ESTE É UM SERVIÇO DE COBRANÇA PERIÓDICA.', 1  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM product WHERE productcode = '" . $productId . "');";
        }
        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Secção cadastrada com sucesso."));
    }

    public function productSectionDelete($sectionId) {
        $sectionId = $this->real_escape_string($sectionId);

        $sql = "DELETE FROM productsection WHERE id = " . $sectionId . "; ";
        $this->query($sql);

        $sql = "SELECT id FROM productsection WHERE id = '" . $sectionId . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível eliminar esta secção."));
        }

        return json_encode(array("status" => 1, "msg" => "Secção eliminada com sucesso."));
    }

    public function productGetIvaCategory($companyId, $categoryId) {
        $companyId = $this->real_escape_string($companyId);
        $categoryId = $this->real_escape_string($categoryId);
        $where = "";
        if ($categoryId != "") {
            $where = " AND id = '" . $categoryId . "' ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT PE.exemptionreason FROM productexemptioncode AS PE WHERE PE.exemptioncode = PIC.productexemption LIMIT 1) AS exemptionreason \n"
                . "FROM productivacategory AS PIC \n"
                . "WHERE (companyid = '" . $companyId . "' OR id <= 18 ) " . $where
                . "ORDER BY category";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function productSaveCategory($companyId, $categoryId,
            $ivaCategory, $productType, $productIVA, $photo) {

        $companyId = $this->real_escape_string($companyId);
        $categoryId = $this->real_escape_string($categoryId);
        $ivaCategory = $this->real_escape_string($ivaCategory);
        $productType = $this->real_escape_string($productType);
        $productIVA = $this->real_escape_string($productIVA);
        $photo = $this->real_escape_string($photo);

        if ($this->validateDuplicateElement($companyId, $categoryId, "id",
                        $category, "productivacategory", "category") == 1) {
            return json_encode(array("status" => 0, "msg" => "Categoria já cadastrada."));
        }

        if (!is_numeric($categoryId)) {
            $categoryId = $this->productNewCategory($companyId, $ivaCategory);
        }

        $sql = "UPDATE productivacategory SET \n"
                . "category = '" . $ivaCategory . "', "
                . "producttype = '" . $productType . "', "
                . "productiva = '" . $productIVA . "',  "
                . "photo = '" . $photo . "' \n"
                . "WHERE id = " . $categoryId . ";";
        $this->query($sql);
        return json_encode(array("status" => 1, "msg" => "Categoria cadastrada com sucesso."));
    }

    public function productNewCategory($companyId, $ivaCategory) {
        $companyId = $this->real_escape_string($companyId);
        $ivaCategory = $this->real_escape_string($ivaCategory);

        $sql = " INSERT INTO productivacategory (companyId, category, productexemption) \n"
                . "SELECT '" . $companyId . "', '" . $ivaCategory . "', 'M02' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM productivacategory WHERE companyId = '" . $companyId . "' AND category = '" . $ivaCategory . "'); \n";
        $this->query($sql);
        $sql = "SELECT id \n"
                . "FROM productivacategory \n"
                . "WHERE companyId = '" . $companyId . "' AND category ='" . $ivaCategory . "'; \n";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
        mysqli_free_result($result);
    }

    public function productIvaCategoryDelete($categoryID) {
        $categoryID = $this->real_escape_string($categoryID);

        $sql = "DELETE FROM productivacategory WHERE id = " . $categoryID . "; ";
        $this->query($sql);

        $sql = "SELECT id FROM productivacategory WHERE id = '" . $categoryID . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível eliminar esta categoria."));
        }

        return json_encode(array("status" => 1, "msg" => "Categoria eliminada com sucesso."));
    }

    public function productGetCategory() {


        $sql = "SELECT * \n"
                . "FROM productcategory \n"
                . "ORDER BY cetegorysupergroup, cetegorygroup, category";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function getProductStock($companyId, $productStockId, $dependecyId = -1) {
        $companyId = $this->real_escape_string($companyId);
        $productStockId = $this->real_escape_string($productStockId);
        $dependecyId = $this->real_escape_string($dependecyId);
        $where = "";
        if ($productStockId != -1) {
            $where = " AND id = '" . $productStockId . "'  ";
        }
        if ($dependecyId != -1) {
            $where .= " AND dependencyid = '" . $dependecyId . "' ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = (SELECT CD.municipalityid FROM companydependency AS CD WHERE CD.id = PS.dependencyid LIMIT 1) LIMIT 1) AS municipality, \n"
                . "(SELECT CD.designation FROM companydependency AS CD WHERE CD.id = PS.dependencyid LIMIT 1) AS dependency \n"
                . "FROM productstock AS PS  \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY billingstock DESC, desigination;";

        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function productStockNew($companyId, $designation) {
        $companyId = $this->real_escape_string($companyId);
        $designation = $this->real_escape_string($designation);

        $sql = " INSERT INTO productstock (companyid, desigination) \n"
                . "SELECT '" . $companyId . "', '" . $designation . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS "
                . "(SELECT id FROM productstock WHERE companyid = '" . $companyId . "' AND desigination='" . $designation . "');";
        $this->query($sql);
        $sql = "SELECT id FROM productstock WHERE companyid = '" . $companyId . "' AND desigination ='" . $designation . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function productStockSave($companyId, $strStockId, $Designation, $billingStock, $dependency) {
        $companyId = $this->real_escape_string($companyId);
        $strStockId = $this->real_escape_string($strStockId);
        $Designation = $this->real_escape_string($Designation);
        $billingStock = $this->real_escape_string($billingStock);
        $dependency = $this->real_escape_string($dependency);

        if ($this->validateDuplicateElement($companyId, $strStockId, "id",
                        $Designation, "productstock", "desigination") == 1) {
            echo json_encode(array("status" => 0, "msg" => 'Este estoque / armazém já foi cadastrado.'));
            return false;
        }

        if (!is_numeric($strStockId)) {
            $strStockId = $this->productStockNew($companyId, $Designation);
        }

        $sql = "";
        if ($billingStock == 1) {
            $sql .= "UPDATE productstock SET billingstock = 0 WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependency . "';  \n";
        }

        $sql .= "UPDATE productstock SET \n"
                . "desigination = '" . $Designation . "', ";
        if ($billingStock == 1) {
            $sql .= "billingstock = '" . $billingStock . "', ";
        }
        $sql .= "dependencyid = '" . $dependency . "' \n"
                . "WHERE id= " . $strStockId . ";";
        $result = $this->multi_query($sql);
        echo json_encode(array("status" => 1, "msg" => 'Estoque / armazém actualizado com sucesso.'));
        return true;
    }

    public function productGetStockActual($companyId, $stockId, $sectionId,
            $categoryId, $family, $brand, $stockable) {

        $companyId = $this->real_escape_string($companyId);
        $stockId = $this->real_escape_string($stockId);
        $sectionId = $this->real_escape_string($sectionId);
        $categoryId = $this->real_escape_string($categoryId);
        $family = $this->real_escape_string($family);
        $brand = $this->real_escape_string($brand);
        $stockable = $this->real_escape_string($stockable);
        $where = "";
        $whereStock = "";
        $whereStockIn = "";
        $whereStocOut = "";
        if ($stockId != -1) {
            $whereStock .= " AND PSA.stockid = '" . $stockId . "'  ";
            $whereStockIn .= " AND I.stocktarger = '" . $stockId . "'  ";
            $whereStocOut .= " AND I.stocksource = '" . $stockId . "'  ";
        }
        if ($sectionId > 0) {
            $where = $where . " AND P.productsection = '" . $sectionId . "'  ";
        }
        if ($categoryId > 0) {
            $where = $where . " AND P.productivacategory = '" . $categoryId . "'  ";
        }
        if ($family != -1) {
            $where .= " AND P.productfamily = '" . $family . "'  ";
        }
        if ($brand != -1) {
            $where .= " AND P.productbrand = '" . $brand . "'  ";
        }
        if ($stockable > 0) {
            $where = $where . " AND P.productstock = '" . $stockable . "'  ";
        }

        $lastIn = ", (SELECT I.invoicedate FROM entranceofgoods AS I WHERE UPPER(I.invoicestatus)!='A' " . $whereStockIn . " AND I.invoicenumber = "
                . "(SELECT MAX(IL.invoiceNumber) FROM entranceofgoodsline AS IL WHERE UPPER(IL.status)='N' AND IL.productId = P.id LIMIT 1) LIMIT 1) AS lastIn \n";

        $lastOut = ", (SELECT I.invoicedate FROM movimentofgoods AS I WHERE UPPER(I.invoicestatus)!='A' " . $whereStocOut . " AND I.invoicenumber = "
                . "(SELECT MAX(IL.invoiceNumber) FROM movimentofgoodsline AS IL WHERE UPPER(IL.status)='N' AND IL.productId = P.id LIMIT 1) LIMIT 1) AS lastOut \n";


        $sql = "SELECT *, \n"
                . "(SELECT PS.section FROM productsection AS PS WHERE PS.id = P.productsection LIMIT 1) AS section, \n"
                . "(SELECT PC.category FROM productivacategory AS PC WHERE PC.id  = P.productivacategory LIMIT 1) AS category, \n"
                . "(COALESCE((SELECT SUM(PSA.quantity) FROM productstockactual AS PSA WHERE PSA.productid = P.id " . $whereStock . "), 0)) AS stock \n"
                . strField('warehouseprice', 0) . strField('indirectcost') . strField("commercialcost")
                . strField('estimatedprofit') . strField("pvp", 0)
                . $lastIn
                . $lastOut
                . "FROM product P \n"
                . "WHERE P.companyid = '" . $companyId . "' " . $where
                . "ORDER BY productdescription;";

        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function productFamilyBrandModelGetList($companyId, $familyBrandModel) {
        $companyId = $this->real_escape_string($companyId);
        $familyBrandModel = $this->real_escape_string($familyBrandModel);
        $where = "";
        if ($companyId != -1) {
            $where = " AND companyid = '" . $companyId . "' ";
        }
        $sql = "SELECT DISTINCT " . $familyBrandModel . " \n"
                . "FROM product  \n"
                . "WHERE id > 0 " . $where
                . "ORDER BY " . $familyBrandModel . "; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function productStockReport($companyId, $stockId,
            $initalDate, $endDate, $sectionId, $productivacategory, $family, $brand) {

        $companyId = $this->real_escape_string($companyId);
        $stockId = $this->real_escape_string($stockId);
        $initalDate = $this->real_escape_string($initalDate);
        $endDate = $this->real_escape_string($endDate);
        $sectionId = $this->real_escape_string($sectionId);
        $productivacategory = $this->real_escape_string($productivacategory);
        $family = $this->real_escape_string($family);
        $brand = $this->real_escape_string($brand);

        $where = "";
        if ($sectionId != -1) {
            $where .= " AND P.productsection = '" . $sectionId . "'  ";
        }
        if ($productivacategory != -1) {
            $where .= " AND P.productivacategory = '" . $productivacategory . "'  ";
        }
        if ($family != -1) {
            $where .= " AND P.productfamily = '" . $family . "'  ";
        }
        if ($brand != -1) {
            $where .= " AND P.productbrand = '" . $brand . "'  ";
        }

        $dateInterval = " CAST('" . $initalDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ";

        function billAndEntrance($as, $tbl, $tblLine, $dateInterval, $ivType = "", $ivType2 = "") {
            if ($ivType2 != "") {
                $ivType2 = " OR invoicetype = '" . $ivType2 . "'";
            }
            if ($ivType != "") {
                $ivType = " AND (invoicetype = '" . $ivType . "' " . $ivType2 . ") ";
            }
            return ", (COALESCE((SELECT SUM(IL.quantity) FROM " . $tblLine . " AS IL WHERE UPPER(IL.status)='N' AND IL.productId = P.id AND IL.invoiceNumber IN "
                    . "(SELECT I.invoicenumber FROM " . $tbl . " AS I WHERE UPPER(I.invoicestatus)!='A' " . $ivType . " AND (CAST(I.invoicedate AS DATE) BETWEEN " . $dateInterval . "))), 0)) AS " . $as . " \n";
        }

        $sql = "SELECT * \n"
                . billAndEntrance("billing", "invoice", "invoiceline", $dateInterval)
                . billAndEntrance("entrance", "entranceofgoods", "entranceofgoodsline", $dateInterval)
                . billAndEntrance("delivery", "movimentofgoods", "movimentofgoodsline", $dateInterval, "GR", "GT")
                . billAndEntrance("internalMoviment", "movimentofgoods", "movimentofgoodsline", $dateInterval, "GA")
                . billAndEntrance("internConsuption", "movimentofgoods", "movimentofgoodsline", $dateInterval, "CI")
                . billAndEntrance("down", "movimentofgoods", "movimentofgoodsline", $dateInterval, "AE")
                . billAndEntrance("supplierReturn", "movimentofgoods", "movimentofgoodsline", $dateInterval, "GD")
                . billAndEntrance("totalOut", "movimentofgoods", "movimentofgoodsline", $dateInterval)
                . "FROM product AS P \n"
                . "WHERE P.companyid = '" . $companyId . "' AND P.productstock = 1 " . $where . " \n"
                . "ORDER BY productdescription;  \n";

        $result = $this->query($sql);

        $array = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "image.svg";
            }
            $row['photo'] = productPhotoPath() . $row['photo'];
            array_push($array, json_encode($row));
        }
        mysqli_free_result($result);
        return $array;
    }

    public function productExpirationDateSave($arrayExpirationLines) {
        $sql = "";
        foreach ($arrayExpirationLines as $key => $ivLine) {
            $id = $this->real_escape_string($ivLine['id']);
            $expirationdate = $this->real_escape_string($ivLine['expirationdate']);

            $sql .= "UPDATE product SET expirationdate = '" . $expirationdate . "' \n"
                    . "WHERE id = '" . $id . "'; \n";
        }

        $result = $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => 'Data de validade actualizada com sucesso.'));
    }

    public function paymentGetMechanism() {

        $sql = "SELECT * "
                . "FROM paymentmechanism "
                . "ORDER BY id;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function paymentGetNationalBank() {

        $sql = "SELECT * "
                . "FROM nationalbank "
                . "ORDER BY initials;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function paymentGetAccountBank() {

        $sql = "SELECT *, "
                . "(SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = BC.nationalbankid LIMIT 1) AS initials "
                . "FROM bankaccount AS BC "
                . "ORDER BY initials, accountnumber;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function paymentGetTpaTerminal($accontId) {
        $accontId = $this->real_escape_string($accontId);
        $where = "";
        if ($accontId != -1) {
            $where = " AND accountbankid = '" . $accontId . "' ";
        }
        $sql = "SELECT * "
                . "FROM banktpaterminal  "
                . "WHERE id > 0  " . $where
                . "ORDER BY designation;";
        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function getCountries() {
        $sql = "SELECT * "
                . "FROM country "
                . "ORDER BY id;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function getProvince($companyId) {
        $companyId = $this->real_escape_string($companyId);
        $sql = "SELECT *, \n"
                . "(SELECT COUNT(CD.id) FROM companydependency AS CD WHERE CD.provinceid = P.id AND CD.companyid = '" . $companyId . "' LIMIT 1) AS NumberOfWP \n"
                . "FROM province AS P \n"
                . "ORDER BY province; \n";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
        $this->get_charset()->charset;
    }

    public function getMunicipality($companyId, $provinceId) {
        $companyId = $this->real_escape_string($companyId);
        $provinceId = $this->real_escape_string($provinceId);
        $where = "";
        if ($provinceId != -1) {
            $where = " WHERE provinceid = " . $provinceId . " ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT COUNT(CD.id) FROM companydependency AS CD WHERE CD.municipalityid = M.id  AND CD.companyid = '" . $companyId . "' LIMIT 1) AS NumberOfWP \n"
                . "FROM municipality AS M \n"
                . $where
                . "ORDER BY municipality;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function getNationalBank() {
        $sql = "SELECT * "
                . "FROM nationalbank "
                . "ORDER BY initials;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function getDependences($companyId, $municipalityId) {
        $companyId = $this->real_escape_string($companyId);
        $municipalityId = $this->real_escape_string($municipalityId);
        $where = "";

        $sql = "SELECT *, \n"
                . "(SELECT COUNT(U.id) FROM systemuser AS U WHERE U.workplaceid = CD.id AND companyid = '" . $companyId . "' LIMIT 1) AS NumberOfU \n"
                . "FROM companydependency AS CD \n"
                . "WHERE companyid = '" . $companyId . "' AND municipalityid = '" . $municipalityId . "' \n"
                . "ORDER BY designation;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function bankGetAccountBankList($companyId, $accountId, $availiable = -1) {
        $companyId = $this->real_escape_string($companyId);
        $accountId = $this->real_escape_string($accountId);
        $availiable = $this->real_escape_string($availiable);
        $where = "";
        if ($accountId != -1) {
            $where = " AND accountid = '" . $accountId . "'  ";
        }
        if ($availiable != -1) {
            $where = " AND availiableoninvoice = '" . $availiable . "'  ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT CONCAT(NB.initials, ' - ', NB.bankname) FROM nationalbank AS NB WHERE NB.id = BA.nationalbankid LIMIT 1) AS bankname, \n"
                . "(SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = BA.nationalbankid LIMIT 1) AS initials \n"
                . "FROM bankaccount AS BA "
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY bankname, accountnumber; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function bankAccountSave($companyId, $accountId, $nationalBankId,
            $agency, $accountNumber, $iban, $swift, $availiable) {

        $companyId = $this->real_escape_string($companyId);
        $accountId = $this->real_escape_string($accountId);
        $nationalBankId = $this->real_escape_string($nationalBankId);
        $agency = $this->real_escape_string($agency);
        $accountNumber = $this->real_escape_string($accountNumber);
        $iban = $this->real_escape_string($iban);
        $swift = $this->real_escape_string($swift);
        $availiable = $this->real_escape_string($availiable);

        if ($accountNumber != "") {
            if ($this->validateDuplicateElement($companyId, $accountId, "id",
                            $accountNumber, "bankaccount", "accountnumber") == 1) {
                return json_encode(array("status" => 0, "msg" => "Número de conta bancária já cadastrada."));
            }
        }
        if ($iban != "") {
            if ($this->validateDuplicateElement($companyId, $accountId, "id",
                            $iban, "bankaccount", "iban") == 1) {
                return json_encode(array("status" => 0, "msg" => "IBAN já cadastrado."));
            }
        }

        if (!is_numeric($accountId)) {
            $accountId = $this->bankAccountNew($companyId, $nationalBankId, $accountNumber, $iban);
        }

        $sql = "UPDATE bankaccount SET \n"
                . "agency = '" . $agency . "', "
                . "accountnumber = '" . $accountNumber . "', "
                . "iban = '" . $iban . "', "
                . "swift = '" . $swift . "', "
                . "availiableoninvoice = '" . $availiable . "' \n"
                . "WHERE id = " . $accountId . ";";

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Conta bancária actualizada com sucesso."));
    }

    public function bankAccountNew($companyId, $nationalBankId, $accountNumber, $iban) {

        $companyId = $this->real_escape_string($companyId);
        $nationalBankId = $this->real_escape_string($nationalBankId);
        $accountNumber = $this->real_escape_string($accountNumber);
        $iban = $this->real_escape_string($iban);

        $sql = "INSERT INTO bankaccount (companyid, nationalbankid, accountnumber, iban) \n"
                . "SELECT '" . $companyId . "', '" . $nationalBankId . "', '" . $accountNumber . "', '" . $iban . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS "
                . "(SELECT id FROM bankaccount WHERE companyid = '" . $companyId . "' AND nationalbankid = '" . $nationalBankId . "' AND accountnumber = '" . $accountNumber . "' AND iban = '" . $iban . "');";
        $this->query($sql);
        $sql = "SELECT id FROM bankaccount WHERE companyid = '" . $companyId . "' AND nationalbankid = '" . $nationalBankId . "' AND accountnumber = '" . $accountNumber . "' AND iban = '" . $iban . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function bankDeleteAccountBank($accountId) {
        $accountId = $this->real_escape_string($accountId);

        $sql = "SELECT id "
                . "FROM invoicepayment "
                . "WHERE accountBankTarget = " . $accountId . " "
                . "LIMIT 1; ";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            return json_encode(array("status" => 0, "msg" => "Não pode apagar esta conta bancária, porque existem pagamentos sobre a mesma."));
        }

        $sql = "DELETE FROM bankaccount WHERE id = " . $accountId . "; ";
        $result = $this->query($sql);
        return json_encode(array("status" => 1, "msg" => "Conta apagada com sucesso."));
    }

    public function bankGetTpaTerminalList($companyId, $tpaId) {
        $companyId = $this->real_escape_string($companyId);
        $tpaId = $this->real_escape_string($tpaId);
        $where = "";
        if ($tpaId != -1) {
            $where = " AND id = '" . $tpaId . "'  ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT CONCAT(NB.initials, ' - ', BA.accountnumber) FROM nationalbank AS NB, bankaccount AS BA  "
                . "  WHERE NB.id = BA.nationalbankid AND BA.id = BT.accountbankid LIMIT 1) AS accountbank \n"
                . "FROM banktpaterminal AS BT \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY accountbank, designation; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function bankTpaTerminalNew($companyId, $accountBankId, $designation) {
        $companyId = $this->real_escape_string($companyId);
        $accountBankId = $this->real_escape_string($accountBankId);
        $designation = $this->real_escape_string($designation);

        $sql = "INSERT INTO banktpaterminal (companyid, accountbankid, designation) \n"
                . "SELECT '" . $companyId . "', '" . $accountBankId . "', '" . $designation . "'  \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS "
                . "(SELECT id FROM banktpaterminal WHERE companyid = '" . $companyId . "' AND accountbankid = '" . $accountBankId . "' AND designation = '" . $designation . "');";

        $this->query($sql);

        $sql = "SELECT id FROM banktpaterminal WHERE companyid = '" . $companyId . "' AND accountbankid = '" . $accountBankId . "' AND designation = '" . $designation . "';";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function bankTpaTerminalSave($companyId, $tpaId, $accountBankId, $designation, $holdingTax) {
        $companyId = $this->real_escape_string($companyId);
        $tpaId = $this->real_escape_string($tpaId);
        $accountBankId = $this->real_escape_string($accountBankId);
        $designation = $this->real_escape_string($designation);
        $holdingTax = $this->real_escape_string($holdingTax);

        if ($this->validateDuplicateElement($companyId, $tpaId, "id",
                        $designation, "banktpaterminal", "designation") == 1) {
            return json_encode(array("status" => 0, "msg" => "Terminal de pagamento já cadastrado."));
        }

        if (!is_numeric($tpaId)) {
            $tpaId = silicaDB::getInstance()->bankTpaTerminalNew($companyId, $accountBankId, $designation);
        }


        $sql = "UPDATE banktpaterminal SET "
                . "accountbankid = '" . $accountBankId . "', "
                . "designation = '" . $designation . "', "
                . "holdingtax = '" . $holdingTax . "' "
                . "WHERE id = " . $tpaId . ";";

        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Terminal de pagamento cadastrado com sucesso."));
    }

    public function bankDeleteTpaTerminal($tpaId) {
        $tpaId = $this->real_escape_string($tpaId);

        $sql = "SELECT id "
                . "FROM invoicepayment "
                . "WHERE tpaterminal = " . $tpaId . " "
                . "LIMIT 1; ";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            return json_encode(array("status" => 0, "msg" => "Não pode apagar este TPA, porque existem pagamentos sobre o mesmo."));
        }

        $sql = "DELETE FROM banktpaterminal WHERE id = " . $tpaId . "; ";
        $result = $this->query($sql);
        return json_encode(array("status" => 1, "msg" => "TPA apagada com sucesso."));
    }

    /*     * * INVOICE
     * *********************** */

    private function invoiceSetHash($invoiceType, $invoiceSerie, $invoiceEntryDate, $grossTotal, $invoiceTable) {
        $sequenceNumber = 1;
        $hashControl = 0;
        $hashBeafore = "";
        $hashResult = "";

        $sql = "SELECT invoicesequence, hash \n "
                . "FROM " . $invoiceTable . "  "
                . "WHERE invoicesequence = (SELECT MAX(invoicesequence) FROM " . $invoiceTable . " WHERE invoicetype = '" . $invoiceType . "' AND invoiceserie = '" . $invoiceSerie . "'); ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            $sequenceNumber = $row['invoicesequence'] + 1;
            $hashControl = $row['invoicesequence'];
            $hashBeafore = $row['hash'];
        }
        if (!is_numeric($sequenceNumber)) {
            $sequenceNumber = 1;
        }
        if (!is_numeric($hashControl)) {
            $hashControl = 1;
        }

        $date = date_format($invoiceEntryDate, "Y-m-d");
        $dateTime = date_format($invoiceEntryDate, "Y-m-d\TH:i:s");
        $invoiceNumber = $invoiceType . " " . $invoiceSerie . "/" . $sequenceNumber;

        $stringToHash = $date . ";"
                . $dateTime . ";"
                . $invoiceNumber . ";"
                . $grossTotal;
        if ($hashBeafore != "") {
            $stringToHash .= ";" . $hashBeafore;
        }

        $hashResult = rsaSetHashSign($stringToHash); // _rsaCryptTax::getInstance()->encryptRSA_tax($stringToHash);
        return array("invoiceSequence" => $sequenceNumber, "invoiceNumber" => $invoiceNumber, "hash" => $hashResult, "hashControl" => $hashControl);
    }

    private function invoiceSetInvoiceNumber($invoiceType, $invoiceSerie, $userId, $customerId, $shipLocation) {
        $invoiceType = $this->real_escape_string($invoiceType);
        $invoiceSerie = $this->real_escape_string($invoiceSerie);
        $invoiceSeq = 1;
        $controlHash = "";
        $userId = $this->real_escape_string($userId);
        $customerId = $this->real_escape_string($customerId);
        $shipLocation = $this->real_escape_string($shipLocation);
        $invoiceTable = "workingdocument";
        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceTable = "invoice";
        }


        $fieldsShipTo = ", 'Loja', NOW(), CY.billingcountry, CY.billingprovince, CY.billingmunicipality, "
                . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                . "CY.billingpostalcode \n";
        $fieldShipFrom = $fieldsShipTo;
        $strFrom = "";
        $strWhere = "";

        if ($shipLocation == 0) {
            $fieldsShipTo = ", 'Domicílio', NOW(), C.shipcountry, C.shipprovince, C.shipmunicipality, "
                    . "C.shipcity, C.shipdistrict, C.shipcomuna, C.shipneiborhood, C.shipstreetname, C.shipbuildingnumber, "
                    . "C.shippostalcode \n";
            $strFrom = "";
            $strWhere = "";
        }

        $sql = "SELECT MAX(invoicesequence) AS invoicesequence, hash \n "
                . "FROM " . $invoiceTable . "  "
                . "WHERE invoicetype = '" . $invoiceType . "' AND invoiceserie = '" . $invoiceSerie . "'; ";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $invoiceSeq = $row[0] + 1;
        }

        $invoiceNumber = $invoiceType . " " . $invoiceSerie . "/" . $invoiceSeq;

        $sql = "INSERT INTO " . $invoiceTable . " (invoicetype, invoiceserie, invoicesequence, invoicenumber, "
                . "invoicestatus, invoiceStatusDate, Sourceidstatus, Sourcebilling, invoicedate, "
                . "sourceid, systementrydate, customerid, \n"
                . "deliveryIdShipto, deliverydateshipto, countryshipto, provinceshipto, municipalityshipto, "
                . "cityshipto, districtshipto, comunashipto, neiborhoodshipto, streetnameshipto, buildingnumbershipto, "
                . "postalcodeshipto,  \n"
                . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                . "postalCodeshipfrom, \n"
                . "customername, customertaxid, customercountry, customercity, customerpostalcode, registerid) \n"
                . "SELECT '" . $invoiceType . "', '" . $invoiceSerie . "', '" . $invoiceSeq . "', '" . $invoiceNumber . "', "
                . "'N', NOW(), '" . $userId . "', 'P', NOW(), '" . $userId . "', NOW(), '" . $customerId . "' \n"
                . $fieldsShipTo . $fieldShipFrom
                . ", C.companyname, C.customertaxid, C.billingcountry, C.billingcity, C.billingpostalcode, C.registerid  \n"
                . "FROM company AS CY , customer AS C  \n"
                . "WHERE (NOT EXISTS (SELECT id FROM " . $invoiceTable . " WHERE invoicenumber = '" . $invoiceNumber . "')) "
                . " AND C.customerid = '" . $customerId . "'  LIMIT 1; \n";

        $result = $this->query($sql);
        return $invoiceNumber;
    }

    public function invoiceNewInvoice($shipLocation, $arrayNewInvoice, $arrayInvoiceLines, $arrayPayments, $arrayPaymentsSchedule) {

        $companyId = $this->real_escape_string($arrayNewInvoice['companyId']);
        $dependencyid = $this->real_escape_string($arrayNewInvoice['dependencyid']);
        $userId = $this->real_escape_string($arrayNewInvoice['userId']);
        $customerId = $this->real_escape_string($arrayNewInvoice['customerId']);
        $contractid = $this->real_escape_string($arrayNewInvoice['contractid']);
        $cil = $this->real_escape_string($arrayNewInvoice['cil']);
        $invoiceType = $this->real_escape_string($arrayNewInvoice['invoiceType']);
        $invoiceSerie = $this->real_escape_string($arrayNewInvoice['invoiceSerie']);
        $invoiceSequence = $this->real_escape_string($arrayNewInvoice['invoiceSequence']);
        $invoiceNumber = $this->real_escape_string($arrayNewInvoice['invoiceNumber']);
        /*  $invoiceHash = $this->real_escape_string($arrayNewInvoice['invoiceHash']); */
        $orderReference = $this->real_escape_string($arrayNewInvoice['orderReference']);
        $shelflifedays = $this->real_escape_string($arrayNewInvoice['shelflife']);
        $shelflife = Date('Y-m-d', strtotime('+' . $shelflifedays . ' days'));

        $totalItems = $this->real_escape_string($arrayNewInvoice['totalItems']);
        $subtotalUnitPrice = $this->real_escape_string($arrayNewInvoice['subtotalUnitPrice']);
        $subtotalIndirectCost = $this->real_escape_string($arrayNewInvoice['subtotalIndirectCost']);
        $subtotalCommercialCost = $this->real_escape_string($arrayNewInvoice['subtotalCommercialCost']);
        $subtotalEstimateProfit = $this->real_escape_string($arrayNewInvoice['subtotalEstimateProfit']);
        $subtotalColaborateComission = $this->real_escape_string($arrayNewInvoice['subtotalColaborateComission']);
        $subtotalFund = $this->real_escape_string($arrayNewInvoice['subtotalFund']);
        $subtotalDescount = $this->real_escape_string($arrayNewInvoice['subtotalDescount']);
        $subtotalIva = $this->real_escape_string($arrayNewInvoice['subtotalIva']);
        $subtotalIvaWithoutDescount = $this->real_escape_string($arrayNewInvoice['subtotalIvaWithoutDescount']);
        $totalInvoice = $this->real_escape_string($arrayNewInvoice['totalInvoice']);
        $grosstotal = $this->real_escape_string($arrayNewInvoice['grosstotal']);

        $taxGroup = $this->real_escape_string($arrayNewInvoice['taxGroup']);
        $extraNote = $this->real_escape_string($arrayNewInvoice['extraNote']);
        $paymentamount = $this->real_escape_string($arrayNewInvoice['paymentamount']);
        $change = $this->real_escape_string($arrayNewInvoice['change']);
        $balance = $this->real_escape_string($arrayNewInvoice['balance']);
        $expeditiondate = $this->real_escape_string($arrayNewInvoice['expeditiondate']);
        $sellerUser = $this->real_escape_string($arrayNewInvoice['sellerUser']);
        $managerUser = $this->real_escape_string($arrayNewInvoice['managerUser']);
        $partnershipUser = $this->real_escape_string($arrayNewInvoice['partnershipUser']);
        $WithholdingTaxType = $this->real_escape_string($arrayNewInvoice['WithholdingTaxType']);
        $WithholdingTaxDescription = $this->real_escape_string($arrayNewInvoice['WithholdingTaxDescription']);
        $WithholdingTaxAmount = $this->real_escape_string($arrayNewInvoice['WithholdingTaxAmount']);
        $descountStockOnBilling = $this->real_escape_string($arrayNewInvoice['descountStockOnBilling']);
        $justUpdateOrder = $this->real_escape_string($arrayNewInvoice['justUpdateOrder']);
        $invoiceEntryDate = date_create(date("Y-m-d H:i:s"));
        $printnumber = $invoiceType . $this->getRandomString(10) . round(microtime(true) * 1000);

        if (!$this->checkCustomerInDataBase($customerId)) {
            return json_encode(array("status" => 0, "msg" => "Este cliente foi eliminado da base de dados."));
        }
        if ($orderReference != "") {
            if ($this->checkOriginationIsCanceled($orderReference)) {
                return json_encode(array("status" => 0, "msg" => "Documento de origem anulado."));
            }
        }


        $invoiceTable = "workingdocument";
        $invoiceLineTable = "workingdocumentline";
        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceTable = "invoice";
            $invoiceLineTable = "invoiceline";
        }

        $sql = "";
        if ($justUpdateOrder != 1) {
            $arrayHash = $this->invoiceSetHash($invoiceType, $invoiceSerie, $invoiceEntryDate, $grosstotal, $invoiceTable);
            $invoiceSequence = $arrayHash['invoiceSequence'];
            $invoiceNumber = $arrayHash['invoiceNumber'];
            $hash = $arrayHash['hash'];
            $hashControl = $arrayHash['hashControl'];
            $invoiceEntryDate = date_format($invoiceEntryDate, "Y-m-d\TH:i:s");


            $fieldsShipTo = ", 'Loja', '" . $invoiceEntryDate . "', 1, CY.billingprovince, CY.billingmunicipality, "
                    . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                    . "CY.billingpostalcode \n";
            $fieldShipFrom = $fieldsShipTo;
            $strFrom = "";
            $strWhere = "";

            if ($shipLocation == 0) {
                $fieldsShipTo = ", 'Domicílio', '" . $invoiceEntryDate . "', 1, CT.billingprovince, CT.billingmunicipality, "
                        . "CT.billingcity, CT.billingdistrict, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, CT.billingbuildingnumber, "
                        . "CT.billingpostalcode \n";
                $strFrom = "";
                $strWhere = "";
            }

            $CT_C = "CT";
            $contractTable = ", contract AS CT";
            $whereContract = " AND CT.customerid = C.customerid AND CT.contractid = '" . $contractid . "' ";
            if ($contractid == "") {
                $CT_C = "C";
                $contractTable = "";
                $whereContract = "";
            }
            
            $sql = "INSERT INTO " . $invoiceTable . " (companyid, dependencyid, invoicetype, invoiceserie, invoicesequence, invoicenumber, "
                    . "invoicestatus, invoiceStatusDate, Sourceidstatus, Sourcebilling, hash, hashcontrol, "
                    . "invoicedate, sourceid, systementrydate, customerid, contractid, cil, grosstotal, printnumber, \n"
                    . "deliveryIdShipto, deliverydateshipto, countryshipto, provinceshipto, municipalityshipto, "
                    . "cityshipto, districtshipto, comunashipto, neiborhoodshipto, streetnameshipto, buildingnumbershipto, "
                    . "postalcodeshipto,  \n"
                    . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                    . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                    . "postalCodeshipfrom, \n"
                    . "customername, customertaxid, customercountry, customercity, customerfulladdress, customerpostalcode, customerphone, registerid) \n"
                    . "SELECT '" . $companyId . "', '" . $dependencyid . "', '" . $invoiceType . "', '" . $invoiceSerie . "', '" . $invoiceSequence . "', '" . $invoiceNumber . "', "
                    . "'N', '" . $invoiceEntryDate . "', '" . $userId . "', 'P', '" . $hash . "', '" . $hashControl . "', "
                    . "'" . $invoiceEntryDate . "', '" . $userId . "', '" . $invoiceEntryDate . "', '" . $customerId . "', '" . $contractid . "', '" . $cil . "', '" . $grosstotal . "', '" . $printnumber . "' \n"
                    . $fieldsShipTo . $fieldShipFrom
                    . ", C.companyname, C.customertaxid, 1, "
                    . "(COALESCE((SELECT M.municipality FROM municipality AS M WHERE M.id = " . $CT_C . ".billingmunicipality LIMIT 1), '')), \n"
                    . $CT_C . ".billingneiborhood, " . $CT_C . ".billingpostalcode, C.telephone1, C.registerid  \n"
                    . "FROM company AS CY , customer AS C " . $contractTable . " \n"
                    . "WHERE C.customerid = '" . $customerId . "' " . $whereContract . " \n"
                    . " AND (NOT EXISTS (SELECT id FROM " . $invoiceTable . " WHERE invoicenumber = '" . $invoiceNumber . "')) "
                    . " LIMIT 1; \n";


            //  $invoiceNumber = $this->invoiceSetInvoiceNumber($invoiceType, $invoiceSerie, $userId, $customerId, $shipLocation);
        }

        $sql .= "UPDATE " . $invoiceTable . " SET \n";
        if ($justUpdateOrder == 1) {
            $invoiceEntryDate = date_format($invoiceEntryDate, "Y-m-d\TH:i:s");
            $sql .= "invoiceStatusDate = '" . $invoiceEntryDate . "', "
                    . "Sourceidstatus = '" . $userId . "', ";
        }
        $sql .= "reference = '" . $orderReference . "', "
                . "shelflife = '" . $shelflife . "', "
                . "totalItems = '" . $totalItems . "', "
                . "subtotalUnitPrice = '" . $subtotalUnitPrice . "', "
                . "subtotalIndirectCost = '" . $subtotalIndirectCost . "', "
                . "subtotalCommercialCost = '" . $subtotalCommercialCost . "', "
                . "subtotalEstimateProfit = '" . $subtotalEstimateProfit . "', "
                . "subtotalColaborateComission = '" . $subtotalColaborateComission . "', "
                . "subtotalFund = '" . $subtotalFund . "', "
                . "subtotalDescount = '" . $subtotalDescount . "', "
                . "subtotalIva = '" . $subtotalIva . "', "
                . "subtotalIvaWithoutDescount = '" . $subtotalIvaWithoutDescount . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "taxGroup = '" . $taxGroup . "', "
                . "extraNote = '" . $extraNote . "', "
                . "paymentamount = '" . $paymentamount . "', "
                . "changeamount = '" . $change . "', "
                . "balanceamount = '" . $balance . "', "
                . "expeditiondate = '" . $expeditiondate . "', "
                . "sellerUser = '" . $sellerUser . "', "
                . "managerUser = '" . $managerUser . "', "
                . "partnershipUser = '" . $partnershipUser . "', "
                . "WithholdingTaxType = '" . $WithholdingTaxType . "', "
                . "WithholdingTaxDescription = '" . $WithholdingTaxDescription . "', "
                . "WithholdingTaxAmount = '" . $WithholdingTaxAmount . "'  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $sql = $sql . "INSERT INTO customeraccount ("
                    . "companyid, customerid, contractid, source, reason, amount) \n"
                    . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', '" . $invoiceNumber . "', 'Factura', '-" . $totalInvoice . "' \n"
                    . "FROM insert_table; \n";
            if ($WithholdingTaxAmount > 0) {
                $sql = $sql . "INSERT INTO customeraccount ("
                        . "companyid, customerid, contractid, source, reason, amount) \n"
                        . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', '" . $invoiceNumber . "', 'Retenção na fonte', '" . $WithholdingTaxAmount . "' \n"
                        . "FROM insert_table; \n";
            }
            if ($balance > 0.01) {
                $sql = $sql . "INSERT INTO customeraccount ("
                        . "companyid, customerid, contractid, source, reason, balanceamount) \n"
                        . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', "
                        . "'" . $invoiceNumber . "', 'Saldo transferido', '" . $balance . "' \n"
                        . "FROM insert_table; \n";
            }
        }

        if ($justUpdateOrder == 1) {
            $sql .= "DELETE FROM " . $invoiceLineTable . " WHERE invoiceNumber = '" . $invoiceNumber . "'; \n";
        }
        /*   LINES */
        foreach ($arrayInvoiceLines as $key => $ivLine) {
            $productID = $this->real_escape_string($ivLine['productID']);
            $productType = $this->real_escape_string($ivLine['productType']);
            $productDescription = $this->real_escape_string($ivLine['productDescription']);
            $productBarCode = $this->real_escape_string($ivLine['productBarCode']);
            $productUnit = $this->real_escape_string($ivLine['productUnit']);
            $productSection = $this->real_escape_string($ivLine['productSection']);
            $productIvaCategory = $this->real_escape_string($ivLine['productIvaCategory']);
            $productStock = $this->real_escape_string($ivLine['productStock']);
            $productWeight = $this->real_escape_string($ivLine['productWeight']);
            $productNetWeight = $this->real_escape_string($ivLine['productNetWeight']);
            $devolutiondays = $this->real_escape_string($ivLine['devolutiondays']);
            $devolutionDate = Date('Y-m-d', strtotime('+' . $devolutiondays . ' days'));
            $productStockActual = $this->real_escape_string($ivLine['productStockActual']);

            $periodicService = $this->real_escape_string($ivLine['periodicService']);
            $periodicServiceDateBefore = $this->real_escape_string($ivLine['periodicServiceDateBefore']);
            $periodicServiceDate = $this->real_escape_string($ivLine['periodicServiceDate']);

            $quant = $this->real_escape_string($ivLine['quant']);
            $warehousePrice = $this->real_escape_string($ivLine['warehousePrice']);
            $indirectCost = $this->real_escape_string($ivLine['indirectCost']);
            $commercialCost = $this->real_escape_string($ivLine['commercialCost']);
            $estimatedProfit = $this->real_escape_string($ivLine['estimatedProfit']);
            $sellerComission = $this->real_escape_string($ivLine['sellerComission']);
            $managerComission = $this->real_escape_string($ivLine['managerComission']);
            $fundInvestment = $this->real_escape_string($ivLine['fundInvestment']);
            $fundReserve = $this->real_escape_string($ivLine['fundReserve']);
            $fundSocialaction = $this->real_escape_string($ivLine['fundSocialaction']);

            $priceWithComission = $this->real_escape_string($ivLine['priceWithComission']);
            $creditAmount = $this->real_escape_string($ivLine['creditAmount']);
            $descount = $this->real_escape_string($ivLine['descount']);
            $iva = $this->real_escape_string($ivLine['iva']);
            $ivaValue = $this->real_escape_string($ivLine['ivaValue']);
            $ivaWithoutDescountValue = $this->real_escape_string($ivLine['ivaWithoutDescountValue']);
            $subtotalLine = $this->real_escape_string($ivLine['subtotalLine']);
            $exemptionCode = $this->real_escape_string($ivLine['exemptionCode']);
            $exemptionReason = $this->real_escape_string($ivLine['exemptionReason']);
            $note = (int) $this->real_escape_string($ivLine['note']);
            $gmelicense = $this->real_escape_string($ivLine['gmelicense']);
            $status = $this->real_escape_string($ivLine['status']);

            if ($status != 0) {
                $strInsertLine = "INSERT INTO " . $invoiceLineTable . " (invoiceNumber, customerid, productCode, productDescription, quantity, "
                        . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                        . "taxPointDate, description, creditAmount, taxPercentage, \n"
                        . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                        . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                        . "productWeight, productNetWeight, devolution, \n"
                        . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                        . "sellerComission, managerComission, \n"
                        . "fundInvestment, fundReserve, fundSocialaction, \n"
                        . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note, gmelicense) \n"
                        . "SELECT '" . $invoiceNumber . "', '" . $customerId . "', '" . $productID . "', '" . $productDescription . "', '" . $quant . "', \n"
                        . "'" . $productUnit . "', '" . $warehousePrice . "', '" . $indirectCost . "', '" . $commercialCost . "', '" . $estimatedProfit . "',  \n"
                        . "'" . $invoiceEntryDate . "', '" . $productDescription . "', '" . $creditAmount . "', '" . $iva . "', \n"
                        . "'" . $exemptionReason . "', '" . $exemptionCode . "', '" . $descount . "', \n"
                        . "'" . $productID . "', '" . $productType . "', '" . $productBarCode . "', '" . $productSection . "', '" . $productIvaCategory . "', '" . $productStock . "', \n"
                        . "'" . $productWeight . "', '" . $productNetWeight . "', '" . $devolutionDate . "', \n"
                        . "'" . $periodicService . "', '" . $periodicServiceDateBefore . "', '" . $periodicServiceDate . "', \n"
                        . "'" . $sellerComission . "', '" . $managerComission . "', \n"
                        . "'" . $fundInvestment . "', '" . $fundReserve . "', '" . $fundSocialaction . "', \n"
                        . "'" . $priceWithComission . "', '" . $ivaValue . "', '" . $ivaWithoutDescountValue . "', '" . $subtotalLine . "', '" . $note . "', '" . $gmelicense . "'  \n"
                        . "FROM insert_table \n";
                if (!$note) {
                    $strInsertLine .= "WHERE NOT EXISTS (SELECT id FROM " . $invoiceLineTable . " WHERE invoiceNumber = '" . $invoiceNumber . "' AND productId = '" . $productID . "')";
                }
                $strInsertLine .= "; \n";

                if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
                    if ($productStock == 1) {
                        if ($descountStockOnBilling != 1) {
                            $strInsertLine = $strInsertLine . "INSERT INTO productstockpending ("
                                    . "companyid, invoicenumber, customerid, productid, quantity) \n"
                                    . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', '" . $customerId . "', '" . $productID . "', '" . $quant . "'  \n"
                                    . "FROM insert_table \n"
                                    . "WHERE NOT EXISTS (SELECT id FROM productstockpending WHERE invoicenumber = '" . $invoiceNumber . "' AND productid = '" . $productID . "'); \n";
                        }
                    }
                    if ($periodicService == 1) {
                        $strInsertLine .= "UPDATE customerassigntoservice SET \n"
                                . "lastdate = '" . $periodicServiceDate . "', nopayment = 0 \n"
                                . "WHERE customerid = '" . $customerId . "' AND productsectionid = '" . $productSection . "'; ";
                    }
                    if ($gmelicense > 0) {
                        //Active license
                        for ($index = 1; $index <= $quant; $index++) {
                            $serialnumber = generateSerialNumber();
                            $sql .= "INSERT INTO licenseassigned ("
                                    . "companyid, invoicenumber, productdescription, licensetypeid, serialnumber, entryuser) \n"
                                    . "SELECT COALESCE((SELECT C.contractorid FROM customer AS C WHERE C.customerid = '" . $customerId . "' LIMIT 1), 0), \n"
                                    . "'" . $invoiceNumber . "', '" . $productDescription . "', LP.id, '" . $serialnumber . "', '" . $userId . "' \n"
                                    . "FROM licensetype AS LP \n"
                                    . "WHERE LP.id = '" . $gmelicense . "'; \n";
                        }
                    }
                }

                $sql = $sql . $strInsertLine;
            }
        }

        /* PAYMENTS   */
        foreach ($arrayPayments as $key => $ivPays) {
            $mechanism = $this->real_escape_string($ivPays['mechanism']);
            $banksource = $this->real_escape_string($ivPays['banksource']);
            $accountsource = $this->real_escape_string($ivPays['accountsource']);
            $accounttarget = $this->real_escape_string($ivPays['accounttarget']);
            $tpaterminal = $this->real_escape_string($ivPays['tpaterminal']);
            $reference = $this->real_escape_string($ivPays['reference']);
            $date = $this->real_escape_string($ivPays['date']);
            $amount = $this->real_escape_string($ivPays['amount']);

            $sqlInserPayment = "INSERT INTO invoicepayment ("
                    . "companyid, invoicenumber, customerid, contractid, PaymentMechanism, PaymentAmount, PaymentDate, "
                    . "banksource, accountBankSource, tpaterminal, "
                    . "reference, accountBankTarget)  \n"
                    . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', '" . $customerId . "', '" . $contractid . "', '" . $mechanism . "', '" . $amount . "', '" . $date . "', "
                    . "'" . $banksource . "', '" . $accountsource . "', '" . $tpaterminal . "', "
                    . "'" . $reference . "', '" . $accounttarget . "' \n"
                    . "FROM insert_table; \n";

            if ($mechanism != 5) {
                $sqlInserPayment = $sqlInserPayment . "INSERT INTO customeraccount ("
                        . "companyid, customerid, contractid, source, reason, amount) \n"
                        . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', '" . $invoiceNumber . "', 'Pagamento', '" . $amount . "' \n"
                        . "FROM insert_table; \n";
            } else {
                $sqlInserPayment = $sqlInserPayment . "INSERT INTO customeraccount ("
                        . "companyid, customerid, contractid, source, reason, balanceamount) \n"
                        . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', "
                        . "'" . $invoiceNumber . "', 'Compensação de saldo', '-" . $amount . "' \n"
                        . "FROM insert_table; \n";
            }

            $sql = $sql . $sqlInserPayment;
        }

        if ($change > 0) {// Troco como pagamento
            $sql .= "INSERT INTO invoicepayment ("
                    . "companyid, invoicenumber, customerid, contractid, PaymentMechanism, PaymentAmount, PaymentDate)  \n"
                    . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', '" . $customerId . "', '" . $contractid . "', '100', '-" . $change . "', '" . $invoiceEntryDate . "' \n"
                    . "FROM insert_table ; \n";
            $sql .= "INSERT INTO customeraccount ("
                    . "companyid, customerid, contractid, source, reason, amount) \n"
                    . "SELECT '" . $companyId . "', '" . $customerId . "', '" . $contractid . "', '" . $invoiceNumber . "', 'Troco', '-" . $change . "' \n"
                    . "FROM insert_table ; \n";
        }

        /* PAYMENTS SCHEDULE  */
        foreach ($arrayPaymentsSchedule as $key => $ivPaysSche) {
            $installment = $this->real_escape_string($ivPaysSche['installment']);
            $installmentDate = $this->real_escape_string($ivPaysSche['installmentDate']);
            $paymentAmount = $this->real_escape_string($ivPaysSche['paymentAmount']);

            $sqlInsertPaymentSchedule = "INSERT INTO invoicepaymentschedule ("
                    . "companyid, invoicenumber, customerid, contractid, installment, installmentdate, "
                    . "paymentamount, installmentstatus)  \n"
                    . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', '" . $customerId . "', '" . $contractid . "', '" . $installment . "', '" . $installmentDate . "', "
                    . "'" . $paymentAmount . "', 'AP'  \n"
                    . "FROM insert_table ; \n";

            $sql = $sql . $sqlInsertPaymentSchedule;
        }

        if ($justUpdateOrder == 1) {
            $sql .= "SELECT * \n"
                    . "FROM " . $invoiceTable . "  \n"
                    . "WHERE invoicenumber = '" . $invoiceNumber . "';";
        } else {
            $sql .= "SELECT * \n"
                    . "FROM " . $invoiceTable . "  \n"
                    . "WHERE printnumber = '" . $printnumber . "';";
        }

        unset($ivPaysSche);
        unset($ivPays);
        unset($ivLine);

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $printnumber = $row['printnumber'];
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if ($justUpdateOrder != 1) {
            if (!$saved) {
                return json_encode(array("status" => false, "msg" => "Não foi possível criar este documento.", "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
            }
        }
        return json_encode(array("status" => true, "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
    }

    public function invoiceGetInvoiceDetails($invoiceType, $invoiceNumber, $via) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $via = $this->real_escape_string($via);
        $invoiceTable = "workingdocument"; //
        $extraFields = "(SELECT C.country FROM country AS C WHERE C.id = I.customercountry LIMIT 1) AS customercountryName, \n";
        $extraFields .= "COALESCE((SELECT CT.oldcontractnumber FROM contract AS CT WHERE CT.contractid = I.contractid LIMIT 1), '') AS oldcontractnumber, \n";
        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceTable = "invoice";
        } elseif (($invoiceType == "RG") || ($invoiceType == "NA")) {
            $invoiceTable = " payment ";
        } elseif (($invoiceType == "GR") || ($invoiceType == "GT") || ($invoiceType == "GA") ||
                ($invoiceType == "CI") || ($invoiceType == "AE") || ($invoiceType == "GD")) {
            $invoiceTable = " movimentofgoods ";
            $extraFields .= "(SELECT PS.desigination FROM productstock AS PS WHERE PS.id = I.stocksource LIMIT 1) AS productstock, \n"
                    . "(SELECT C.country FROM country AS C WHERE C.id = I.countryshipfrom LIMIT 1) AS countryshipfromName, \n"
                    . "(SELECT P.province FROM province AS P WHERE P.id = I.provinceshipfrom LIMIT 1) AS provinceshipfromName, \n"
                    . "(SELECT M.municipality FROM municipality AS M WHERE M.id = I.municipalityshipfrom LIMIT 1) AS municipalityshipfromName, \n"
                    . "(SELECT C.country FROM country AS C WHERE C.id = I.countryshipto LIMIT 1) AS countryshiptoName, \n"
                    . "(SELECT P.province FROM province AS P WHERE P.id = I.provinceshipto LIMIT 1) AS provinceshiptoName, \n"
                    . "(SELECT M.municipality FROM municipality AS M WHERE M.id = I.municipalityshipto LIMIT 1) AS municipalityshiptoName, \n";
        } elseif ($invoiceType == "GE") {
            $invoiceTable = " entranceofgoods ";
            $extraFields = "(SELECT S.companyname FROM supplier AS S WHERE S.id = I.supplierid LIMIT 1) AS supplierName, \n"
                    . "(SELECT S.suppliertaxid FROM supplier AS S WHERE S.id = I.supplierid LIMIT 1) AS suppliertaxid, \n"
                    . "(SELECT PS.desigination FROM productstock AS PS WHERE PS.id = I.stocksource LIMIT 1) AS stockout, \n"
                    . "(SELECT PS.desigination FROM productstock AS PS WHERE PS.id = I.stocktarger LIMIT 1) AS stockin, \n"
                    . "(SELECT C.country FROM country AS C WHERE C.id = I.countryshipfrom LIMIT 1) AS countryshipfromName, \n"
                    . "(SELECT P.province FROM province AS P WHERE P.id = I.provinceshipfrom LIMIT 1) AS provinceshipfromName, \n"
                    . "(SELECT M.municipality FROM municipality AS M WHERE M.id = I.municipalityshipfrom LIMIT 1) AS municipalityshipfromName, \n";
        }

        $sql = "SELECT *, '" . $via . "' AS via,  \n"
                . $extraFields
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.sourceid LIMIT 1) AS operatorName, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorStatusName \n"
                . "FROM " . $invoiceTable . "  I \n"
                . "WHERE printnumber = '" . $invoiceNumber . "'; ";

        return $this->query($sql);
    }

    public function invoiceGetInvoiceLines($invoiceType, $invoiceNumber, $invoiceStatus) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $invoiceLineTable = ($invoiceStatus == "A") ? "workingdocumentlinecanceled" : "workingdocumentline";
        $strProductStockActual = "";

        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceLineTable = ($invoiceStatus == "A") ? "invoicelinecanceled" : "invoiceline";
        } elseif (($invoiceType == "GR") || ($invoiceType == "GT") || ($invoiceType == "GA") ||
                ($invoiceType == "CI") || ($invoiceType == "AE") || ($invoiceType == "GD")) {
            $invoiceLineTable = ($invoiceStatus == "A") ? "movimentofgoodslinecanceled" : " movimentofgoodsline ";
        } elseif ($invoiceType == "GE") {
            $invoiceLineTable = ($invoiceStatus == "A") ? "entranceofgoodslinecanceled" : " entranceofgoodsline ";
        } else {
            $workPlaceId = "(SELECT I.dependencyid FROM workingdocument AS I WHERE I.invoicenumber = '" . $invoiceNumber . "') ";
            $stockSource = "(SELECT ST.id FROM productstock AS ST WHERE ST.dependencyid = " . $workPlaceId . " AND ST.billingstock = 1)";
            $strProductStockActual = ", (COALESCE((SELECT SUM(PSA.quantity) AS quantity  FROM productstockactual AS PSA WHERE PSA.stockid IN (" . $stockSource . ") AND PSA.productid = IL.productId LIMIT 1), 0)) AS productstockactual \n";
        }


        $sql = "SELECT *  \n"
                . $strProductStockActual
                . "FROM " . $invoiceLineTable . " AS IL \n"
                . "WHERE invoiceNumber = '" . $invoiceNumber . "'; ";

        return $this->query($sql);
    }

    public function invoiceGetFullTotalItens($invoiceNumber, $invoiceStatus) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $invoiceType = substr($invoiceNumber, 0, 2);
        $invoiceLineTable = ($invoiceStatus == "A") ? "workingdocumentlinecanceled" : "workingdocumentline";

        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceLineTable = ($invoiceStatus == "A") ? "invoicelinecanceled" : "invoiceline";
        } elseif (($invoiceType == "GR") || ($invoiceType == "GT") || ($invoiceType == "GA") ||
                ($invoiceType == "CI") || ($invoiceType == "AE") || ($invoiceType == "GD")) {
            $invoiceLineTable = ($invoiceStatus == "A") ? "movimentofgoodslinecanceled" : " movimentofgoodsline ";
        } elseif ($invoiceType == "GE") {
            $invoiceLineTable = ($invoiceStatus == "A") ? "entranceofgoodslinecanceled" : " entranceofgoodsline ";
        }

        $sql = "SELECT COUNT(id) AS nItens  \n"
                . "FROM " . $invoiceLineTable . " AS IL \n"
                . "WHERE invoiceNumber = '" . $invoiceNumber . "'; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $row['nItens'];
        }
        mysqli_free_result($result);
    }

    public function invoiceGetTaxResume($invoiceType, $invoiceNumber) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $invoiceLineTable = "workingdocumentline";
        if (($invoiceType == "FT") || ($invoiceType == "FR") || ($invoiceType == "VD")) {
            $invoiceLineTable = "invoiceline";
        }
        $sql = "SELECT  taxPercentage, SUM((priceWithComission * quantity)* (1-(settlementAmount/100))) AS base, SUM(ivaValue) AS tax \n"
                . "FROM " . $invoiceLineTable . " \n"
                . "WHERE invoiceNumber = '" . $invoiceNumber . "' \n"
                . "GROUP BY taxPercentage ; ";
        return $this->query($sql);
    }

    public function invoiceGetPayments($invoiceNumber) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);

        $sql = "SELECT  *, \n"
                . "(COALESCE((SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = (SELECT BC.nationalbankid FROM bankaccount AS BC WHERE BC.id = P.accountBankTarget LIMIT 1) LIMIT 1), '')) AS targetBankName \n"
                . "FROM invoicepayment AS P \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "' ; ";

        return $this->query($sql);
    }

    public function invoiceGetPaymentsSchedule($invoiceNumber) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);

        $sql = "SELECT  * \n"
                . "FROM invoicepaymentschedule AS P \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "' ; ";

        return $this->query($sql);
    }

    public function invoiceBillingSettingGet($companyId, $dependencyId, $licenseLevel) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $licenseLevel = $this->real_escape_string($licenseLevel);

        $flds = "checkstockonbilling, onlybillingproductinstock, descountstockonbilling, movimentafterbilling \n";
        if ($licenseLevel <= 2) {
            $flds = "0 AS checkstockonbilling, 0 AS onlybillingproductinstock, 0 AS descountstockonbilling, 0 AS movimentafterbilling \n";
        }

        $sql = "INSERT INTO billingsetting (companyid, dependencyid) \n"
                . "SELECT '" . $companyId . "', '" . $dependencyId . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM billingsetting WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "'); \n";
        $this->query($sql);

        $sql = "SELECT id, saletype, workingdocumenttype, opendescount, extranoteprefix, shelflifedocument,  \n"
                . "validatepaymentreference, " . $flds
                . "FROM billingsetting \n"
                . "WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "'  \n"
                . "LIMIT 1;";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            return json_encode($row);
        }
        mysqli_free_result($result);
    }

    public function invoiceBillingSettingSave($arrayBillingSetting) {

        $companyid = $this->real_escape_string($arrayBillingSetting['companyid']);
        $dependencyid = $this->real_escape_string($arrayBillingSetting['dependencyid']);
        $saletype = $this->real_escape_string($arrayBillingSetting['saletype']);
        $workingdocumenttype = $this->real_escape_string($arrayBillingSetting['workingdocumenttype']);
        $opendescount = $this->real_escape_string($arrayBillingSetting['opendescount']);
        $extranoteprefix = $this->real_escape_string($arrayBillingSetting['extranoteprefix']);
        $shelflifedocument = $this->real_escape_string($arrayBillingSetting['shelflifedocument']);
        $checkstockonbilling = $this->real_escape_string($arrayBillingSetting['checkstockonbilling']);
        $onlybillingproductinstock = $this->real_escape_string($arrayBillingSetting['onlybillingproductinstock']);
        $descountstockonbilling = $this->real_escape_string($arrayBillingSetting['descountstockonbilling']);
        $movimentafterbilling = $this->real_escape_string($arrayBillingSetting['movimentafterbilling']);
        $validatepaymentreference = $this->real_escape_string($arrayBillingSetting['validatepaymentreference']);


        $sql = "UPDATE billingsetting SET \n"
                . "saletype = '" . $saletype . "', "
                . "workingdocumenttype = '" . $workingdocumenttype . "', "
                . "opendescount = '" . $opendescount . "', "
                . "extranoteprefix = '" . $extranoteprefix . "', "
                . "shelflifedocument = '" . $shelflifedocument . "', "
                . "checkstockonbilling = '" . $checkstockonbilling . "', "
                . "onlybillingproductinstock = '" . $onlybillingproductinstock . "', "
                . "descountstockonbilling = '" . $descountstockonbilling . "', "
                . "movimentafterbilling = '" . $movimentafterbilling . "', "
                . "validatepaymentreference = '" . $validatepaymentreference . "' \n"
                . "WHERE companyid = '" . $companyid . "' AND dependencyid = '" . $dependencyid . "'; ";

        $result = $this->query($sql);

        return json_encode(array("status" => 1, "msg" => "Definições de facturação actualizadas com sucesso."));
    }

    public function invoiceCancelWorkingDocument($invoiceNumber, $originationOn, $reason, $userId) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $originationOn = $this->real_escape_string($originationOn);
        $reason = $this->real_escape_string($reason);
        $userId = $this->real_escape_string($userId);
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");

        //Cancel Entrance
        $sql = "UPDATE workingdocument SET \n"
                . "invoicestatus = 'A', "
                . "invoiceStatusDate = '" . $invoiceEntryDate . "', "
                . "Sourceidstatus = '" . $userId . "', "
                . "invoicestatusreason = '" . $reason . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Transfer lines
        $sql .= "INSERT INTO workingdocumentlinecanceled (invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note) \n"
                . "SELECT invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note  \n"
                . "FROM workingdocumentline AS IL  \n"
                . "WHERE IL.invoiceNumber = '" . $invoiceNumber . "'; \n";
        //Delete old lines
        $sql .= "DELETE FROM workingdocumentline WHERE invoiceNumber = '" . $invoiceNumber . "'; \n";

        $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => "Consulta de preço anulada com sucesso."));
    }

    public function invoiceCancelInvoice($arrayInvoiceInfo) {
        $invoiceNumber = $this->real_escape_string($arrayInvoiceInfo['invoicenumber']);
        $originationOn = $this->real_escape_string($arrayInvoiceInfo['reference']);
        $reason = $this->real_escape_string($arrayInvoiceInfo['reason']);
        $userId = $this->real_escape_string($arrayInvoiceInfo['userId']);

        $companyId = $this->real_escape_string($arrayInvoiceInfo['companyid']);
        $contractid = $this->real_escape_string($arrayInvoiceInfo['contractid']);
        $billingdate1 = $this->real_escape_string($arrayInvoiceInfo['billingstartdate']);
        $billingdate2 = $this->real_escape_string($arrayInvoiceInfo['billingenddate']);
        $consumptioninvoice = $this->real_escape_string($arrayInvoiceInfo['consumptioninvoice']);

        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");

        //Cancel 
        $sql = "UPDATE invoice SET \n"
                . "invoicestatus = 'A', "
                . "invoiceStatusDate = '" . $invoiceEntryDate . "', "
                . "Sourceidstatus = '" . $userId . "', "
                . "billingstartdate = date_format(billingstartdate,'%Y-%m-04'), "
                . "billingenddate = date_format(billingenddate,'%Y-%m-09'), "
                . "invoicestatusreason = '" . $reason . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Zero installments
        /*  $sql .= "UPDATE invoicepaymentschedule SET \n"
          . "installmentstatus = 'A', "
          . "installmentstatusdate = '" . $invoiceEntryDate . "', "
          . "installmentpaymentnumber = '', "
          . "realpaymentamount = 0  \n"
          . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n"; */

        $sql .= "DELETE FROM  invoicepaymentschedule \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Customer Account
        $sql .= "UPDATE customeraccount SET "
                . "reason = 'Documento anulado', "
                . "amount = 0 \n"
                . "WHERE source = '" . $invoiceNumber . "'; \n";
        //Transfer lines
        $sql .= "INSERT INTO invoicelinecanceled (invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note) \n"
                . "SELECT invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note  \n"
                . "FROM invoiceline AS IL  \n"
                . "WHERE IL.invoiceNumber = '" . $invoiceNumber . "'; \n";
        //Delete old lines
        $sql .= "DELETE FROM invoiceline WHERE invoiceNumber = '" . $invoiceNumber . "'; \n";
        //Delete pending
        $sql .= "DELETE FROM productstockpending WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        if ($consumptioninvoice == 1) {
            //update consumptions
            $sql .= "UPDATE consumption SET \n"
                    . "consumptionstatus = 1, "
                    . "statususer = '" . $userId . "', "
                    . "statusdate = NOW() \n"
                    . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                    . "(CAST(consumptiondate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) ); ";
            //update hydrometer records
            $sql .= "UPDATE hydrometerrecord SET \n"
                    . "recordstatus = 1, "
                    . "statususer = '" . $userId . "', "
                    . "statusdate = NOW() \n"
                    . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                    . "(CAST(recorddate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) );";
            //update consumption estimated
            $sql .= "UPDATE consumptionestimated SET \n"
                    . "estimatedstatus = 1, "
                    . "statususer = '" . $userId . "', "
                    . "statusdate = NOW() \n"
                    . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                    . "(CAST(estimateddate1 AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) );";
        }


        $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => "Factura anulada com sucesso."));
    }

    private function paymentSetInvoiceNumber($invoiceType, $invoiceSerie) {
        $invoiceType = $this->real_escape_string($invoiceType);
        $invoiceSerie = $this->real_escape_string($invoiceSerie);
        $invoiceSequence = 1;

        $sql = "SELECT MAX(invoicesequence) "
                . "FROM payment  "
                . "WHERE invoicetype = '" . $invoiceType . "' AND invoiceserie = '" . $invoiceSerie . "'; ";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $invoiceSequence = $row[0] + 1;
        }

        $invoiceNumber = $invoiceType . " " . $invoiceSerie . "/" . $invoiceSequence;

        return array("invoiceSequence" => $invoiceSequence, "invoiceNumber" => $invoiceNumber);
    }

    public function invoiceValidatePaymentReference($arrayFilter) {

        $companyId = $this->real_escape_string($arrayFilter['companyId']);
        $accountId = $this->real_escape_string($arrayFilter['accountId']);
        $reference = $this->real_escape_string($arrayFilter['reference']);


        $sql = "SELECT *, \n"
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = IP.customerid LIMIT 1) AS companyname \n"
                . "FROM invoicepayment AS IP \n"
                . "WHERE IP.companyid = '" . $companyId . "' AND IP.accountBankTarget = '" . $accountId . "' AND  "
                . "IP.reference = '" . $reference . "'  \n"
                . "LIMIT 1; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function paymentNewPayment($arrayNewPayment, $arrayPayments, $arrayPaymentsInstallments) {

        $companyid = $this->real_escape_string($arrayNewPayment['companyid']);
        $dependencyid = $this->real_escape_string($arrayNewPayment['dependencyid']);
        $request = $this->real_escape_string($arrayNewPayment['request']);
        $invoicetype = $this->real_escape_string($arrayNewPayment['invoicetype']);
        $invoiceserie = $this->real_escape_string($arrayNewPayment['invoiceserie']);

        $description = $this->real_escape_string($arrayNewPayment['description']);
        $sourceid = $this->real_escape_string($arrayNewPayment['sourceid']);

        $contractid = $this->real_escape_string($arrayNewPayment['contractid']);
        $cil = $this->real_escape_string($arrayNewPayment['cil']);
        $customerid = $this->real_escape_string($arrayNewPayment['customerid']);
        //$originatingon = $this->real_escape_string($arrayNewPayment['originatingon']);
        //$invoicedate = $this->real_escape_string($arrayNewPayment['invoicedate']);

        $diference = $this->real_escape_string($arrayNewPayment['diference']);
        $change = $this->real_escape_string($arrayNewPayment['change']);
        $balance = $this->real_escape_string($arrayNewPayment['balance']);
        $paymentamount = $this->real_escape_string($arrayNewPayment['paymentamount']);
        if ($invoicetype == "NA") {
            $totalInvoice = $balance; // valor do recibo
        } else {
            $totalInvoice = $paymentamount - $change - $balance; // valor do recibo
        }
        $ramaining = $this->real_escape_string($arrayNewPayment['ramaining']);
        $taxGroup = $this->real_escape_string($arrayNewPayment['taxGroup']);
        $extraNote = $this->real_escape_string($arrayNewPayment['extraNote']);
        $coordinatorUser = $this->real_escape_string($arrayNewPayment['coordinatorUser']);
        $managerUser = $this->real_escape_string($arrayNewPayment['managerUser']);
        $directorGeneralUser = $this->real_escape_string($arrayNewPayment['directorGeneralUser']);
        $administratorUser = $this->real_escape_string($arrayNewPayment['administratorUser']);
        $partnershipUser = $this->real_escape_string($arrayNewPayment['partnershipUser']);
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");
        $printnumber = $invoicetype . $this->getRandomString(5) . round(microtime(true) * 1000);

        if (!$this->checkCustomerInDataBase($customerid)) {
            return json_encode(array("status" => 0, "msg" => "Este cliente foi eliminado da base de dados."));
        }

        $arrayIN = $this->paymentSetInvoiceNumber($invoicetype, $invoiceserie);
        $invoiceSequence = $arrayIN['invoiceSequence'];
        $invoiceNumber = $arrayIN['invoiceNumber'];


        $CT_C = "CT";
        $contractTable = ", contract AS CT";
        $whereContract = " AND CT.customerid = C.customerid AND CT.contractid = '" . $contractid . "' ";
        if ($contractid == "") {
            $CT_C = "C";
            $contractTable = "";
            $whereContract = "";
        }


        $sql = "INSERT INTO payment (invoicetype, invoiceserie, invoicesequence, invoicenumber, \n"
                . "transactiondate, sourcepayment, paymentstatus, Sourceidstatus, paymentstatusdate, "
                . "sourceid, systementrydate, customerid, \n"
                . "customername, customertaxid, customercountry, customercity, "
                . "customerfulladdress, customerpostalcode, customerphone, printnumber) \n"
                . "SELECT '" . $invoicetype . "', '" . $invoiceserie . "', '" . $invoiceSequence . "', '" . $invoiceNumber . "', \n"
                . "'" . $invoiceEntryDate . "', 'P', 'N', '" . $sourceid . "', '" . $invoiceEntryDate . "', "
                . "'" . $sourceid . "', '" . $invoiceEntryDate . "', '" . $customerid . "', \n"
                . "C.companyname, C.customertaxid, 1, \n"
                . "(COALESCE((SELECT municipality FROM municipality AS M WHERE M.id = " . $CT_C . ".billingmunicipality LIMIT 1), '')), "
                . $CT_C . ".billingneiborhood, " . $CT_C . ".billingpostalcode, C.telephone1, '" . $printnumber . "'   \n"
                . "FROM customer AS C" . $contractTable . "  \n"
                . "WHERE (NOT EXISTS (SELECT id FROM payment WHERE invoicenumber = '" . $invoiceNumber . "')) \n"
                . $whereContract . " AND C.customerid = '" . $customerid . "'  LIMIT 1; \n";

        $sql .= "UPDATE payment SET \n"
                . "companyid = '" . $companyid . "', "
                . "dependencyid = '" . $dependencyid . "', "
                . "contractid = '" . $contractid . "', "
                . "cil = '" . $cil . "', "
                . "request = '" . $request . "', "
                . "description = '" . $description . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "ramaining = '" . $ramaining . "', "
                . "diference = '" . $diference . "', "
                . "changeamount = '" . $change . "', "
                . "balanceamount = '" . $balance . "', "
                . "paymentamount = '" . $paymentamount . "', "
                . "nettotal = '" . $totalInvoice . "', "
                . "grosstotal = '" . $totalInvoice . "', "
                . "extraNote = '" . $extraNote . "', \n"
                . "coordinatorUser = '" . $coordinatorUser . "', "
                . "managerUser = '" . $managerUser . "', "
                . "directorGeneralUser = '" . $directorGeneralUser . "', "
                . "administratorUser = '" . $administratorUser . "', "
                . "partnershipUser = '" . $partnershipUser . "', "
                . "printnumber = '" . $printnumber . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        if ($balance > 0.01) {
            $sql = $sql . "INSERT INTO customeraccount ("
                    . "companyid, customerid, contractid, source, reason, balanceamount) \n"
                    . "SELECT '" . $companyid . "', '" . $customerid . "', '" . $contractid . "', "
                    . "'" . $invoiceNumber . "', 'Saldo transferido', '" . $balance . "' \n"
                    . "FROM insert_table; \n";
        }


        /* PAYMENTS   */
        foreach ($arrayPayments as $key => $ivPays) {
            $mechanism = $this->real_escape_string($ivPays['mechanism']);
            $banksource = $this->real_escape_string($ivPays['banksource']);
            $accountsource = $this->real_escape_string($ivPays['accountsource']);
            $accounttarget = $this->real_escape_string($ivPays['accounttarget']);
            $tpaterminal = $this->real_escape_string($ivPays['tpaterminal']);
            $reference = $this->real_escape_string($ivPays['reference']);
            $date = $this->real_escape_string($ivPays['date']);
            $amount = $this->real_escape_string($ivPays['amount']);

            $sqlInserPayment = "INSERT INTO invoicepayment ("
                    . "companyid, invoicenumber, contractid, customerid, PaymentMechanism, PaymentAmount, PaymentDate, "
                    . "banksource, accountBankSource, tpaterminal, "
                    . "reference, accountBankTarget)  \n"
                    . "SELECT '" . $companyid . "', '" . $invoiceNumber . "', '" . $contractid . "', '" . $customerid . "', '" . $mechanism . "', '" . $amount . "', '" . $date . "', "
                    . "'" . $banksource . "', '" . $accountsource . "', '" . $tpaterminal . "', "
                    . "'" . $reference . "', '" . $accounttarget . "' \n"
                    . "FROM insert_table; \n";

            if ($mechanism != 5) {
                $reason = "Pagamento";
                if ($invoicetype == "NA") {
                    $reason = "Adiantamento";
                }
                $sqlInserPayment = $sqlInserPayment . "INSERT INTO customeraccount ("
                        . "companyid, contractid, customerid, source, reason, amount) \n"
                        . "SELECT '" . $companyid . "', '" . $contractid . "','" . $customerid . "', '" . $invoiceNumber . "', '" . $reason . "', '" . $amount . "' \n"
                        . "FROM insert_table; \n";
            } else {
                $sqlInserPayment = $sqlInserPayment . "INSERT INTO customeraccount ("
                        . "companyid, customerid, contractid, source, reason, balanceamount) \n"
                        . "SELECT '" . $companyid . "', '" . $customerid . "', '" . $contractid . "', "
                        . "'" . $invoiceNumber . "', 'Compensação de saldo', '-" . $amount . "' \n"
                        . "FROM insert_table; \n";
            }

            $sql .= $sqlInserPayment;
        }

        if ($change > 0) {// Troco como pagamento
            $sql .= "INSERT INTO invoicepayment ("
                    . "companyid, invoicenumber, contractid, customerid, PaymentMechanism, PaymentAmount, PaymentDate)  \n"
                    . "SELECT '" . $companyid . "', '" . $invoiceNumber . "', '" . $contractid . "', '" . $customerid . "', '100', '-" . $change . "', '" . $invoiceEntryDate . "' \n"
                    . "FROM insert_table ; \n";
            $sql .= "INSERT INTO customeraccount ("
                    . "companyid, contractid, customerid, source, reason, amount) \n"
                    . "SELECT '" . $companyid . "', '" . $contractid . "', '" . $customerid . "', '" . $invoiceNumber . "', 'Troco', '-" . $change . "' \n"
                    . "FROM insert_table ; \n";
        }

        /* INSTALLMENTS  */
        foreach ($arrayPaymentsInstallments as $key => $payInstall) {
            $installId = $this->real_escape_string($payInstall['id']);
            $originatingon = $this->real_escape_string($payInstall['originatingon']);
            $invoicedate = $this->real_escape_string($payInstall['invoicedate']);
            $installment = $this->real_escape_string($payInstall['installment']);
            $installmentdate = $this->real_escape_string($payInstall['installmentdate']);
            $paymentamount = $this->real_escape_string($payInstall['paymentinfault']);
            $creditamount = $this->real_escape_string($payInstall['realpaymentamount']);
            $statuschange = $this->real_escape_string($payInstall['statuschange']);
            $installmentstatus = $this->real_escape_string($payInstall['installmentstatus']);
            $realpaymentamount = $this->real_escape_string($payInstall['realpaymentamount']);
            $description = ($installmentstatus == "PP") ? "PAGAMENTO PARCIAL" : "PAGAMENTO TOTAL";

            if ($statuschange == 1) {
                if ($originatingon != "") {
                    if ($this->checkOriginationIsCanceled($originatingon)) {
                        return json_encode(array("status" => 0, "msg" => "Documento de origem (" . $originatingon . ") anulado. "));
                    }
                }

                $sqlUpdateInstallment = "INSERT INTO paymentline (\n"
                        . "companyid, customerid, contractid, invoicenumber, "
                        . "description, originatingon, invoicedate, creditamount, \n"
                        . "taxexemptionreason, taxexemptioncode, installment, "
                        . "installmentdate, paymentamount, realpaymentamount) \n"
                        . "SELECT '" . $companyid . "', '" . $customerid . "', '" . $contractid . "', '" . $invoiceNumber . "', \n"
                        . "'" . $description . "', '" . $originatingon . "', '" . $invoicedate . "', '" . $creditamount . "', "
                        . "'Transmissão de bens e serviço não sujeita', 'M02', '" . $installment . "', \n"
                        . "'" . $installmentdate . "', '" . $paymentamount . "', '" . $realpaymentamount . "' \n"
                        . "FROM insert_table ; \n";

                $sqlUpdateInstallment .= "UPDATE invoicepaymentschedule SET \n"
                        . "installmentstatus = '" . $installmentstatus . "', "
                        . "installmentstatusdate = '" . $invoiceEntryDate . "', "
                        . "installmentpaymentnumber = '" . $invoiceNumber . "', "
                        . "realpaymentamount = (realpaymentamount + '" . $realpaymentamount . "')  \n"
                        . "WHERE id = '" . $installId . "'; \n";

                $sqlUpdateInstallment .= "UPDATE invoice SET \n"
                        . "paymentamount = (paymentamount + '" . $realpaymentamount . "') \n"
                        . "WHERE invoicenumber = '" . $originatingon . "'; \n";

                $sqlUpdateInstallment .= "UPDATE invoice SET \n"
                        . "paymentinfaultperc = ((totalInvoice - paymentamount)/totalInvoice) \n"
                        . "WHERE invoicenumber = '" . $originatingon . "'; \n";

                $sql .= $sqlUpdateInstallment;
            }
        }

        unset($ivPays);
        unset($payInstall);

        $sql .= "SELECT * \n"
                . "FROM payment \n"
                . "WHERE printnumber = '" . $printnumber . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => false, "msg" => "Não foi possível criar este documento.", "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
        }

        return json_encode(array("status" => true, "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
    }

    public function paymentImportLoteSave($arrayNewPayment) {

        $companyid = $this->real_escape_string($arrayNewPayment['companyid']);
        $dependencyid = $this->real_escape_string($arrayNewPayment['dependencyid']);
        $invoiceNumber = $this->real_escape_string($arrayNewPayment['invoiceNumber']);
        $invoicedate = $this->real_escape_string($arrayNewPayment['invoicedate']);
        $request = 10;
        $invoicetype = "RG";

        $description = "Documento importado";
        $sourceid = $this->real_escape_string($arrayNewPayment['userId']);

        $customerid = $this->real_escape_string($arrayNewPayment['customertid']);

        $diference = 0;
        $change = 0;
        $balance = 0;
        $paymentamount = $this->real_escape_string($arrayNewPayment['totalinvoice']);
        $totalInvoice = $paymentamount; // - $change - $balance; // valor do recibo
        $ramaining = 0;
        $taxGroup = 1;
        $extraNote = "";
        $coordinatorUser = 0;
        $managerUser = 0;
        $directorGeneralUser = 0;
        $administratorUser = 0;
        $partnershipUser = 0;
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");
        $printnumber = $invoicetype . $this->getRandomString(5) . round(microtime(true) * 1000);

        $invoiceNumber = $invoicetype . " " . $invoiceNumber;


        //Check invoice number
        $sql = "SELECT invoicenumber \n"
                . "FROM payment \n"
                . "WHERE companyid = '" . $companyid . "' AND invoicenumber = '" . $invoiceNumber . "'; ";
        $checkInvoice = $this->query($sql);
        if ($checkInvoice->num_rows > 0) {
            mysqli_free_result($checkInvoice);
            echo json_encode(array("status" => 0, "msg" => "Recibo já foi cadastrado."));
            return false;
        }

        //Check customer number
        $sql = "SELECT customerid \n"
                . "FROM customer \n"
                . "WHERE companyid = '" . $companyid . "' AND customerid = '" . $customerid . "'; ";
        $checkCustomer = $this->query($sql);
        if ($checkCustomer->num_rows <= 0) {
            mysqli_free_result($checkCustomer);
            echo json_encode(array("status" => 0, "msg" => "Cliente não encontrado."));
            return false;
        }

        //Check contract number
        $sql = "SELECT contractid \n"
                . "FROM contract \n"
                . "WHERE companyid = '" . $companyid . "' AND customerid = '" . $customerid . "'; ";
        $checkContract = $this->query($sql);
        if ($checkContract->num_rows <= 0) {
            mysqli_free_result($checkContract);
            echo json_encode(array("status" => 0, "msg" => "Contracto não encontrado."));
            return false;
        }



        $sql = "INSERT INTO payment (invoicetype, invoicenumber, \n"
                . "transactiondate, sourcepayment, paymentstatus, Sourceidstatus, paymentstatusdate, "
                . "systementrydate, customerid, contractid, \n"
                . "customername, customertaxid, customercountry, customercity, "
                . "customerfulladdress, customerpostalcode, customerphone, printnumber) \n"
                . "SELECT '" . $invoicetype . "', '" . $invoiceNumber . "', \n"
                . "'" . $invoicedate . "', 'I', 'N', '" . $sourceid . "', '" . $invoiceEntryDate . "', "
                . "'" . $invoiceEntryDate . "', '" . $customerid . "', CT.contractid, \n"
                . "C.companyname, C.customertaxid, 1, \n"
                . "(COALESCE((SELECT municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1), '')), "
                . "CT.billingneiborhood, CT.billingpostalcode, C.telephone1, '" . $printnumber . "'   \n"
                . "FROM customer AS C, contract AS CT  \n"
                . "WHERE (NOT EXISTS (SELECT id FROM payment WHERE invoicenumber = '" . $invoiceNumber . "')) AND \n"
                . "CT.customerid = C.customerid AND C.customerid = '" . $customerid . "'  LIMIT 1; \n";

        $sql .= "UPDATE payment SET \n"
                . "companyid = '" . $companyid . "', "
                . "dependencyid = '" . $dependencyid . "', "
                . "request = '" . $request . "', "
                . "description = '" . $description . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "ramaining = '" . $ramaining . "', "
                . "diference = '" . $diference . "', "
                . "changeamount = '" . $change . "', "
                . "balanceamount = '" . $balance . "', "
                . "paymentamount = '" . $paymentamount . "', "
                . "nettotal = '" . $totalInvoice . "', "
                . "grosstotal = '" . $totalInvoice . "', "
                . "extraNote = '" . $extraNote . "', \n"
                . "coordinatorUser = '" . $coordinatorUser . "', "
                . "managerUser = '" . $managerUser . "', "
                . "directorGeneralUser = '" . $directorGeneralUser . "', "
                . "administratorUser = '" . $administratorUser . "', "
                . "partnershipUser = '" . $partnershipUser . "', "
                . "printnumber = '" . $printnumber . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

//ACCOUNT
        $sql .= "INSERT INTO customeraccount ("
                . "companyid, contractid, customerid, source, reason, amount) \n"
                . "SELECT '" . $companyid . "', CT.contractid, '" . $customerid . "', '" . $invoiceNumber . "', 'Pagamento', '" . $totalInvoice . "' \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.customerid = '" . $customerid . "' \n"
                . "LIMIT 1; \n";

        /* INSTALLMENTS  */
        $partSQL = "SELECT *, invoicenumber AS originatingon, \n"
                . "(SELECT I.invoicedate FROM invoice AS I WHERE I.invoicenumber = IPS.invoicenumber LIMIT 1) AS invoicedate, \n"
                . "(paymentamount - realpaymentamount) AS paymentinfault \n"
                . "FROM invoicepaymentschedule AS IPS \n"
                . "WHERE companyid = '" . $companyid . "' AND customerid = '" . $customerid . "' AND \n"
                . "((paymentamount - realpaymentamount) > '0.01') \n"
                . "ORDER BY id ;";
        $resultInstallment = $this->query($partSQL);

        $totalAmount = $totalInvoice;
        while ($row = mysqli_fetch_array($resultInstallment)) {
            $installId = $this->real_escape_string($row['id']);
            $originatingon = $this->real_escape_string($row['originatingon']);
            $invoicedate = $this->real_escape_string($row['invoicedate']);
            $installment = $this->real_escape_string($row['installment']);
            $installmentdate = $this->real_escape_string($row['installmentdate']);
            $paymentamount = $this->real_escape_string($row['paymentinfault']);
            $realpaymentamount = 0;
            if ($paymentamount <= $totalAmount) {
                $realpaymentamount = $paymentamount;
                $installmentstatus = "PT";
            } else {
                $realpaymentamount = $totalAmount;
                $installmentstatus = "PP";
            }
            $totalAmount -= $realpaymentamount;
            $creditamount = $realpaymentamount;

            $description = ($installmentstatus == "PP") ? "PAGAMENTO PARCIAL" : "PAGAMENTO TOTAL";

            if ($realpaymentamount > 0) {
                $sqlUpdateInstallment = "INSERT INTO paymentline (\n"
                        . "companyid, customerid, contractid, invoicenumber, "
                        . "description, originatingon, invoicedate, creditamount, \n"
                        . "taxexemptionreason, taxexemptioncode, installment, "
                        . "installmentdate, paymentamount, realpaymentamount) \n"
                        . "SELECT '" . $companyid . "', '" . $customerid . "', CT.contractid, '" . $invoiceNumber . "', \n"
                        . "'" . $description . "', '" . $originatingon . "', '" . $invoicedate . "', '" . $creditamount . "', "
                        . "'Transmissão de bens e serviço não sujeita', 'M02', '" . $installment . "', \n"
                        . "'" . $installmentdate . "', '" . $paymentamount . "', '" . $realpaymentamount . "' \n"
                        . "FROM contract AS CT \n"
                        . "WHERE CT.customerid = '" . $customerid . "' \n"
                        . "LIMIT 1; \n";

                $sqlUpdateInstallment .= "UPDATE invoicepaymentschedule SET \n"
                        . "installmentstatus = '" . $installmentstatus . "', "
                        . "installmentstatusdate = '" . $invoiceEntryDate . "', "
                        . "installmentpaymentnumber = '" . $invoiceNumber . "', "
                        . "realpaymentamount = (realpaymentamount + '" . $realpaymentamount . "')  \n"
                        . "WHERE id = '" . $installId . "'; \n";

                $sqlUpdateInstallment .= "UPDATE invoice SET \n"
                        . "paymentamount = (paymentamount + '" . $realpaymentamount . "') \n"
                        . "WHERE invoicenumber = '" . $originatingon . "'; \n";


                $sql .= $sqlUpdateInstallment;
            }
        }
        mysqli_free_result($resultInstallment);

        $sql .= "SELECT * \n"
                . "FROM payment \n"
                . "WHERE printnumber = '" . $printnumber . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => false, "msg" => "Não foi possível criar este documento.", "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
        }

        return json_encode(array("status" => true, "msg" => "Processado com sucesso"));
    }

    public function paymentCancelPayment($invoiceNumber, $originationOn, $reason, $userId) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $originationOn = $this->real_escape_string($originationOn);
        $reason = $this->real_escape_string($reason);
        $userId = $this->real_escape_string($userId);
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");

        //Cancel payment
        $sql = "UPDATE payment SET \n"
                . "paymentstatus = 'A', "
                . "paymentstatusdate = '" . $invoiceEntryDate . "', "
                . "Sourceidstatus = '" . $userId . "', "
                . "reason = '" . $reason . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Remove payment on invoice ************** Zero installments
        $partSql = "SELECT * \n"
                . "FROM paymentline \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "';";
        $resultPaymentLine = $this->query($partSql);
        while ($row = mysqli_fetch_array($resultPaymentLine)) {
            $sql .= "UPDATE invoice AS I SET \n"
                    . "I.paymentamount = (I.paymentamount - '" . $row['realpaymentamount'] . "')  \n"
                    . "WHERE invoicenumber = '" . $row['originatingon'] . "'; \n";

            $sql .= "UPDATE invoice SET \n"
                    . "paymentinfaultperc = ((totalInvoice - paymentamount)/totalInvoice) \n"
                    . "WHERE invoicenumber = '" . $row['originatingon'] . "'; \n";
            
            //Zero installments
            $sql .= "UPDATE invoicepaymentschedule AS IPS SET \n"
                    . "installmentstatus = 'AP', "
                    . "installmentstatusdate = '" . $invoiceEntryDate . "', "
                    . "installmentpaymentnumber = '', "
                    . "realpaymentamount = (IPS.realpaymentamount - '" . $row['realpaymentamount'] . "')  \n"
                    . "WHERE invoicenumber = '" . $row['originatingon'] . "' AND installment = '" . $row['installment'] . "'; \n";
        }
        mysqli_free_result($resultPaymentLine);
        //Move to paymentlinecanceled
        $sql .= "INSERT INTO paymentlinecanceled (\n"
                . "companyid, customerid, contractid, invoicenumber, description, originatingon, invoicedate, "
                . "debitamount, creditamount, taxexemptionreason, taxexemptioncode, "
                . "installment, installmentdate, paymentamount, realpaymentamount) \n"
                . "SELECT companyid, customerid, contractid, invoicenumber, description, originatingon, invoicedate, "
                . "debitamount, creditamount, taxexemptionreason, taxexemptioncode, "
                . "installment, installmentdate, paymentamount, realpaymentamount \n"
                . "FROM paymentline \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Delete paymentline
        $sql .= "DELETE FROM paymentline \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Customer Account
        $sql .= "UPDATE customeraccount SET "
                . "reason = 'Documento anulado', "
                . "balanceamount = 0, "
                . "amount = 0 \n"
                . "WHERE source = '" . $invoiceNumber . "'; \n";
        //Confirm operation
        $sql .= "SELECT paymentstatus \n"
                . "FROM payment  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['paymentstatus'] == "A") {
                            $saved = true;
                        }
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não possível anular este recibo."));
        }

        return json_encode(array("status" => 1, "msg" => "Recibo anulado com sucesso."));
    }

    public function paymentGetPaymentLines($invoiceType, $invoiceNumber, $invoiceStatus) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $invoiceLineTable = ($invoiceStatus == "A") ? "paymentlinecanceled" : "paymentline";

        $sql = "SELECT *,  \n"
                . "(SELECT I.consumptioninvoice FROM invoice AS I WHERE I.invoicenumber = IL.originatingon LIMIT 1) AS consumptioninvoice, \n"
                . "(SELECT I.billingstartdate FROM invoice AS I WHERE I.invoicenumber = IL.originatingon LIMIT 1) AS billingstartdate \n"
                . "FROM " . $invoiceLineTable . " AS IL \n"
                . "WHERE invoiceNumber = '" . $invoiceNumber . "'; ";

        return $this->query($sql);
    }

    public function paymentReviewBalanceOnCurrentAccount() {

        $sql = "SELECT companyid, invoicenumber, invoicetype, customerid, contractid, \n"
                . "(COALESCE((SELECT SUM(IP.PaymentAmount) FROM invoicepayment AS IP WHERE IP.invoicenumber = P.invoicenumber), 0)) AS realBalance \n"
                . "FROM payment AS P \n"
                . "WHERE invoicetype = 'NA' AND P.totalInvoice <= 0; \n";

        $list = $this->query($sql);
        $sql = "";
        while ($row = mysqli_fetch_array($list)) {
            $companyid = $row['companyid'];
            $invoiceNumber = $row['invoicenumber'];
            $customerId = $row['customerid'];
            $contractId = $row['contractid'];
            $realBalance = $row['realBalance'];

            $sql .= "UPDATE payment SET \n"
                    . "totalInvoice = '" . $realBalance . "', "
                    . "balanceamount = '" . $realBalance . "' \n"
                    . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
            $sql .= "INSERT INTO customeraccount ("
                    . "companyid, customerid, contractid, source, reason, balanceamount) \n"
                    . "SELECT '" . $companyid . "', '" . $customerId . "', '" . $contractId . "', "
                    . "'" . $invoiceNumber . "', 'Saldo transferido', '" . $realBalance . "' \n"
                    . "FROM insert_table; \n";
        }

        $sql .= "SELECT id \n"
                . "FROM payment \n"
                . "WHERE invoicetype = 'NA' AND P.totalInvoice <= 0; ";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => false, "msg" => "Não foi possível actualizar"));
        }

        return json_encode(array("status" => true, "msg" => "Actualização com sucesso"));
    }

    /*     * * ENTRANCE OF GOODS
     * *********************** */

    private function entranceSetEntranceNumber($companyId, $invoiceType, $invoiceSerie, $userId, $supplierId) {
        $companyId = $this->real_escape_string($companyId);
        $invoiceType = $this->real_escape_string($invoiceType);
        $invoiceSerie = $this->real_escape_string($invoiceSerie);
        $invoiceSeq = 1;
        $userId = $this->real_escape_string($userId);
        $supplierId = $this->real_escape_string($supplierId);

        $fieldShipFrom = ", 'Loja', NOW(), CY.billingcountry, CY.billingprovince, CY.billingmunicipality, "
                . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                . "CY.billingpostalcode \n";
        $strFrom = "FROM company AS CY \n";
        $strWhere = " AND CY.companyid = '" . $companyId . "' ";

        if ($supplierId > 0) {
            $fieldShipFrom = ", 'Domicílio', NOW(), S.billingcountry, S.billingprovince, S.billingmunicipality, "
                    . "S.billingcity, S.billingdistrict, S.billingcomuna, S.billingneiborhood, S.billingstreetname, S.billingbuildingnumber, "
                    . "S.billingpostalcode \n";
            $strFrom = "FROM supplier AS S \n";
            $strWhere = " AND S.id = '" . $supplierId . "' ";
        }

        $sql = "SELECT MAX(invoicesequence) "
                . "FROM entranceofgoods  "
                . "WHERE invoicetype = '" . $invoiceType . "' AND invoiceserie = '" . $invoiceSerie . "'; ";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $invoiceSeq = $row[0] + 1;
        }

        $invoiceNumber = $invoiceType . " " . $invoiceSerie . "/" . $invoiceSeq;

        $sql = "INSERT INTO entranceofgoods (companyid, invoicetype, invoiceserie, invoicesequence, invoicenumber, "
                . "invoicestatus, invoiceStatusDate, Sourceidstatus, Sourcebilling, invoicedate, "
                . "sourceid, systementrydate, supplierid, \n"
                . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                . "postalCodeshipfrom) \n"
                . "SELECT '" . $companyId . "', '" . $invoiceType . "', '" . $invoiceSerie . "', '" . $invoiceSeq . "', '" . $invoiceNumber . "', "
                . "'N', NOW(), '" . $userId . "', 'P', NOW(), '" . $userId . "', NOW(), '" . $supplierId . "' \n"
                . $fieldShipFrom
                . $strFrom . " \n"
                . "WHERE (NOT EXISTS (SELECT id FROM entranceofgoods WHERE invoicenumber = '" . $invoiceNumber . "')) "
                . $strWhere . " LIMIT 1; \n";

        $result = $this->query($sql);
        return $invoiceNumber;
    }

    public function entranceNewInvoice($arrayNewEntrance, $arrayEntranceLines) {
        $companyId = $this->real_escape_string($arrayNewEntrance['companyId']);
        $dependencyid = $this->real_escape_string($arrayNewEntrance['dependencyid']);
        $userId = $this->real_escape_string($arrayNewEntrance['userId']);
        $invoiceType = $this->real_escape_string($arrayNewEntrance['invoiceType']);
        $invoiceSerie = $this->real_escape_string($arrayNewEntrance['invoiceSerie']);
        $supplierId = $this->real_escape_string($arrayNewEntrance['supplier']);
        $stocksource = $this->real_escape_string($arrayNewEntrance['stocksource']);
        $totalItems = $this->real_escape_string($arrayNewEntrance['totalItems']);
        $movimentsource = $this->real_escape_string($arrayNewEntrance['movimentsource']);
        $reference = $this->real_escape_string($arrayNewEntrance['reference']);
        $movimentDate = $this->real_escape_string($arrayNewEntrance['movimentDate']);
        $stockTarget = $this->real_escape_string($arrayNewEntrance['stockTarget']);
        $printnumber = $invoiceType . $this->getRandomString(5) . round(microtime(true) * 1000);

        if (!is_numeric($stocksource)) {
            $stocksource = -1;
        }

        $invoiceNumber = $this->entranceSetEntranceNumber($companyId, $invoiceType, $invoiceSerie, $userId, $supplierId);

        $sql = "UPDATE entranceofgoods SET \n"
                . "totalItems = '" . $totalItems . "', "
                . "dependencyid = '" . $dependencyid . "', "
                . "movimentsource = '" . $movimentsource . "', "
                . "stocksource = '" . $stocksource . "', "
                . "movimentereference = '" . $reference . "', "
                . "movimentereferencedate = '" . $movimentDate . "', "
                . "stocktarger = '" . $stockTarget . "', "
                . "printnumber = '" . $printnumber . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";


        /*   LINES */
        foreach ($arrayEntranceLines as $key => $ivLine) {
            $productID = $this->real_escape_string($ivLine['productID']);
            $productDescription = $this->real_escape_string($ivLine['productDescription']);
            $productUnit = $this->real_escape_string($ivLine['productUnit']);
            $productSection = $this->real_escape_string($ivLine['productSection']);
            $productIvaCategory = $this->real_escape_string($ivLine['productIvaCategory']);
            $productStock = $this->real_escape_string($ivLine['productStock']);
            $quant = $this->real_escape_string($ivLine['quant']);
            $serialnumber = $this->real_escape_string($ivLine['serialnumber']);
            $lotenumber = $this->real_escape_string($ivLine['lotenumber']);
            $note = (int) $this->real_escape_string($ivLine['note']);
            $expirationdate = $this->real_escape_string($ivLine['expirationdate']);
            $status = $this->real_escape_string($ivLine['status']);

            if ($status != 0) {
                $strInsertLine = "INSERT INTO entranceofgoodsline (invoiceNumber, productDescription, quantity, "
                        . "unitOfMeasure, serialnumber, lotenumber, expirationdate, "
                        . "productId, productSection, productIvaCategory, productStock, note) \n"
                        . "SELECT '" . $invoiceNumber . "', '" . $productDescription . "', '" . $quant . "', "
                        . "'" . $productUnit . "', '" . $serialnumber . "', '" . $lotenumber . "', '" . $expirationdate . "', "
                        . "'" . $productID . "', '" . $productSection . "', '" . $productIvaCategory . "', "
                        . "'" . $productStock . "',  '" . $note . "'  \n"
                        . "FROM insert_table \n"
                        . "WHERE NOT EXISTS (SELECT id FROM entranceofgoodsline WHERE invoiceNumber = '" . $invoiceNumber . "' AND productId = '" . $productID . "'); \n";

                if ($note != 1) {
                    $strInsertLine .= "UPDATE productstockactual SET "
                            . "quantity = quantity + '" . $quant . "'  \n"
                            . "WHERE stockid = '" . $stockTarget . "' AND productid = '" . $productID . "'; \n";

                    if ($stocksource != -1) {
                        $strInsertLine .= "UPDATE productstockactual SET "
                                . "quantity = quantity - '" . $quant . "'  \n"
                                . "WHERE stockid = '" . $stocksource . "' AND productid = '" . $productID . "'; \n";
                    }

                    $strInsertLine .= "INSERT INTO productstockactual ("
                            . "stockid, productid, quantity) \n"
                            . "SELECT  '" . $stockTarget . "', '" . $productID . "', '" . $quant . "'  \n"
                            . "FROM insert_table \n"
                            . "WHERE NOT EXISTS (SELECT id FROM productstockactual WHERE stockid = '" . $stockTarget . "' AND productid = '" . $productID . "'); \n";

                    if ($expirationdate != "") {
                        $strInsertLine .= "UPDATE product SET "
                                . "expirationdate = '" . $expirationdate . "' \n"
                                . "WHERE id = '" . $productID . "'; \n";
                    }
                }

                $sql = $sql . $strInsertLine;
            }
        }


        $sql = $sql . "UPDATE entranceofgoods SET "
                . "finish = '1' "
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        $this->multi_query($sql);

        unset($ivLine);
        return json_encode(array("status" => true, "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
    }

    public function entranceListGet($companyId, $dependencyId, $stockTarget, $docNumber, $docDate,
            $supplierId, $operatorId, $serialNumber, $loteNumber, $productId) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $stockTarget = $this->real_escape_string($stockTarget);
        $docNumber = $this->real_escape_string($docNumber);
        $docDate = $this->real_escape_string($docDate);
        $supplierId = $this->real_escape_string($supplierId);
        $operatorId = $this->real_escape_string($operatorId);
        $serialNumber = $this->real_escape_string($serialNumber);
        $loteNumber = $this->real_escape_string($loteNumber);
        $productId = $this->real_escape_string($productId);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND (dependencyid = '" . $dependencyId . "') ";
        }
        if ($stockTarget != -1) {
            $where .= " AND (stocktarger = '" . $stockTarget . "') ";
        }
        if ($docNumber != -1) {
            $where .= " AND invoicenumber = '" . $docNumber . "' ";
        }
        if ($docDate != -1) {
            $where .= " AND CAST(invoicedate AS DATE) = CAST('" . $docDate . "' AS DATE)";
        }
        if ($supplierId != -1) {
            $where .= " AND supplierid = '" . $supplierId . "' ";
        }
        if ($operatorId != -1) {
            $where .= " AND sourceid = '" . $operatorId . "' ";
        }

        if ($serialNumber != -1) {
            $where .= " AND E.invoicenumber IN (SELECT IL.invoiceNumber FROM entranceofgoodsline AS IL WHERE IL.serialnumber = '" . $serialNumber . "') ";
        }
        if ($loteNumber != -1) {
            $where .= " AND E.invoicenumber IN (SELECT IL.invoiceNumber FROM entranceofgoodsline AS IL WHERE IL.lotenumber = '" . $loteNumber . "') ";
        }
        if ($productId != -1) {
            $where .= " AND E.invoicenumber IN (SELECT IL.invoiceNumber FROM entranceofgoodsline AS IL WHERE IL.productId = '" . $productId . "') ";
        }

        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = E.sourceid LIMIT 1) AS operatorName, \n"
                . "(SELECT PS.desigination FROM productstock AS PS WHERE PS.id = E.stocktarger) AS stockin, \n"
                . "(SELECT S.companyname FROM supplier AS S WHERE S.id = E.supplierid LIMIT 1) AS supplierName \n"
                . "FROM entranceofgoods AS E \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . "   \n"
                . "ORDER BY invoicedate DESC  ; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function entranceCancelEntrance($invoiceNumber, $reason, $userId) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $reason = $this->real_escape_string($reason);
        $userId = $this->real_escape_string($userId);

        //Cancel Entrance
        $sql = "UPDATE entranceofgoods SET \n"
                . "invoicestatus = 'A', "
                . "invoiceStatusDate = NOW(), "
                . "Sourceidstatus = '" . $userId . "', "
                . "invoicestatusreason = '" . $reason . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Reduce stock target
        $sql .= "UPDATE productstockactual AS PSA SET \n"
                . "quantity = (quantity - (SELECT IL.quantity FROM entranceofgoodsline AS IL WHERE IL.productId = PSA.productid AND IL.invoiceNumber = '" . $invoiceNumber . "')) "
                . "WHERE PSA.stockid = (SELECT I.stocktarger FROM entranceofgoods AS I WHERE I.invoicenumber = '" . $invoiceNumber . "') AND  "
                . "PSA.productid IN (SELECT IL.productId FROM entranceofgoodsline AS IL WHERE IL.invoiceNumber = '" . $invoiceNumber . "'); ";
        //Add to Stock Source
        $sql .= "UPDATE productstockactual AS PSA SET \n"
                . "quantity = (quantity + (SELECT IL.quantity FROM entranceofgoodsline AS IL WHERE IL.productId = PSA.productid AND IL.invoiceNumber = '" . $invoiceNumber . "')) "
                . "WHERE PSA.stockid = (SELECT I.stocksource FROM entranceofgoods AS I WHERE I.invoicenumber = '" . $invoiceNumber . "') AND  "
                . "PSA.productid IN (SELECT IL.productId FROM entranceofgoodsline AS IL WHERE IL.invoiceNumber = '" . $invoiceNumber . "'); ";
        //Transfer lines
        $sql .= "INSERT INTO entranceofgoodslinecanceled ("
                . "invoiceNumber, productId, productDescription, productSection, quantity, "
                . "unitOfMeasure, serialnumber, lotenumber, expirationdate, note) \n"
                . "SELECT invoiceNumber, productId, productDescription, productSection, quantity, "
                . "unitOfMeasure, serialnumber, lotenumber, expirationdate, note  \n"
                . "FROM entranceofgoodsline AS IL  \n"
                . "WHERE IL.invoiceNumber = '" . $invoiceNumber . "'; \n";
        //Delete old lines
        $sql .= "DELETE FROM entranceofgoodsline WHERE invoiceNumber = '" . $invoiceNumber . "'; \n";

        $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => "Guia de entrada anulada com sucesso."));
    }

    /*     * * SUPPLIER ****************** */

    public function supplierGetSupplier($companyId, $supplierId) {
        $companyId = $this->real_escape_string($companyId);
        $supplierId = $this->real_escape_string($supplierId);
        $where = "";
        if ($supplierId != -1) {
            $where = " AND id = '" . $supplierId . "'  ";
        }
        $sql = "SELECT * "
                . "FROM supplier "
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY companyname;";
        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function supplierGetAutocomplete($companyId) {

        $companyId = $this->real_escape_string($companyId);
        $sql = "SELECT companyname \n"
                . "FROM supplier \n"
                . "WHERE companyid = '" . $companyId . "' \n"
                . "ORDER BY companyname;";
        $result = $this->query($sql);
        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= '-/-' . $row['companyname'];
        }
        return $str;
    }

    public function supplierListSearch($SearchLimit, $searchTag, $companyId) {

        $SearchLimit = $this->real_escape_string($SearchLimit);
        $searchTag = $this->real_escape_string($searchTag);
        $companyId = $this->real_escape_string($companyId);

        $where = "";
        if ($searchTag != "") {
            $where = $where . " AND (CAST(id AS CHAR) = '" . $searchTag . "' OR "
                    . "companyname LIKE '" . $searchTag . "%' OR "
                    . "suppliertaxid = '" . $searchTag . "') \n";
        }

        $limit = is_numeric($SearchLimit) ? $SearchLimit : 5;

        $sql = "SELECT id, companyname, suppliertaxid \n"
                . "FROM supplier \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY companyname "
                . "LIMIT " . $limit . ";";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function supplierListDetail($companyId, $countryId, $provinceId, $municipalityId) {
        $companyId = $this->real_escape_string($companyId);
        $countryId = $this->real_escape_string($countryId);
        $provinceId = $this->real_escape_string($provinceId);
        $municipalityId = $this->real_escape_string($municipalityId);

        $where = "";
        if ($countryId != -1) {
            $where = " AND billingcountry = '" . $countryId . "'  ";
        }
        if ($provinceId != -1) {
            $where = " AND billingprovince = '" . $provinceId . "'  ";
        }
        if ($municipalityId != -1) {
            $where = " AND billingmunicipality = '" . $municipalityId . "'  ";
        }

        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = S.sourceid LIMIT 1) AS operatorName, \n"
                . "(SELECT P.province FROM province AS P WHERE (CAST(P.id AS CHAR)) = S.billingprovince LIMIT 1) AS province, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE (CAST(M.id AS CHAR)) = S.billingmunicipality) AS municipality \n"
                . "FROM supplier AS S \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY companyname;";
        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    /*     * * SYSTEM USER ********************* */

    public function systemUserGetUsersByProfile($companyId, $userProfile, $userId) {
        $companyId = $this->real_escape_string($companyId);
        $userProfile = $this->real_escape_string($userProfile);
        $userId = $this->real_escape_string($userId);
        $where = "";
        if (is_numeric($userProfile)) {
            if ($userProfile != -1) {
                $where = " AND billingprofile >= '" . $userProfile . "' ";
            }
        }
        if (is_numeric($userId)) {
            if ($userId != -1) {
                $where = $where . " AND userid = '" . $userId . "'  ";
            }
        }


        $sql = "SELECT * "
                . "FROM systemuser "
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY userfullname;";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function systemUserListSearch($companyId, $userId = -1, $SearchLimit = 5, $searchTag = "", $target = "") {

        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $SearchLimit = $this->real_escape_string($SearchLimit);
        $searchTag = $this->real_escape_string($searchTag);
        $where = "";
        if ($searchTag != "") {
            $where = $where . " AND (CAST(userid AS CHAR) = '" . $searchTag . "' OR "
                    . "userfullname LIKE '%" . $searchTag . "%' ) ";
        }


        $limit = is_numeric($SearchLimit) ? $SearchLimit : 5;
        if ($userId != -1) {
            $where .= " AND userid = '" . $userId . "' ";
            $limit = 1;
        }

        $sql = "SELECT * "
                . "FROM systemuser "
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY userfullname "
                . "LIMIT " . $limit . ";";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] == "") {
                $row['photo'] = "0000_default.png";
            }
            $row['photo'] = systemUserDocsPath() . $row['photo'];
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserAutocompleteList($companyId) {

        $companyId = $this->real_escape_string($companyId);

        $sql = "SELECT userfullname "
                . "FROM systemuser "
                . "WHERE companyid = '" . $companyId . "' "
                . "ORDER BY userfullname ;";

        $result = $this->query($sql);

        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= '-/-' . $row['userfullname'];
        }
        return $str;
    }

    public function systemUserNew($userName, $userFullName, $email, $sourceid) {
        $sourceid = $this->real_escape_string($sourceid);
        $userName = $this->real_escape_string($userName);
        $salt = $this->getRandomString(10);
        $defaultPass = $this->getRandomString(7);

        $encrytedPass = _rsaCryptGeneral::getInstance()->encryptRSA_general($salt . $defaultPass);

        $sql = "INSERT INTO systemuser (username, passwordsalt, password, sourceid) \n"
                . "SELECT '" . $userName . "', '" . $salt . "', '" . $encrytedPass . "', '" . $sourceid . "'  \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS  "
                . "(SELECT id FROM systemuser WHERE username = '" . $userName . "');";
        $this->query($sql);

        $sql = "SELECT userid, passwordsalt \n"
                . "FROM systemuser \n"
                . "WHERE username = '" . $userName . "' \n;";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            include_once '../mailing.php';
            $sentMail = sendMail($email, "Cadastro Realizado",
                    "<p>Caro " . $userFullName . ", </p><br>"
                    . "<p>A equipa do Sílica Web deseja-lhe as boas vindas. A baixo estão as sua credenciais de acesso.</p> <br>"
                    . "<p>Email: " . $email . "</p><p>Utilizador: " . $userName . "</p><p>Palavra-passe: " . $defaultPass . "</p><br><br>"
                    . "<p><b>Recomendação:</b></p><p>Trocar a plavra passe no próximo início de sessão.</p><br><br>"
                    . "<p>Para confirmar o seu e-mail clicar em </p>"
                    . "<a href='" . $_SERVER['SERVER_NAME'] . "/indexLogin.php?wc=EMAIL'>Acessar Sílica Web</a>");
            return array("id" => $row[0], "pass" => $defaultPass, "sentMail" => $sentMail);
        } else {
            return null;
        }
    }

    public function systemUserSave($arrayUserInfo, $arrayUserPermissions, $target) {

        $userId = $this->real_escape_string($arrayUserInfo['uId']);
        $userFullName = $this->real_escape_string($arrayUserInfo['uFullName']);
        $userName = $this->real_escape_string($arrayUserInfo['uName']);
        $email = $this->real_escape_string($arrayUserInfo['email']);
        $userTitle = $this->real_escape_string($arrayUserInfo['uTle']);
        $userTitleSignature = $this->real_escape_string($arrayUserInfo['uTleSign']);
        $accountstatus = $this->real_escape_string($arrayUserInfo['accountstatus']);

        $billingProfile = $this->real_escape_string($arrayUserInfo['bProfile']);
        $workplaceid = $this->real_escape_string($arrayUserInfo['workplaceid']);
        $permissionLevel = $this->real_escape_string($arrayUserInfo['pLevel']);
        $companyId = $this->real_escape_string($arrayUserInfo['companyId']);
        $photo = $this->real_escape_string($arrayUserInfo['photo']);
        $sourceid = $this->real_escape_string($arrayUserInfo['sourceid']);

        $target = $this->real_escape_string($target);
        $passWordMsg = "";

        if (!$this->systemUserCheckUserName($userId, $userName, false)) {
            return json_encode(array("result" => '0', "msg" => 'Nome de utilizador duplicado.'));
        }

        if ($this->validateDuplicateElement(-1, $userId, "userid",
                        $userFullName, "systemuser", "userfullname") == 1) {
            return json_encode(array("result" => '0', "msg" => 'Nome completo já cadastrado.'));
        }
        if ($this->validateDuplicateElement(-1, $userId, "userid",
                        $email, "systemuser", "email") == 1) {
            return json_encode(array("result" => '0', "msg" => 'E-mail já cadastrado.'));
        }

        if (!is_numeric($userId)) {
            $newUser = $this->systemUserNew($userName, $userFullName, $email, $sourceid);
            $userId = $newUser['userid'];
            $defaultPass = $newUser['pass'];
            $mailed = $newUser['sentMail'];
            $passWordMsg = "<br><br>";
            if ($mailed) {
                $passWordMsg .= "Foi enviada a palavra-passe por e-mail.";
            } else {
                $passWordMsg .= "A palavra-passe gerada é   " . $defaultPass;
            }
        }
        $permissionUserTable = "systemuserpermission";
        if ($target != "") {
            $permissionUserTable = "gmesystemuserpermission";
        }

        $sql = "UPDATE systemuser SET \n"
                . "username = '" . $userName . "', "
                . "userfullname = '" . $userFullName . "', "
                . "email = '" . $email . "', "
                . "accountstatus = '" . $accountstatus . "', \n";
        if ($target == "") {
            $sql .= "title = '" . $userTitle . "', "
                    . "sigintitle = '" . $userTitleSignature . "', "
                    . "billingprofile = '" . $billingProfile . "',  \n";
        }
        $sql .= "permissionlevel =  '" . $permissionLevel . "',  "
                . "workplaceid = '" . $workplaceid . "', "
                . "companyid = '" . $companyId . "',  "
                . "photo = '" . $photo . "' \n"
                . "WHERE userid = '" . $userId . "'; \n";

        foreach ($arrayUserPermissions as $key => $value) {
            $permissionCode = $this->real_escape_string($value['pC']);
            $permissionStatus = $this->real_escape_string($value['pS']);

            $sql .= "UPDATE " . $permissionUserTable . " SET \n"
                    . "permissionstatus = '" . $permissionStatus . "' \n"
                    . "WHERE userid = '" . $userId . "' AND permissioncode = '" . $permissionCode . "'; \n";
            $sql .= "INSERT INTO " . $permissionUserTable . " (companyid, userid, permissioncode, permissionstatus) \n"
                    . "SELECT '" . $companyId . "', '" . $userId . "', '" . $permissionCode . "', '" . $permissionStatus . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM " . $permissionUserTable . " WHERE userid = '" . $userId . "'  AND permissioncode = '" . $permissionCode . "' ); \n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("result" => '1', "msg" => 'Utilizador guardado com sucesso. ' . $passWordMsg));
    }

    public function systemUserCheckUserName($userId, $userName, $needEcho = true) {
        $userId = $this->real_escape_string($userId);
        $userName = $this->real_escape_string($userName);


        $sql = "SELECT userid "
                . "FROM systemuser  "
                . "WHERE CAST(userid AS CHAR) <> '" . $userId . "' AND username = '" . $userName . "'; ";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            if ($needEcho == true) {
                echo json_encode(array("result" => '0', "msg" => '* Este nome de utilizador não está disponível.'));
            }
            return false;
        }
        if ($needEcho == true) {
            echo json_encode(array("result" => '1', "msg" => ''));
        }
        return true;
    }

    public function systemUserGetPermissionListNoJason($userId, $target, $licenseLevel) {
        $userId = $this->real_escape_string($userId);
        $target = $this->real_escape_string($target);

        $permissionTable = "permissions";
        $permissionUserTable = "systemuserpermission";
        if ($target == "GME") {
            $permissionTable = "gmepermission";
            $permissionUserTable = "gmesystemuserpermission";
        }

        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT SUP.permissionstatus FROM " . $permissionUserTable . " AS SUP "
                . "WHERE SUP.permissioncode = P.permissionCode AND SUP.userid = '" . $userId . "' LIMIT 1), 0)) AS permissionstatus \n"
                . "FROM " . $permissionTable . " AS P \n"
                . "WHERE licenselevel <= " . $licenseLevel . "  \n"
                . "ORDER BY permissionCode; ";

        return $this->query($sql);
    }

    public function systemUserGetPermissionList($userId, $target, $licenseLevel) {
        /*   $userId = $this->real_escape_string($userId);
          $sql = "SELECT *, \n"
          . "(COALESCE((SELECT SUP.permissionstatus FROM systemuserpermission AS SUP "
          . "WHERE SUP.permissioncode = P.permissionCode AND SUP.userid = '" . $userId . "' LIMIT 1), 0)) AS permissionstatus \n"
          . "FROM permissions AS P \n"
          . "ORDER BY permissionCode; "; */

        $result = $this->systemUserGetPermissionListNoJason($userId, $target, $licenseLevel);
        $array = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($array, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($array);
    }

    public function systemUserSetDefaultPermission() {
        include_once './_aqconections/_userPermission.php';

        $sql = _userPermissin::getInstance()->setAllPermissions();
        $sql .= _gmePermission::getInstance()->setAllPermissionsGme();
        $result = $this->multi_query($sql);
        return true;
    }

    private function systemUserCheckUserPass($userId, $passWord) {
        $userId = $this->real_escape_string($userId);
        $passWord = $this->real_escape_string($passWord);
        $sql = "SELECT 1 AS status, userid, username, passwordsalt, password \n"
                . "FROM systemuser \n"
                . "WHERE userid = '" . $userId . "'; \n";
        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $passwordNW = $row['passwordsalt'] . $passWord;
            $decrytedPass = _rsaCryptGeneral::getInstance()->decryptRSA_general($row['password']);
            if ($decrytedPass == $passwordNW) {
                return $row;
            }
        }

        return array("status" => 0);
    }

    public function systemUserResetPasswordSave($userId, $oldPass, $newPass) {
        $userId = $this->real_escape_string($userId);
        $oldPass = $this->real_escape_string($oldPass);
        $newPass = $this->real_escape_string($newPass);

        $checkOldPass = $this->systemUserCheckUserPass($userId, $oldPass);
        if ($checkOldPass['status'] == 0) {
            return 0;
        } else {
            $salt = $checkOldPass['passwordsalt'];
            $encrytedPass = _rsaCryptGeneral::getInstance()->encryptRSA_general($salt . $newPass);

            $sql = "UPDATE systemuser "
                    . "SET password = '" . $encrytedPass . "', lastpassordchange = NOW(), needchangepass = 0  "
                    . "WHERE userid = '" . $userId . "';";
            $result = $this->query($sql);
            return 1;
        }
    }

    public function systemUserGetTarget($companyId, $userId, $billingProfile) {
        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $billingProfile = $this->real_escape_string($billingProfile);
        $initialDate = mktime(0, 0, 0, Date('m'), 1, Date('Y'));

        function fielTarget($field, $billProf) {
            if ($billProf == 1) {
//seller
                return "(SELECT HRT." . $field . " FROM hrtarget AS HRT WHERE HRT.companyid = U.companyid "
                        . " AND HRT.targettype = (SELECT CP.provincetype FROM companyprovince AS CP WHERE CP.provinceid = (SELECT CD.provinceid FROM companydependency AS CD WHERE CD.id = U.workplaceid LIMIT 1) LIMIT 1) LIMIT 1) AS " . $field . ", \n";
            } elseif ($billProf == 2) {
//coordinator
                return "(COALESCE( ((SELECT HRT." . $field . " FROM hrtarget AS HRT WHERE HRT.companyid = U.companyid "
                        . " AND HRT.targettype = (SELECT CP.provincetype FROM companyprovince AS CP WHERE CP.provinceid = (SELECT CD.provinceid FROM companydependency AS CD WHERE CD.coordinatoruser = U.id LIMIT 1) LIMIT 1) LIMIT 1) * "
                        . "(SELECT COUNT(SU.id)+1 FROM systemuser AS SU WHERE SU.workplaceid = (SELECT CD.id FROM companydependency AS CD WHERE CD.coordinatoruser = U.userid LIMIT 1)) ), 0)) AS " . $field . ", \n";
            } elseif ($billProf == 3) {
//manager prov / reg
                return "(COALESCE( ((SELECT HRT." . $field . " FROM hrtarget AS HRT WHERE HRT.companyid = U.companyid "
                        . " AND HRT.targettype = (SELECT CP.provincetype FROM companyprovince AS CP WHERE CP.manageruser = U.id LIMIT 1) LIMIT 1) * "
                        . "(SELECT COUNT(SU.id)+1 FROM systemuser AS SU WHERE SU.workplaceid IN (SELECT CD.id FROM companydependency AS CD WHERE CD.provinceid = (SELECT CP.provinceid FROM companyprovince AS CP WHERE CP.manageruser = U.userid))) ), 0)) AS " . $field . ", \n";
            } else {
                return "(0) AS " . $field . ", ";
            }
        }

        $reachedRegisterNumber = "(SELECT COUNT(C.id) FROM customer AS C "
                . "WHERE (C.entryUser = U.id OR C.coordinatorUser = U.id OR C.managerUser = U.id OR C.directorGeneralUser = U.id OR C.administratorUser = U.id) "
                . "AND (CAST(C.entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST(NOW() AS DATE)) ) AS reachedRegisterNumber, ";

        $reachedSaleAmount = "(SELECT SUM(I.totalInvoice) FROM invoice AS I "
                . "WHERE (I.sourceid = U.id OR I.coordinatorUser = U.id OR I.managerUser = U.id OR I.directorGeneralUser = U.id OR I.administratorUser = U.id) "
                . " AND (CAST(I.invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST(NOW() AS DATE)) AND UPPER(I.invoicestatus)!= 'A' ) AS reachedSaleAmount, ";

        $sql = "SELECT userid, userfullname, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = (SELECT CD.municipalityid FROM companydependency AS CD WHERE CD.id = U.workplaceid LIMIT 1) LIMIT 1) AS municipality, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = (SELECT CD.provinceid FROM companydependency AS CD WHERE CD.id = U.workplaceid LIMIT 1) LIMIT 1) AS province, \n"
                . "(SELECT CD.designation FROM companydependency AS CD WHERE CD.id = U.workplaceid LIMIT 1) AS workplace, \n"
                . fielTarget("registernumber", $billingProfile)
                . fielTarget("saleamount", $billingProfile)
                . $reachedRegisterNumber
                . $reachedSaleAmount
                . "directoruser, directorgeneraluser, administratoruser \n"
                . "FROM systemuser AS U \n"
                . "WHERE userid = '" . $userId . "';";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $row = $this->converteArrayParaUtf8($row);
            return json_encode($row);
        }
    }

    public function systemUserListDetail($companyId, $initialDate, $endDate,
            $billingProfile, $permissionLevel) {

        $companyId = $this->real_escape_string($companyId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);
        $billingProfile = $this->real_escape_string($billingProfile);
        $permissionLevel = $this->real_escape_string($permissionLevel);

        $where = "";
        if ($billingProfile != -1) {
            $where .= " AND billingprofile = '" . $billingProfile . "' ";
        }
        if ($permissionLevel != -1) {
            $where .= " AND permissionlevel = '" . $permissionLevel . "' ";
        }

        $sql = "SELECT *, \n"
                . "(SELECT SU.userfullname FROM systemuser AS SU WHERE SU.id = U.sourceid LIMIT 1) AS operatorName \n"
                . "FROM systemuser AS U \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . " AND (CAST(entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n"
                . "ORDER BY userfullname ;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            if ($row['photo'] != "") {
                $row['photo'] = systemUserDocsPath() . $row['photo'];
            }
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserRequestNewPass($userName, $email) {

        $userName = $this->real_escape_string($userName);
        $email = $this->real_escape_string($email);


        $sql = "SELECT userid, userfullname, email \n"
                . "FROM systemuser AS SU \n"
                . "WHERE BINARY username = '" . $userName . "' AND email = '" . $email . "';";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);

            $userId = $row['userid'];
            $userFullName = $row['userfullname'];
            $requestId = "RNP" . round(microtime(true) * 1000);

            $sql = "INSERT INTO systemuserrequestnewpass (userid, requestid) \n"
                    . "SELECT '" . $userId . "', '" . $requestId . "' \n"
                    . "FROM insert_table \n";
            $this->multi_query($sql);

            include_once './mailing.php';
            $sentMail = sendMail($email, "Solicitação de nova palavra passe",
                    "<p>Caro " . $userFullName . ", </p><br>"
                    . "<p>Foi feita uma solicitação de nova palavra passe. Para o efeito, clique em.</p>"
                    . "<a href='" . $_SERVER['SERVER_NAME'] . "/indexRestartPass.php?rid=" . $requestId . "&wc=MAIL'>Solicitar nova palavra passe</a>");

            return json_encode(array("status" => 1, "msg" => "Solicitação de nova palavra passe com sucesso. <br><br>"
                . "Foi enviado para seu e-mail os restantes procedimentos."));
        }
        return json_encode(array("status" => 0, "msg" => "Utilizador ou e-mail incorrecto."));
    }

    public function systemUserRestartPass($requestId, $sourceId = -1) {
        $requestId = $this->real_escape_string($requestId);
        $sourceId = $this->real_escape_string($sourceId);

        $sql = "SELECT U.userid, U.userfullname, U.email, R.requestid \n"
                . "FROM systemuser AS U, systemuserrequestnewpass AS R \n"
                . "WHERE U.userid = R.userid AND R.requestid = '" . $requestId . "' AND requeststatus = 0 \n"
                . "LIMIT 1;";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $userId = $row['userid'];
            $userFullName = $row['userfullname'];
            $email = $row['email'];
            $salt = $this->getRandomString(10);
            $defaultPass = $this->getRandomString(7);

            if ($sourceId == -1) {
                //include_once './_rsa/_rsaCryptGeneral.php';
                include_once './mailing.php';
            } else {
                //include_once '../_rsa/_rsaCryptGeneral.php';
                include_once '../mailing.php';
            }

            $encrytedPass = _rsaCryptGeneral::getInstance()->encryptRSA_general($salt . $defaultPass);

            $sql = "UPDATE systemuser SET  \n"
                    . "passwordsalt = '" . $salt . "', password = '" . $encrytedPass . "' \n"
                    . "WHERE userid = '" . $userId . "';  \n";
            $sql .= "UPDATE systemuserrequestnewpass SET \n"
                    . "requeststatus = 1, attendencedate = NOW(), attendenceuserid = '" . $sourceId . "' \n"
                    . "WHERE requestid = '" . $requestId . "';";
            $sql .= "UPDATE systemuserrequestnewpass SET \n"
                    . "requeststatus = 1, attendencedate = NOW() \n"
                    . "WHERE userid = '" . $userId . "' AND requeststatus = 0;";
            $this->multi_query($sql);

            $sentMail = sendMail($email, "Palavra passe reiniciada",
                    "<p>Caro " . $userFullName . ", </p><br>"
                    . "<p>Por sua solicitação, foi reiniciada a sua palavra passe.</p> <br>"
                    . "<p>Nova palavra-passe: " . $defaultPass . "</p><br><br>"
                    . "<p><b>Recomendação:</b></p><p>Trocar a plavra passe no próximo início de sessão.</p><br><br>"
                    . "<p>Para aceder ao Sílica Web clique em </p><a href='" . $_SERVER['SERVER_NAME'] . "/indexLogin.php'>Acessar Sílica Web</a>");

            $msg = "Sua nova palavra passe é <br><br>" . $defaultPass . "";
            if ($sourceId != -1) {
                if ($sentMail) {
                    $msg = "Palavra passe reiniciada com sucesso. Foi enviada por e-mail a nova palavra passe.";
                }
            }
            return array("status" => 1, "msg" => $msg);
        } else {
            return array("status" => 0, "msg" => "Não foi encontrada uma solicitação de nova palavra passe válida.");
        }
    }

    public function systemUserRequestNewPassListDetail($companyId, $userId) {

        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);

        $where = "";
        if ($userId != -1) {
            $where .= " AND R.userid = '" . $userId . "' ";
        }

        $sql = "SELECT R.userid, U.userfullname, R.requestid, R.entrydate, R.requeststatus, R.attendencedate, R.attendenceuserid, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.id = R.attendenceuserid) AS operatorName \n"
                . "FROM systemuser AS U, systemuserrequestnewpass AS R \n"
                . "WHERE U.companyid = '" . $companyId . "' AND U.userid = R.userid " . $where . " \n"
                . "ORDER BY requeststatus, userfullname ;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserRequestEmailConfirmation($userId) {
        $userId = $this->real_escape_string($userId);

        $sql = "SELECT U.userid, U.userfullname, U.email  \n"
                . "FROM systemuser AS U  \n"
                . "WHERE U.userid = '" . $userId . "' \n"
                . "LIMIT 1;";

        $result = $this->query($sql);

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $userId = $row['userid'];
            $userFullName = $row['userfullname'];
            $email = $row['email'];

            include_once '../mailing.php';

            $sentMail = sendMail($email, "Solicitação de confirmação de e-mail",
                    "<p>Caro " . $userFullName . ", </p><br>"
                    . "<p>Para confirmar a associação da sua conta de utilizador ao e-mail '" . $email . "', deve clicar em </p>"
                    . "<a href='" . $_SERVER['SERVER_NAME'] . "/indexLogin.php?wc=EMAIL'>Confirmar meu e-mail</a>");
            if ($sentMail) {
                return array("status" => 1, "msg" => "Foi enviada uma solicitação de confirmação para " . $email);
            }
        }

        return array("status" => 0, "msg" => "Não foi possível enviar a solicitação de confirmação.");
    }

    public function systemUserSelfRegisterSave($silicaUser, $userFullName,
            $userName, $email, $phone, $pass) {

        $$silicaUser = $this->real_escape_string($silicaUser);
        $userId = "Novo utilizador";
        $userFullName = $this->real_escape_string($userFullName);
        $userName = $this->real_escape_string($userName);
        $email = $this->real_escape_string($email);
        $phone = $this->real_escape_string($phone);
        $pass = $this->real_escape_string($pass);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(40);

        if (!$this->systemUserCheckUserName($userId, $userName, false)) {
            return json_encode(array("status" => '0', "msg" => 'Nome de utilizador duplicado.'));
        }

        /*    if ($this->validateDuplicateElement(-1, $userId, "id",
          $userFullName, "systemuser", "userfullname") == 1) {
          return json_encode(array("status" => '0', "msg" => 'Nome completo já cadastrado.'));
          } */

        if ($this->validateDuplicateElement(-1, $userId, "userid",
                        $email, "systemuser", "email") == 1) {
            return json_encode(array("status" => '0', "msg" => 'E-mail já cadastrado.'));
        }


        $salt = $this->getRandomString(10);
        $defaultPass = $pass;

        $encrytedPass = _rsaCryptGeneral::getInstance()->encryptRSA_general($salt . $defaultPass);
//Register User
        $sql = "INSERT INTO systemuser \n"
                . "(companyid, userfullname, username, billingprofile, needchangepass, "
                . "email, passwordsalt, password, permissionlevel, registernumber) \n"
                . "SELECT '0', '" . $userFullName . "', '" . $userName . "', 20, 0, "
                . "'" . $email . "','" . $salt . "', '" . $encrytedPass . "', 3, '" . $registerNumber . "'  \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM systemuser WHERE username = '" . $userName . "'); \n";
        //Register company
        $sql .= "INSERT INTO company ("
                . "companyname, personname, personphone1, personemail, "
                . "businessname, billingtelephone1, billingemail, "
                . "entryuser, request, registernumber) \n"
                . "SELECT '" . $userFullName . "', '" . $userFullName . "', '" . $phone . "', '" . $email . "', "
                . "'" . $userFullName . "', '" . $phone . "', '" . $email . "', "
                . "'" . $silicaUser . "', '20', '" . $registerNumber . "'  \n"
                . "FROM insert_table; \n";
        $sql .= "UPDATE company SET companyid = id WHERE registernumber = '" . $registerNumber . "'; \n";
        //Register dependency
        $sql .= "INSERT INTO companydependency ("
                . "companyid, provinceid, municipalityid, designation, coordinatoruser, registernumber) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "11, 97, 'Sede', (SELECT U.id FROM systemuser AS U WHERE U.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "'" . $registerNumber . "' \n"
                . "FROM insert_table; \n";
        //Register warehouse (stock)
        $sql .= "INSERT INTO productstock ("
                . "companyid, desigination, dependencyid, billingstock, registernumber) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "'ARMAZÉM DE FACTURAÇÃO', (SELECT CD.id FROM companydependency AS CD WHERE CD.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "1, '" . $registerNumber . "' \n"
                . "FROM insert_table; \n";
        //Register Bank Account
        $sql .= "INSERT INTO bankaccount ("
                . "companyid, nationalbankid, accountnumber, iban, registernumber) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "1, 'XXXXXXXX-10', 'XXXX-XXXX-XXXX-XXXX-XXXX-X', '" . $registerNumber . "' \n"
                . "FROM insert_table; \n";
        //Register TPA Device
        $sql .= "INSERT INTO banktpaterminal ("
                . "companyid, accountbankid, designation, registernumber) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "(SELECT BA.id FROM bankaccount AS BA WHERE BA.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "'TPA 1', '" . $registerNumber . "' \n"
                . "FROM insert_table; \n";
        //Defaul Treasury Rubric
        include_once './_tblfld.php';
        for ($x = 0; $x <= 8; $x++) {
            $rubric = $treasuryRubricDefault[$x];
            $sql .= treasuryRubricDefault($rubric[$str], $rubric[$ctype], $rubric[$cstype], $registerNumber);
        }
        //Register partnership
        $sql .= "INSERT INTO companypartnership ("
                . "companyid, registernumber) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "'" . $registerNumber . "' \n"
                . "FROM insert_table; \n";
        $sql .= "INSERT INTO companypartnershippartner ("
                . "partnershipid, partnerid, percentage) \n"
                . "SELECT (SELECT CP.id FROM companypartnership AS CP WHERE CP.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "(SELECT U.id FROM systemuser AS U WHERE U.registernumber = '" . $registerNumber . "' LIMIT 1), 100 \n"
                . "FROM insert_table; \n";
        //UPDATE USER 
        $sql .= "UPDATE systemuser SET "
                . "companyid = (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "manageruser = id, "
                . "workplaceid = (SELECT CD.id FROM companydependency AS CD WHERE CD.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "partnershipuser = (SELECT CP.id FROM companypartnership AS CP WHERE CP.registernumber = '" . $registerNumber . "' LIMIT 1) \n"
                . " WHERE registernumber = '" . $registerNumber . "'; \n";
        //Set permission for user
        $sql .= "INSERT INTO systemuserpermission ("
                . "companyid, userid, permissioncode, permissionstatus) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "(SELECT U.id FROM systemuser AS U WHERE U.registernumber = '" . $registerNumber . "' LIMIT 1), \n "
                . "permissionCode, 1  \n"
                . "FROM permissions;  \n";
        //Active free license
        $sql .= "INSERT INTO licenseassigned ("
                . "companyid, invoicenumber, productdescription, licensetypeid) \n"
                . "SELECT (SELECT C.id FROM company AS C WHERE C.registernumber = '" . $registerNumber . "' LIMIT 1), \n"
                . "'ACTIVAÇÃO AUTOMÁTICA', 'PLANO SÍLICA WEB GRATIS', LP.id \n"
                . "FROM licensetype AS LP \n"
                . "WHERE LP.silicatag = 'SWFREE'; \n";
        //Create schedule alert
        $sql .= "INSERT INTO scheduleactivity ("
                . "companyid, dependencyid, activity, details, "
                . "localdatetime, customername, contacts, email, "
                . "entrydate, sourceid) \n"
                . "SELECT '5000', '2', '[Novo cadastro]', 'Nova cadastro em SílicaWeb.ao', "
                . "NOW(), '" . $userFullName . "', '" . $phone . "', '" . $email . "', "
                . "NOW(), -1  \n"
                . "FROM insert_table; \n";
//Get user Id
        $sql .= "SELECT id, companyid  \n"
                . "FROM systemuser \n"
                . "WHERE registernumber = '" . $registerNumber . "' \n"
                . "LIMIT 1; \n";

        if ($this->multi_query($sql)) {
            do {
                /* store first result set */
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        $userId = $row['id'];
                        $companyId = $row['companyid'];
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!is_numeric($userId)) {
            return json_encode(array("status" => '0', "msg" => 'Não foi possível cadastrar este utilizador.'));
        }


        include_once '../mailing.php';
        $sentMail = sendMail($email, "Cadastro Realizado",
                "<p>Caro " . $userFullName . ", </p><br>"
                . "<p>A equipa do Sílica Web deseja-lhe as boas vindas. A baixo estão as sua credenciais de acesso.</p> <br>"
                . "<p>Email: " . $email . "</p><p>Utilizador: " . $userName . "</p><p>Palavra-passe: " . $defaultPass . "</p><br><br>"
                . "<p><b>Recomendação:</b></p><p>Trocar a plavra passe no próximo início de sessão.</p><br><br>"
                . "<p>Para confirmar o seu e-mail clicar em </p>"
                . "<a href='" . $_SERVER['SERVER_NAME'] . "/indexLogin.php?wc=EMAIL'>Acessar Sílica Web</a>");

        return json_encode(array("status" => '1', "msg" => 'Utilizador guardado com sucesso.', "uid" => $userId, "nus" => $companyId));
    }

    public function systemUserGetSilicaInterest($userId, $initialDate, $endDate) {

        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);


        $sql = "SELECT COUNT(id) AS interests \n"
                . "FROM frontacess \n"
                . "WHERE recomendationuser = '" . $userId . "' AND "
                . "(CAST(acesstime AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) \n;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            return json_encode($row);
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserGetOrdersSales($companyId, $userId, $initialDate, $endDate, $type) {

        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);
        $type = $this->real_escape_string($type);

        $tblInvoice = ($type == "I") ? "invoice" : "workingdocument";


        $sql = "SELECT COUNT(id) AS quantity, SUM(totalInvoice) AS amount \n"
                . "FROM " . $tblInvoice . " AS I \n"
                . "WHERE (sellerUser = '" . $userId . "' OR managerUser = '" . $userId . "') AND "
                . "(CAST(invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) \n"
                . "AND (UPPER(invoicestatus) != 'A') ;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            return json_encode($row);
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserGetComissions($companyId, $userId, $initialDate, $endDate) {

        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        function comissionOnDash($field, $userType, $userId) {
            return "SUM((CASE WHEN I." . $userType . " = '" . $userId . "' THEN (((IL.warehousePrice * (1 - (IL.settlementAmount/100))) * IL." . $field . "/100)*IL.quantity) ELSE 0 END)) \n";
        }

        //
        //      . comissionOnDash('managerComission', 'managerUser', $userId)

        $sql = "SELECT COUNT(IL.id) AS quantity, "
                . "(" . comissionOnDash('sellerComission', 'sellerUser', $userId) . " + "
                . comissionOnDash('managerComission', 'managerUser', $userId) . ") AS amount    \n"
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.invoicenumber = IL.invoiceNumber AND UPPER(I.invoicestatus)!= 'A' AND UPPER(IL.status)= 'N' AND IL.note = 0 "//AND (CAST(IL.devolution AS DATE)) < (CAST(NOW() AS DATE))  \n"
                . "AND (I.sellerUser = '" . $userId . "' OR I.managerUser = '" . $userId . "') \n"
                . "AND (CAST(IL.devolution AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "AND IL.warehousePrice > 0 ; ";

        $result = $this->query($sql);


        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            return json_encode($row);
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function systemUserGetPromotionSilica($userId) {

        $userId = $this->real_escape_string($userId);

        $where = "";
        if ($userId != -1) {
            $where = " AND U.id = '" . $userId . "' ";
        }

        $sql = "INSERT INTO systemuserpurshaselink (userid, socialnetwork, fulllink) \n"
                . "SELECT U.id, SN.shortform, CONCAT('https://silicaweb.ao?u=', U.userid, '&sn=', SN.shortform) \n"
                . "FROM systemuser AS U, socialnetwork AS SN \n"
                . "WHERE U.companyid = 5000 " . $where . " AND \n"
                . "NOT EXISTS(SELECT id FROM systemuserpurshaselink AS UL WHERE UL.userid = U.id AND UL.socialnetwork = SN.shortform); \n";

        $sql .= "SELECT UL.id, U.userfullname, SN.socialnetwork, UL.fulllink    \n"
                . "FROM systemuserpurshaselink AS UL, systemuser AS U, socialnetwork AS SN \n"
                . "WHERE U.companyid = 5000 AND UL.userid = U.id AND UL.socialnetwork = SN.shortform " . $where . " \n"
                . "ORDER BY U.userfullname, SN.id; ";

        //  $result = $this->query($sql);

        $arrayResult = array();
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        array_push($arrayResult, json_encode($row));
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        return json_encode($arrayResult);
    }

    public function systemUserPromotionSilicaSave($arrayLinks) {

        $sql = "";
        foreach ($arrayLinks as $key => $links) {
            $id = $this->real_escape_string($links['id']);
            $fulllink = $this->real_escape_string($links['fulllink']);

            $sql .= "UPDATE systemuserpurshaselink SET \n"
                    . "fulllink = '" . $fulllink . "' \n"
                    . "WHERE id  = '" . $id . "'; \n";
        }

        $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Promoção actualizada com sucesso."));
    }

    public function systemUserLogedList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);

        $where = "";
        $orderBy = "ORDER BY userfullname, sessiontime DESC \n";
        $limit = "LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " \n";
        if ($dependencyId != -1) {
            $where .= " AND SU.workplaceid = '" . $dependencyId . "' ";
        }

        $fields = "SU.userid, SU.userfullname, SL.idaddress, SL.sessiontime, SL.sessionouttime, \n"
                . "SL.onlinestatus, "
                . "TIMESTAMPDIFF(MINUTE, SL.sessiontime, SL.sessionouttime) AS sessinduration ";

        if ($onlynumber == 1) {
            $fields = " COUNT(SL.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM systemuser AS SU, systemuserloged AS SL \n"
                . "WHERE SU.companyid = '" . $companyId . "' " . $where
                . " AND (CAST(SL.sessiontime AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n"
                . "AND SU.userid = SL.userid \n"
                . $orderBy . $limit
                . " ;";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return json_encode(array("n" => mysqli_fetch_array($result)[0]));
        }

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayCustomer);
    }

    public function postsellGetDocuments($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $limit = $this->real_escape_string($arrayFilterInfo['limit']);
        $docType = $this->real_escape_string($arrayFilterInfo['docType']);
        $docNumber = $this->real_escape_string($arrayFilterInfo['docNumber']);
        $docDate = $this->real_escape_string($arrayFilterInfo['docDate']);
        $docStatus = $this->real_escape_string($arrayFilterInfo['docStatus']);
        $customerId = $this->real_escape_string($arrayFilterInfo['customerId']);
        $operatorId = $this->real_escape_string($arrayFilterInfo['operatorId']);
        $productId = $this->real_escape_string($arrayFilterInfo['productId']);

        $where = " AND (invoicetype = 'FR' OR invoicetype = 'VD') ";
        $tblInvoice = " invoice ";
        $tblInvoiceLine = " invoiceline ";
        $fldOrigination = " reference AS originatingon, ";
        $fldHoldongTax = "";
        if ($docType == 2) {
            $where = " AND (invoicetype = 'FT') ";
        } elseif ($docType == 3) {
            $where = " AND (invoicetype = 'FT') ";
        } elseif ($docType == 4) {
            $tblInvoice = " workingdocument ";
            $tblInvoiceLine = " workingdocumentline ";
            $where = "";
        } elseif (($docType == 5) || ($docType == 6)) {
            $tblInvoice = " payment ";
            $fldOrigination = "";
            $fldHoldongTax = " withholdingtaxAmount AS WithholdingTaxAmount, ";
            $where = " AND (invoicetype = 'RG') ";
            if ($docType == 6) {
                $where = " AND (invoicetype = 'NA') ";
            }
        }

        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($docNumber != -1) {
            $where .= " AND invoicenumber = '" . $docNumber . "' ";
        }
        if ($docDate != -1) {
            $where .= " AND CAST(invoicedate AS DATE) = CAST('" . $docDate . "' AS DATE)";
        }
        if ($docStatus != -1) {
            if (($docType == 5) || ($docType == 6)) {
                $where .= " AND paymentstatus = '" . $docStatus . "' ";
            } else {
                $where .= " AND invoicestatus = '" . $docStatus . "' ";
            }
        }
        if ($customerId != -1) {
            $where .= " AND customerid = '" . $customerId . "' ";
        }
        if ($operatorId != -1) {
            $where .= " AND sourceid = '" . $operatorId . "' ";
        }
        if ($productId != -1) {
            $where .= " AND I.invoicenumber IN (SELECT IL.invoiceNumber FROM " . $tblInvoiceLine . " AS IL WHERE IL.productId = '" . $productId . "') ";
        }

        $fldPayDocument = "(COALESCE((SELECT P.invoicenumber FROM payment AS P WHERE P.originatingon = I.invoicenumber AND UPPER(P.paymentstatus) != 'A' LIMIT 1), '')) AS paymentDocument, \n";
        $fldMovDocument = "(COALESCE((SELECT M.invoicenumber FROM movimentofgoods AS M WHERE M.originatingon = I.invoicenumber AND UPPER(M.invoicestatus) != 'A' LIMIT 1), '')) AS movimentDocument, \n";

        $sql = "SELECT *, \n"
                . $fldOrigination
                . $fldHoldongTax
                . $fldPayDocument
                . $fldMovDocument
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newCustomerName, \n"
                . "(SELECT C.customertaxid FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTaxId, \n"
                . "(SELECT C.telephone1 FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTelephone, "
                . "(SELECT C.email FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newEmail, "
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName \n"
                . "FROM " . $tblInvoice . " AS I \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . "  \n"
                . "ORDER BY invoicedate DESC  \n"
                . "LIMIT " . $limit . "; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleOrderList($companyId, $dependencyId,
            $docNumber, $docDate, $productId, $customerId, $operatorId) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $docNumber = $this->real_escape_string($docNumber);
        $docDate = $this->real_escape_string($docDate);
        $productId = $this->real_escape_string($productId);
        $customerId = $this->real_escape_string($customerId);
        $operatorId = $this->real_escape_string($operatorId);

        $where = "";

        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($docNumber != -1) {
            $where .= " AND invoicenumber = '" . $docNumber . "' ";
        }
        if ($docDate != -1) {
            $where .= " AND CAST(invoicedate AS DATE) = CAST('" . $docDate . "' AS DATE)";
        }
        if ($productId != -1) {
            $where .= " AND invoicenumber IN (SELECT IL.invoiceNumber FROM workingdocumentline AS IL WHERE IL.productId = '" . $productId . "') ";
        }
        if ($customerId != -1) {
            $where .= " AND customerid = '" . $customerId . "' ";
        }
        if ($operatorId != -1) {
            $where .= " AND sourceid = '" . $operatorId . "' ";
        }

        $billingNumber = " (COALESCE( (SELECT B.invoicenumber FROM invoice AS B WHERE B.reference = I.invoicenumber AND UPPER(B.invoicestatus) != 'A' LIMIT 1), '')) AS billingNumber, \n";

        $sql = "SELECT *, \n"
                . $billingNumber
                . "(DATEDIFF(I.expeditiondate, NOW())) AS delayDays, \n"
                . "(SELECT C.telephone1 FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTelephone, "
                . "(SELECT C.email FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newEmail, "
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName \n"
                . "FROM workingdocument AS I \n"
                . "WHERE companyid = '" . $companyId . "' AND invoicetype = 'NE' " . $where . "  \n"
                . "ORDER BY invoicestatus DESC, billingNumber, delayDays, expeditiondate ; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleDocumentList($arrayFilterInfo) {
        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $docType = $this->real_escape_string($arrayFilterInfo['docType']);
        $documentStatus = $this->real_escape_string($arrayFilterInfo['documentStatus']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);

        $where = " AND (invoicetype = 'FR' OR invoicetype = 'VD') ";
        $tblInvoice = " invoice ";
        $tblInvoiceLine = " invoiceline ";
        $fldSatus = "I.invoicestatus";
        $fldOrigination = " reference AS originatingon, ";
        $fldHoldongTax = "";
        if ($docType == 2) {
            $where = " AND (invoicetype = 'FT') ";
        } elseif ($docType == 3) {
            $where = " AND (invoicetype = 'FT') ";
        } elseif ($docType == 4) {
            $tblInvoice = " workingdocument ";
            $tblInvoiceLine = " workingdocumentline ";
            $where = "";
        } elseif (($docType == 5) || ($docType == 6)) {
            $tblInvoice = " payment ";
            $fldSatus = "I.paymentstatus";
            $fldOrigination = "";
            $fldHoldongTax = " withholdingtaxAmount AS WithholdingTaxAmount, ";
            $where = " AND (invoicetype = 'RG') ";
            if ($docType == 6) {
                $where = " AND (invoicetype = 'NA') ";
            }
        }

        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($documentStatus != -1) {
            $where .= "AND UPPER(" . $fldSatus . ") = '" . $documentStatus . "' ";
        }
        $where .= " AND (CAST(invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE))";

        $fldPayDocument = "(COALESCE((SELECT P.invoicenumber FROM payment AS P WHERE P.originatingon = I.invoicenumber AND UPPER(P.paymentstatus) != 'A' LIMIT 1), '')) AS paymentDocument, \n";
        $fldMovDocument = "(COALESCE((SELECT M.invoicenumber FROM movimentofgoods AS M WHERE M.originatingon = I.invoicenumber AND UPPER(M.invoicestatus) != 'A' LIMIT 1), '')) AS movimentDocument, \n";

        $sql = "SELECT *, \n"
                . $fldOrigination
                . $fldHoldongTax
                . $fldPayDocument
                . $fldMovDocument
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newCustomerName, \n"
                . "(SELECT C.customertaxid FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTaxId, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName \n"
                . "FROM " . $tblInvoice . " AS I \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . "  \n"
                . "; "; //ORDER BY invoicedate DESC 
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleViewDetailsDocumentsTarget($invoiceNumber) {

        $invoiceNumber = $this->real_escape_string($invoiceNumber);

        $sql = "SELECT I.invoicenumber, I.invoicetype, I.totalInvoice, I.invoicestatus, I.invoicedate \n"
                . "FROM invoice AS I \n"
                . "WHERE reference = '" . $invoiceNumber . "' \n"
                . " UNION   \n";
        $sql .= "SELECT P.invoicenumber, P.invoicetype, P.totalInvoice, P.paymentstatus AS invoicestatus, P.transactiondate AS invoicedate \n"
                . "FROM payment AS P \n"
                . "WHERE P.originatingon = '" . $invoiceNumber . "' \n"
                . " UNION \n";
        $sql .= "SELECT M.invoicenumber, M.invoicetype, 0 AS totalInvoice, M.invoicestatus, M.invoicedate \n"
                . "FROM movimentofgoods AS M \n"
                . "WHERE M.originatingon = '" . $invoiceNumber . "' \n"
                . "ORDER BY invoicedate; ";


        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    /*     * * SALE REPORT ** */

    public function saleReportComissionReport($companyId, $userId, $initialDate, $endDate) {
        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        /* echo $companyId . "  <br>";
          echo $userId . "   <br>"; */
        /* SELECT invoicenumber, invoicedate 
          FROM invoice
          WHERE (CAST(invoicedate AS DATE) BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2021-01-24' AS DATE)) */

        function comission($field, $userType, $userId) {
            return ", SUM((CASE WHEN I." . $userType . " = '" . $userId . "' THEN (((IL.warehousePrice * (1 - (IL.settlementAmount/100))) * IL." . $field . "/100)*IL.quantity) ELSE 0 END)) AS " . $field . " \n";
        }

        $sql = "SELECT I.invoicenumber, I.invoicedate    "
                . comission('sellerComission', 'sellerUser', $userId)
                . comission('managerComission', 'managerUser', $userId)
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.invoicenumber = IL.invoiceNumber AND UPPER(I.invoicestatus)!= 'A' AND UPPER(IL.status)= 'N' AND IL.note = 0 "//AND (CAST(IL.devolution AS DATE)) < (CAST(NOW() AS DATE))  \n"
                . "AND (I.sellerUser = '" . $userId . "' OR I.managerUser = '" . $userId . "') \n"
                . "AND (CAST(IL.devolution AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "AND IL.warehousePrice > 0  \n"
                . "GROUP BY I.invoicenumber, I.invoicedate; ";

        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReportGetPaymentReport($companyId, $dependencyId,
            $sellerUser, $initialDate, $endDate) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $sellerUser = $this->real_escape_string($sellerUser);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $where = "";
        if ($sellerUser != -1) {
            $where .= " AND I.sourceid = '" . $sellerUser . "' ";
        }
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }

        $partSql = "SELECT I.request, I.invoicenumber, I.invoicetype, I.systementrydate, I.zzzzzz AS transactiondate, I.sourceid, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.sourceid LIMIT 1) AS operatorName, \n"
                . "IP.PaymentMechanism, (SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = IP.banksource LIMIT 1) AS banksource, \n"
                . "(SELECT CONCAT((SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = BC.nationalbankid LIMIT 1), ' - ', BC.accountnumber) FROM bankaccount AS BC WHERE BC.id = IP.accountBankTarget LIMIT 1) AS accountBankTarget, \n"
                . "(SELECT BT.designation FROM banktpaterminal AS BT WHERE BT.id = IP.tpaterminal LIMIT 1) AS tpaterminal, \n"
                . "IP.reference, IP.PaymentDate, IP.PaymentAmount  \n"
                . "FROM xxxxxx AS I, invoicepayment AS IP \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.invoicenumber = IP.invoicenumber AND UPPER(I.yyyyyy)!= 'A'  " . $where
                . "AND (CAST(zzzzzz AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n";
//
        $sql = str_replace("xxxxxx", "invoice", str_replace("yyyyyy", "invoicestatus", str_replace("zzzzzz", "invoicedate", $partSql)));
        $sql .= " UNION  ";
        $sql .= str_replace("xxxxxx", "payment", str_replace("yyyyyy", "paymentstatus", str_replace("zzzzzz", "transactiondate", $partSql)));
        $sql .= "ORDER BY request, operatorName, PaymentMechanism, accountBankTarget, tpaterminal, systementrydate, invoicenumber; ";


        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReportGetProductReport($companyId, $dependencyId,
            $sellerUser, $initialDate, $endDate, $sectionId, $category, $family, $brand) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $sellerUser = $this->real_escape_string($sellerUser);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);
        $sectionId = $this->real_escape_string($sectionId);
        $category = $this->real_escape_string($category);
        $family = $this->real_escape_string($family);
        $brand = $this->real_escape_string($brand);

        $where = "";
        if ($sellerUser != -1) {
            $where .= " AND I.sourceid = '" . $sellerUser . "' ";
        }
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }
        if ($sectionId != -1) {
            $where .= " AND IL.productSection = '" . $sectionId . "' ";
        }

        $whereCatFamBra = " AND IL.productId IN (SELECT P.id FROM product AS P WHERE P.xxxxxx = 'yyyyyy') ";
        if ($category != -1) {
            $where .= str_replace("xxxxxx", "productcategory", str_replace("yyyyyy", $category, $whereCatFamBra));
        }
        if ($family != -1) {
            $where .= str_replace("xxxxxx", "productfamily", str_replace("yyyyyy", $family, $whereCatFamBra));
        }
        if ($brand != -1) {
            $where .= str_replace("xxxxxx", "productbrand", str_replace("yyyyyy", $brand, $whereCatFamBra));
        }


        $sql = "SELECT I.request, I.invoicenumber, (CASE WHEN I.invoicetype = 'FT' THEN 'Crédito' ELSE 'Pronto' END) AS saleType, I.sourceid, "
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.sourceid LIMIT 1) AS operatorName, \n"
                . "IL.productDescription, IL.unitOfMeasure, IL.productId, IL.quantity, IL.warehousePrice, IL.indirectCost, IL.commercialCost, "
                . "IL.estimatedProfit, IL.settlementAmount, IL.sellerComission, IL.managerComission, "
                . "IL.fundInvestment, IL.fundReserve, IL.fundSocialaction, IL.priceWithComission, IL.ivaValue, IL.subtotalLine  \n"
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.invoicenumber = IL.invoicenumber AND UPPER(I.invoicestatus)!= 'A'  " . $where
                . "AND (CAST(invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n"
                . "ORDER BY I.request, saleType, operatorName, I.systementrydate, I.invoicenumber ; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReportGetUsers($userType, $companyId, $sender) {
        $userType = $this->real_escape_string($userType);
        $companyId = $this->real_escape_string($companyId);
        $sender = $this->real_escape_string($sender);
        $table = "invoice";
        if ($sender == "CLILIST") {
            $table = "customer";
        }

        $sql = "SELECT SU.userid, SU.userfullname  \n"
                . "FROM systemuser AS SU \n"
                . "WHERE SU.userid IN (SELECT DISTINCT " . $userType . " FROM " . $table . " WHERE companyid = '" . $companyId . "') \n"
                . "ORDER BY SU.userfullname; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReportPaymentGetUsers($companyId, $dependencyId, $userId) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $userId = $this->real_escape_string($userId);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }

        $whereUser = "";
        if ($userId != -1) {
            $whereUser .= " AND SU.id = '" . $userId . "' ";
        }

        $whereInInvoice = " (SU.id IN (SELECT DISTINCT sourceid FROM xxxxxx WHERE companyid = '" . $companyId . "' " . $where . ")) ";

        $sql = "SELECT SU.userid, SU.userfullname  \n"
                . "FROM systemuser AS SU \n"
                . "WHERE  (" . str_replace("xxxxxx", "invoice", $whereInInvoice) . " OR " . str_replace("xxxxxx", "payment", $whereInInvoice) . ") "
                . $whereUser
                . "ORDER BY SU.userfullname; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReporteInvoiceMissingPaymentList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerId = $this->real_escape_string($arrayFilterInfo['customerId']);
        $contractId = $this->real_escape_string($arrayFilterInfo['contractId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);

        $where = "";
        $fromCT = "";
        $whereCT = "";
        $limit = "";
        if (($customerId != -1) || ($contractId != -1)) {
            if ($customerId != -1) {
                $where .= " AND I.customerid = '" . $customerId . "' ";
            }
            if ($contractId != -1) {
                $where .= " AND I.contractid = '" . $contractId . "' ";
            }
        } else {
            $where .= " AND (CAST(I.invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
            if ($dependencyId != -1) {
                $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
            }
            if ($consumertype != -1) {
                $whereCT .= " AND CT.consumertype = '" . $consumertype . "' ";
            }
            if ($municipalityId != -1) {
                $whereCT .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
            }
            if ($neiborhood != "") {
                $whereCT .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
            }
            if ($whereCT != "") {
                $fromCT = ", contract AS CT ";
                //$where .= "AND I.contractid IN (SELECT CT.contractid FROM contract AS CT WHERE CT.companyid = I.companyid " . $whereCT . ") \n";
                $where .= " AND I.contractid = CT.contractid AND IPS.contractid = CT.contractid " . $whereCT;
            }
            $limit = " LIMIT " . ($startIdx ) . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "I.companyid, I.invoicenumber, I.customerid, I.customername, I.contractid, I.cil, \n"
                . "IPS.installmentdate, IPS.paymentamount, I.invoicedate, I.printnumber, \n"
                . "I.billingstartdate, I.consumptioninvoice, \n"
                . "(DATEDIFF(IPS.installmentdate, NOW())) AS delayDays, \n"
                . "(IPS.paymentamount - IPS.realpaymentamount) AS amountMissing, \n"
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newCustomerName, "
                . "(SELECT C.telephone1 FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTelephone, "
                . "(SELECT C.email FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newEmail, "
                . "(SELECT C.customertaxid FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS newTaxId \n";

        if ($onlynumber == 1) {
            $fields = " COUNT(IPS.id) ";
            $limit = "";
        }

        $sql = "SELECT  " . $fields
                . "FROM invoice AS I, invoicepaymentschedule AS IPS " . $fromCT . " \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.invoicenumber = IPS.invoicenumber " . $where . "  \n"
                . "AND ((IPS.realpaymentamount + 0.01) < IPS.paymentamount) AND UPPER(I.invoicestatus) != 'A' \n"
                . $limit
                . " ; "; //ORDER BY delayDays, IPS.installmentdate

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleReporteBillingNoticeList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $contractId = $this->real_escape_string($arrayFilterInfo['contractId']);

        $where = "";
        if ($contractId != -1) {
            $where .= " AND CT.contractid = '" . $contractId . "' ";
        } else {
            if ($dependencyId != -1) {
                $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
            }
            if ($consumertype != -1) {
                $where .= " AND CT.consumertype = '" . $consumertype . "' ";
            }
            if ($municipalityId != -1) {
                $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
            }
            if ($neiborhood != "") {
                $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
            }
        }

        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT SUM(IPS.paymentamount - IPS.realpaymentamount) FROM invoicepaymentschedule AS IPS "
                . "WHERE IPS.contractid = CT.contractid AND ((IPS.realpaymentamount + 0.01) < IPS.paymentamount)  ), 0)) AS amountMissing, \n"
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = CT.customerid LIMIT 1) AS newCustomerName, "
                . "(SELECT H.hydrometerid FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1) AS hydrometerid \n"
                . "FROM contract AS CT  \n"
                . "WHERE CT.companyid = '" . $companyId . "' " . $where . "  \n"
                . " ; "; //ORDER BY delayDays, IPS.installmentdate

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleGetAutocomplete($companyId, $documentType, $shelfLife = -1) {

        $companyId = $this->real_escape_string($companyId);
        $documentType = $this->real_escape_string($documentType);
        $shelfLife = $this->real_escape_string($shelfLife);

        $where = "";
        $tblInvoice = " invoice ";
        if ($documentType == 2) {//factura pendente
            $where = " AND (I.paymentamount < I.totalInvoice) AND UPPER(I.invoicestatus) != 'A' ";
        } elseif ($documentType == 3) {
            $tblInvoice = " payment ";
        } elseif ($documentType == 4) {
            $tblInvoice = " notadecredito ";
        } elseif ($documentType == 5) {
            $tblInvoice = " workingdocument ";
        }
        if ($shelfLife != -1) {
            $where .= " AND (CAST(I.shelflife AS DATE) BETWEEN CAST('1990-01-01' AS DATE) AND CAST(NOW() AS DATE)) ";
        }

        $sql = "SELECT invoicenumber \n"
                . "FROM " . $tblInvoice . " AS I \n"
                . "WHERE I.companyid = '" . $companyId . "' " . $where
                . "ORDER BY invoicenumber;";

        $result = $this->query($sql);

        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= "-/-" . $row['invoicenumber'];
        }
        mysqli_free_result($result);
        return $str;
    }

    public function postSaleSearchList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $searchLimit = $this->real_escape_string($arrayFilterInfo['searchLimit']);
        $documentNumber = $this->real_escape_string($arrayFilterInfo['documentNumber']);
        $customerName = $this->real_escape_string($arrayFilterInfo['customerName']);
        $documentType = $this->real_escape_string($arrayFilterInfo['documentType']);
        $shelfLife = $this->real_escape_string($arrayFilterInfo['shelfLife']);
        $field = $this->real_escape_string($arrayFilterInfo['field']);

        $where = "";
        $limit = "";
        $tblInvoice = " invoice ";
        $fldDate = ", invoicedate AS transactiondate "; //por causa dos recibos que também têm o campo ivoicedate
        if ($documentType == 2) {//factura pendente
            $where .= " AND ((I.paymentamount + I.WithholdingTaxAmount + 0.01) < I.totalInvoice) AND UPPER(I.invoicestatus) != 'A' ";
        } elseif ($documentType == 3) {//Recibos
            $tblInvoice = " payment ";
            $fldDate = "";
        } elseif ($documentType == 4) {
            $tblInvoice = " notadecredito ";
        } elseif (($documentType == 5) || ($documentType == 6)) {// Proformas e Encomendas
            $tblInvoice = " workingdocument ";
            if ($documentType == 5) {
                $where .= " AND (CAST(I.shelflife AS DATE) >= CAST(NOW() AS DATE)) ";
            } else {
                $where .= " AND I.invoicetype = 'NE' ";
            }
            $where .= "  AND UPPER(I.invoicestatus) != 'A' "
                    . " AND NOT EXISTS (SELECT id FROM invoice AS IV WHERE IV.reference = I.invoicenumber LIMIT 1) "; // consultas já processadas
        }
        if ($documentNumber != "") {
            $where .= " AND I.invoicenumber LIKE '" . $documentNumber . "%' ";
        }
        if ($customerName != "") {
            if ($field == 1) {
                $where .= " AND (I.cil = '" . $customerName . "') ";
            } elseif ($field == 2) {
                $where .= " AND (I.contractid = '" . $customerName . "') ";
            } elseif ($field == 3) {
                $where .= " AND (I.customerid = '" . $customerName . "') ";
            } else {
                $where .= " AND (I.customername LIKE '%" . $customerName . "%' OR "
                        . "I.customertaxid = '" . $customerName . "') ";
            }
        }

        if ($searchLimit != -1) {
            if (!is_numeric($searchLimit)) {
                $searchLimit = 5;
            }
            $limit = "LIMIT " . $searchLimit . " \n";
        }

        $sql = "SELECT DISTINCT * " . $fldDate . ", \n"
                . "I.contractid, I.cil, "
                . "C.companyname AS newCustomerName, \n"
                . "C.customertaxid AS newTaxId \n"
                . "FROM " . $tblInvoice . " AS I, customer AS C \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.companyid = C.companyid AND C.customerid = I.customerid " . $where
                . " "
                . $limit . ";";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleGetInstallmentInFault($companyId, $contractid) {

        $companyId = $this->real_escape_string($companyId);
        $$contractid = $this->real_escape_string($contractid);

        $sql = "SELECT *, invoicenumber AS originatingon, \n"
                . "(SELECT I.invoicedate FROM invoice AS I WHERE I.invoicenumber = IPS.invoicenumber LIMIT 1) AS invoicedate, \n"
                . "(paymentamount - realpaymentamount) AS paymentinfault \n"
                . "FROM invoicepaymentschedule AS IPS \n"
                . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND \n"
                . "((paymentamount - realpaymentamount) > '0.01') \n"
                . "ORDER BY id ;";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleInvoicePendengList($companyId, $searchLimit,
            $documentNumber, $customerName) {

        $companyId = $this->real_escape_string($companyId);
        $searchLimit = $this->real_escape_string($searchLimit);
        $documentNumber = $this->real_escape_string($documentNumber);
        $customerName = $this->real_escape_string($customerName);

        $where = "";
        $limit = "";

        if ($documentNumber != "") {
            $where .= " AND PSP.invoicenumber LIKE '" . $documentNumber . "%' ";
        }
        if ($customerName != "") {
            $where .= " AND (PSP.customerid IN "
                    . "(SELECT customerid FROM customer WHERE companyname LIKE '" . $customerName . "%' OR customertaxid LIKE '" . $customerName . "%' OR CAST(customerid AS CHAR) LIKE '" . $customerName . "%')) ";
        }
        if (!is_numeric($searchLimit)) {
            $searchLimit = 5;
        }
        $limit = "LIMIT " . $searchLimit . " \n";

        $sql = "SELECT DISTINCT PSP.invoicenumber, C.customerid, PSP.entrydate, \n"
                . "C.companyname AS customerName \n"
                . "FROM productstockpending AS PSP, customer AS C  \n"
                . "WHERE PSP.companyid = '" . $companyId . "' AND PSP.quantity > 0 AND PSP.customerid = C.customerid "
                . $where
                . "ORDER BY PSP.entrydate "
                . $limit . ";";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function postSaleInvoicePendengLines($invoiceNumber, $stockSource) {

        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $stockSource = $this->real_escape_string($stockSource);

        $strProductStockActual = " (COALESCE((SELECT PSA.quantity FROM productstockactual AS PSA WHERE PSA.stockid = " . $stockSource . " AND PSA.productid = IL.productId LIMIT 1), 0)) AS productstockactual \n";

        $sql = "SELECT *, "
                . "(SELECT PSP.quantity FROM productstockpending AS PSP WHERE PSP.invoicenumber = IL.invoiceNumber AND PSP.productid = IL.productId LIMIT 1) AS quantityPending, \n"
                . "(SELECT P.devolutiondays FROM product AS P WHERE P.id = IL.productId LIMIT 1) AS devolutiondays, \n"
                . $strProductStockActual
                . "FROM invoiceline AS IL \n"
                . "WHERE IL.productid IN (SELECT PSP.productid FROM productstockpending AS PSP WHERE PSP.quantity > 0 AND PSP.invoicenumber = '" . $invoiceNumber . "') "
                . "AND IL.invoiceNumber = '" . $invoiceNumber . "'; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    private function movimentSetInvoiceNumber($companyid, $invoiceType, $invoiceSerie, $sourceid,
            $customerid, $dependencyidtarget, $department) {

        $companyid = $this->real_escape_string($companyid);
        $invoiceType = $this->real_escape_string($invoiceType);
        $invoiceSerie = $this->real_escape_string($invoiceSerie);
        $dependencyidtarget = $this->real_escape_string($dependencyidtarget);
        $department = $this->real_escape_string($department);
        $invoiceSeq = 1;
        $sourceid = $this->real_escape_string($sourceid);
        $customerid = $this->real_escape_string($customerid);
        if ($customerid == -1) {
            $customerid = 9999;
        }

        /* $sql = "SELECT MAX(invoicesequence) "
          . "FROM movimentofgoods  "
          . "WHERE companyid = '" . $companyid . "' AND invoicetype = '" . $invoiceType . "' AND invoiceserie = '" . $invoiceSerie . "'; "; */

        $sql = "SELECT MAX(invoicesequence) AS  invoicesequence  "
                . "FROM movimentofgoods  "
                . "WHERE companyid = ? AND invoicetype = ? AND invoiceserie = ?; ";

        $link = new PDO('mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName . ';charset=utf8mb4',
                $this->user,
                $this->pass,
                array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false
                )
        );

        $handle = $link->prepare($sql);

        $handle->bindValue(1, $companyid);
        $handle->bindValue(2, $invoiceType);
        $handle->bindValue(3, $invoiceSerie);

        $handle->execute();
        $result = $handle->fetchAll(PDO::FETCH_OBJ);
        foreach ($result as $row) {
            $invoiceSeq = $row->invoicesequence;
            $invoiceSeq += 1;
        }

        /*    $result = $this->query($sql);
          if ($result->num_rows > 0) {
          $row = $result->fetch_row();
          $invoiceSeq = $row[0] + 1;
          } */
        /* $row = $handle->fetchAll();
          // $row = $handle->fetch_row();
          $invoiceSeq = $row[0] + 1; */

        $invoiceNumber = $invoiceType . " " . $invoiceSerie . "/" . $invoiceSeq;

        $tblAddress = ($invoiceType == "GD") ? "supplier" : "customer";
        $fldId = ($invoiceType == "GD") ? "id" : "customerid";
        $fldTax = ($invoiceType == "GD") ? "suppliertaxid" : "customertaxid";
        $fldAddress = "C.companyname, C." . $fldTax . ", C.billingcountry, C.billingcity, C.billingpostalcode,";
        if (($invoiceType == "GA") || ($invoiceType == "CI")) {
            $companyName = ($dependencyidtarget == -1) ? "'" . $department . "' " :
                    "(COALESCE((SELECT designation FROM companydependency WHERE id = '" . $dependencyidtarget . "' LIMIT 1), ''))";
            $fldAddress = $companyName . ", '', 1, '', '',";
        }

        $sql = "INSERT INTO movimentofgoods ("
                . "companyid, invoicetype, invoiceserie, invoicesequence, invoicenumber, invoicestatus, \n"
                . "invoiceStatusDate, Sourceidstatus, Sourcebilling, invoicedate, sourceid, systementrydate, \n"
                . "customername, customertaxid, customercountry, customercity, customerpostalcode, \n"
                . "cityshipfrom, districtshipfrom, \n"
                . "neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, postalCodeshipfrom) \n"
                . "SELECT '" . $companyid . "', '" . $invoiceType . "', '" . $invoiceSerie . "', '" . $invoiceSeq . "', '" . $invoiceNumber . "', "
                . "'N', NOW(), '" . $sourceid . "', 'P', NOW(), '" . $sourceid . "', NOW(),  \n"
                . $fldAddress . " \n"
                . "CO.billingcity, CO.billingdistrict, \n"
                . "CO.billingneiborhood, CO.billingstreetname, CO.billingbuildingnumber, CO.billingpostalcode  \n"
                . "FROM " . $tblAddress . " AS C, company AS CO  \n"
                . "WHERE (NOT EXISTS (SELECT id FROM movimentofgoods WHERE invoicenumber = '" . $invoiceNumber . "'))  \n"
                . " AND C." . $fldId . " = '" . $customerid . "' AND CO.id = '" . $companyid . "' LIMIT 1; \n";

        $result = $this->multi_query($sql);

        return $invoiceNumber;
    }

    public function movimentNewMoviment($arrayNewMoviment, $arrayMovimentLines) {

        $companyid = $this->real_escape_string($arrayNewMoviment['companyid']);
        $dependencyid = $this->real_escape_string($arrayNewMoviment['dependencyid']);
        $invoicetype = $this->real_escape_string($arrayNewMoviment['invoicetype']);
        $invoiceserie = $this->real_escape_string($arrayNewMoviment['invoiceserie']);
        $sourceid = $this->real_escape_string($arrayNewMoviment['sourceid']);
        $customerid = $this->real_escape_string($arrayNewMoviment['customerid']);
        $movimenttargettype = $this->real_escape_string($arrayNewMoviment['movimenttargettype']);
        $stocksource = $this->real_escape_string($arrayNewMoviment['stocksource']);
        $supplierid = $this->real_escape_string($arrayNewMoviment['supplierid']);
        $dependencyidtarget = $this->real_escape_string($arrayNewMoviment['dependencyidtarget']);
        $department = $this->real_escape_string($arrayNewMoviment['department']);
        $movimentreason = $this->real_escape_string($arrayNewMoviment['movimentreason']);
        $originatingon = $this->real_escape_string($arrayNewMoviment['originatingon']);

        $totalItems = $this->real_escape_string($arrayNewMoviment['totalItems']);
        $productWeight = $this->real_escape_string($arrayNewMoviment['productWeight']);
        $productNetWeight = $this->real_escape_string($arrayNewMoviment['productNetWeight']);

        $countryshipfrom = $this->real_escape_string($arrayNewMoviment['countryshipfrom']);
        $provinceshipfrom = $this->real_escape_string($arrayNewMoviment['provinceshipfrom']);
        $municipalityshipfrom = $this->real_escape_string($arrayNewMoviment['municipalityshipfrom']);

        $deliveryIdShipto = $this->real_escape_string($arrayNewMoviment['deliveryIdShipto']);
        $countryshipto = $this->real_escape_string($arrayNewMoviment['countryshipto']);
        $provinceshipto = $this->real_escape_string($arrayNewMoviment['provinceshipto']);
        $municipalityshipto = $this->real_escape_string($arrayNewMoviment['municipalityshipto']);
        $cityshipto = $this->real_escape_string($arrayNewMoviment['cityshipto']);
        $districtshipto = $this->real_escape_string($arrayNewMoviment['districtshipto']);
        $comunashipto = $this->real_escape_string($arrayNewMoviment['comunashipto']);
        $neiborhoodshipto = $this->real_escape_string($arrayNewMoviment['neiborhoodshipto']);
        $streetnameshipto = $this->real_escape_string($arrayNewMoviment['streetnameshipto']);
        $buildingnumbershipto = $this->real_escape_string($arrayNewMoviment['buildingnumbershipto']);
        $postalcodeshipto = $this->real_escape_string($arrayNewMoviment['postalcodeshipto']);
        $phoneshipto = $this->real_escape_string($arrayNewMoviment['phoneshipto']);

        $extraNote = $this->real_escape_string($arrayNewMoviment['extraNote']);
        $sellerUser = $this->real_escape_string($arrayNewMoviment['sellerUser']);
        $managerUser = $this->real_escape_string($arrayNewMoviment['managerUser']);
        $partnershipUser = $this->real_escape_string($arrayNewMoviment['partnershipUser']);

        $invoiceUser = $this->real_escape_string($arrayNewMoviment['invoiceUser']);
        $invoiceSellerUser = $this->real_escape_string($arrayNewMoviment['invoiceSellerUser']);
        $invoiceManagerUser = $this->real_escape_string($arrayNewMoviment['invoiceManagerUser']);
        $invoicePartnershipUser = $this->real_escape_string($arrayNewMoviment['invoicePartnershipUser']);
        $invoiceEntryDate = date_create(date("Y-m-d H:i:s"));
        $printnumber = $invoicetype . $this->getRandomString(5) . round(microtime(true) * 1000);

        if ($customerid != -1) {
            if (!$this->checkCustomerInDataBase($customerid)) {
                return json_encode(array("status" => 0, "msg" => "Este cliente foi eliminado da base de dados."));
            }
        }
        if ($originatingon != "") {
            if ($this->checkOriginationIsCanceled($originatingon)) {
                return json_encode(array("status" => 0, "msg" => "Documento de origem anulado."));
            }
        }

        $customerid = ($invoicetype == "GD") ? $supplierid : $customerid;
        //  $customerid = $this->real_escape_string($customerid);
        if ($customerid == -1) {
            $customerid = 9999;
        }
        /*   $invoiceNumber = $this->movimentSetInvoiceNumber($companyid, $invoicetype, $invoiceserie, $sourceid,
          $customerSupplier, $dependencyidtarget, $department); */



        $arrayHash = $this->invoiceSetHash($invoicetype, $invoiceserie, $invoiceEntryDate, 0, "movimentofgoods");
        $invoiceSequence = $arrayHash['invoiceSequence'];
        $invoiceNumber = $arrayHash['invoiceNumber'];
        $hash = $arrayHash['hash'];
        $hashControl = $arrayHash['hashControl'];
        $invoiceEntryDate = date_format($invoiceEntryDate, "Y-m-d H:i:s");

        $tblAddress = ($invoicetype == "GD") ? "supplier" : "customer";
        $fldId = ($invoicetype == "GD") ? "id" : "customerid";
        $fldTax = ($invoicetype == "GD") ? "suppliertaxid" : "customertaxid";
        $fldAddress = "C.companyname, C." . $fldTax . ", C.billingcountry, C.billingcity, C.billingpostalcode,";
        if (($invoicetype == "GA") || ($invoicetype == "CI")) {
            $companyName = ($dependencyidtarget == -1) ? "'" . $department . "' " :
                    "(COALESCE((SELECT designation FROM companydependency WHERE id = '" . $dependencyidtarget . "' LIMIT 1), ''))";
            $fldAddress = $companyName . ", '', 1, '', '',";
        }

        $sql = "INSERT INTO movimentofgoods ("
                . "companyid, dependencyid, invoicetype, invoiceserie, invoicesequence, invoicenumber, invoicestatus, \n"
                . "invoiceStatusDate, Sourceidstatus, Sourcebilling, hash, hashcontrol, "
                . "invoicedate, sourceid, systementrydate, \n"
                . "customername, customertaxid, customercountry, customercity, customerpostalcode, \n"
                . "cityshipfrom, districtshipfrom, \n"
                . "neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, postalCodeshipfrom, printnumber) \n"
                . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $invoicetype . "', '" . $invoiceserie . "', '" . $invoiceSequence . "', '" . $invoiceNumber . "', "
                . "'N', '" . $invoiceEntryDate . "', '" . $sourceid . "', 'P', '" . $hash . "', '" . $hashControl . "', "
                . "'" . $invoiceEntryDate . "', '" . $sourceid . "', '" . $invoiceEntryDate . "',  \n"
                . $fldAddress . " \n"
                . "CO.billingcity, CO.billingdistrict, \n"
                . "CO.billingneiborhood, CO.billingstreetname, CO.billingbuildingnumber, CO.billingpostalcode, '" . $printnumber . "'  \n"
                . "FROM " . $tblAddress . " AS C, company AS CO  \n"
                . "WHERE (NOT EXISTS (SELECT id FROM movimentofgoods WHERE invoicenumber = '" . $invoiceNumber . "'))  \n"
                . " AND C." . $fldId . " = '" . $customerid . "' AND CO.id = '" . $companyid . "' LIMIT 1; \n";


        $sql .= "UPDATE movimentofgoods SET \n"
                . "customerid = '" . $customerid . "', "
                . "movimenttargettype = '" . $movimenttargettype . "', "
                . "stocksource = '" . $stocksource . "', "
                . "supplierid = '" . $supplierid . "', "
                . "dependencyidtarget = '" . $dependencyidtarget . "', "
                . "department = '" . $department . "', "
                . "movimentreason = '" . $movimentreason . "', \n"
                . "deliveryIdShipto = '" . $deliveryIdShipto . "', "
                . "deliverydateshipto = '" . $invoiceEntryDate . "', "
                . "countryshipto = '" . $countryshipto . "', "
                . "provinceshipto = '" . $provinceshipto . "', "
                . "municipalityshipto = '" . $municipalityshipto . "', "
                . "cityshipto = '" . $cityshipto . "', "
                . "districtshipto = '" . $districtshipto . "', "
                . "comunashipto = '" . $comunashipto . "', "
                . "neiborhoodshipto = '" . $neiborhoodshipto . "', "
                . "streetnameshipto = '" . $streetnameshipto . "', "
                . "buildingnumbershipto = '" . $buildingnumbershipto . "', \n"
                . "postalcodeshipto = '" . $postalcodeshipto . "', "
                . "phoneshipto = '" . $phoneshipto . "', "
                . "deliveryidshipfrom = '" . $deliveryIdShipto . "', "
                . "deliverydateshipfrom = '" . $invoiceEntryDate . "', "
                . "countryshipfrom = '" . $countryshipfrom . "', "
                . "provinceshipfrom = '" . $provinceshipfrom . "', "
                . "municipalityshipfrom = '" . $municipalityshipfrom . "', "
                . "movementendtime = '" . $invoiceEntryDate . "', "
                . "movementstarttime = '" . $invoiceEntryDate . "', \n"
                . "originatingon = '" . $originatingon . "', "
                . "totalItems  = '" . $totalItems . "', "
                . "productWeight = '" . $productWeight . "', "
                . "productNetWeight = '" . $productNetWeight . "', "
                . "extraNote = '" . $extraNote . "', \n"
                . "sellerUser  = '" . $sellerUser . "', "
                . "managerUser  = '" . $managerUser . "', "
                . "partnershipUser  = '" . $partnershipUser . "', \n"
                . "invoiceUser  = '" . $invoiceUser . "', "
                . "invoiceSellerUser  = '" . $invoiceSellerUser . "', "
                . "invoiceManagerUser  = '" . $invoiceManagerUser . "', "
                . "invoicePartnershipUser  = '" . $invoicePartnershipUser . "'  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        /*   LINES */
        foreach ($arrayMovimentLines as $key => $ivLine) {
            $productID = $this->real_escape_string($ivLine['productID']);
            $productType = $this->real_escape_string($ivLine['productType']);
            $productDescription = $this->real_escape_string($ivLine['productDescription']);
            $productBarCode = $this->real_escape_string($ivLine['productBarCode']);
            $productUnit = $this->real_escape_string($ivLine['productUnit']);
            $productSection = $this->real_escape_string($ivLine['productSection']);
            $productIvaCategory = $this->real_escape_string($ivLine['productIvaCategory']);
            $productStock = $this->real_escape_string($ivLine['productStock']);
            $productWeight = $this->real_escape_string($ivLine['productWeight']);
            $productNetWeight = $this->real_escape_string($ivLine['productNetWeight']);
            $serialnumber = $this->real_escape_string($ivLine['serialnumber']);
            $lotenumber = $this->real_escape_string($ivLine['lotenumber']);
            $devolutiondays = $this->real_escape_string($ivLine['devolutiondays']);
            $devolutionDate = Date('Y-m-d', strtotime('+' . $devolutiondays . ' days'));

            $quant = $this->real_escape_string($ivLine['quant']);
            $warehousePrice = $this->real_escape_string($ivLine['warehousePrice']);
            $indirectCost = $this->real_escape_string($ivLine['indirectCost']);
            $commercialCost = $this->real_escape_string($ivLine['commercialCost']);
            $estimatedProfit = $this->real_escape_string($ivLine['estimatedProfit']);
            $sellerComission = $this->real_escape_string($ivLine['sellerComission']);
            $managerComission = $this->real_escape_string($ivLine['managerComission']);
            $fundInvestment = $this->real_escape_string($ivLine['fundInvestment']);
            $fundReserve = $this->real_escape_string($ivLine['fundReserve']);
            $fundSocialaction = $this->real_escape_string($ivLine['fundSocialaction']);

            $priceWithComission = $this->real_escape_string($ivLine['priceWithComission']);
            $descount = $this->real_escape_string($ivLine['descount']);
            $iva = $this->real_escape_string($ivLine['iva']);
            $ivaValue = $this->real_escape_string($ivLine['ivaValue']);
            $ivaWithoutDescountValue = $this->real_escape_string($ivLine['ivaWithoutDescountValue']);
            $subtotalLine = $this->real_escape_string($ivLine['subtotalLine']);
            $exemptionCode = $this->real_escape_string($ivLine['exemptionCode']);
            $exemptionReason = $this->real_escape_string($ivLine['exemptionReason']);
            $note = (int) $this->real_escape_string($ivLine['note']);
            $status = $this->real_escape_string($ivLine['status']);

            if ($status != 0) {
                $strInsertLine = "INSERT INTO movimentofgoodsline (invoiceNumber, customerid, productCode, productDescription, quantity, "
                        . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                        . "taxPointDate, description, creditAmount, taxPercentage, \n"
                        . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                        . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                        . "productWeight, productNetWeight, serialnumber, lotenumber, devolution, \n"
                        . "sellerComission, managerComission, \n"
                        . "fundInvestment, fundReserve, fundSocialaction, \n"
                        . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note) \n"
                        . "SELECT '" . $invoiceNumber . "', '" . $customerid . "', '" . $productID . "', '" . $productDescription . "', '" . $quant . "', \n"
                        . "'" . $productUnit . "', '" . $warehousePrice . "', '" . $indirectCost . "', '" . $commercialCost . "', '" . $estimatedProfit . "',  \n"
                        . "'" . $invoiceEntryDate . "', '" . $productDescription . "', '" . $priceWithComission . "', '" . $iva . "', \n"
                        . "'" . $exemptionReason . "', '" . $exemptionCode . "', '" . $descount . "', \n"
                        . "'" . $productID . "', '" . $productType . "', '" . $productBarCode . "', '" . $productSection . "', '" . $productIvaCategory . "', '" . $productStock . "', \n"
                        . "'" . $productWeight . "', '" . $productNetWeight . "', '" . $serialnumber . "', '" . $lotenumber . "', '" . $devolutionDate . "', \n"
                        . "'" . $sellerComission . "', '" . $managerComission . "', \n"
                        . "'" . $fundInvestment . "', '" . $fundReserve . "', '" . $fundSocialaction . "', \n"
                        . "'" . $priceWithComission . "', '" . $ivaValue . "', '" . $ivaWithoutDescountValue . "', '" . $subtotalLine . "', '" . $note . "'  "
                        . "FROM insert_table; \n";

                $strInsertLine .= "UPDATE productstockpending SET quantity = (quantity - '" . $quant . "') \n"
                        . "WHERE companyid = '" . $companyid . "' AND invoicenumber = '" . $originatingon . "' AND productid = '" . $productID . "'; \n";

                $strInsertLine .= "UPDATE productstockactual SET quantity = (quantity - '" . $quant . "') \n"
                        . "WHERE stockid = '" . $stocksource . "' AND productid = '" . $productID . "'; \n";

                $sql = $sql . $strInsertLine;
            }
        }


        $sql = $sql . "DELETE FROM productstockpending WHERE quantity <= 0; \n";

        $sql .= "SELECT * \n"
                . "FROM movimentofgoods  \n"
                . "WHERE printnumber = '" . $printnumber . "';";

        unset($ivLine);

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => false, "msg" => "Não possível dar saída de estoque.", "invoiceNumber" => $printnumber));
        }
        return json_encode(array("status" => true, "invoiceNumber" => $printnumber, "in" => $invoiceNumber));
    }

    public function movimentListGet($companyId, $dependencyId, $docType, $docNumber, $docDate,
            $customerId, $supplierId, $operatorId, $serialNumber, $loteNumber, $productId) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $docType = $this->real_escape_string($docType);
        $docNumber = $this->real_escape_string($docNumber);
        $docDate = $this->real_escape_string($docDate);
        $customerId = $this->real_escape_string($customerId);
        $supplierId = $this->real_escape_string($supplierId);
        $operatorId = $this->real_escape_string($operatorId);

        $serialNumber = $this->real_escape_string($serialNumber);
        $loteNumber = $this->real_escape_string($loteNumber);
        $productId = $this->real_escape_string($productId);

        $where = "";
        if ($docType == 1) {
            $where .= " AND (invoicetype = 'GR' OR invoicetype = 'GT') ";
        } elseif ($docType == 3) {
            $where .= " AND (invoicetype = 'GA') ";
        } elseif ($docType == 5) {
            $where .= " AND (invoicetype = 'CI') ";
        } elseif ($docType == 6) {
            $where .= " AND (invoicetype = 'AE') ";
        } elseif ($docType == 7) {
            $where .= " AND (invoicetype = 'GD') ";
        }

        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($docNumber != -1) {
            $where .= " AND invoicenumber = '" . $docNumber . "' ";
        }
        if ($docDate != -1) {
            $where .= " AND CAST(invoicedate AS DATE) = CAST('" . $docDate . "' AS DATE)";
        }
        if ($customerId != -1) {
            $where .= " AND customerid = '" . $customerId . "' ";
        }
        if ($supplierId != -1) {
            $where .= " AND supplierid = '" . $supplierId . "' ";
        }
        if ($operatorId != -1) {
            $where .= " AND sourceid = '" . $operatorId . "' ";
        }

        if ($serialNumber != -1) {
            $where .= " AND (M.invoicenumber IN (SELECT IL.invoiceNumber FROM movimentofgoodsline AS IL WHERE IL.serialnumber = '" . $serialNumber . "' )) ";
        }
        if ($loteNumber != -1) {
            $where .= " AND (M.invoicenumber IN (SELECT IL.invoiceNumber FROM movimentofgoodsline AS IL WHERE IL.lotenumber = '" . $loteNumber . "' )) ";
        }
        if ($productId != -1) {
            $where .= " AND (M.invoicenumber IN (SELECT IL.invoiceNumber FROM movimentofgoodsline AS IL WHERE IL.productId = '" . $productId . "' )) ";
        }


        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = M.sourceid LIMIT 1) AS operatorName, \n"
                . "(SELECT C.email FROM customer AS C WHERE C.customerid = M.customerid LIMIT 1) AS newEmail, "
                . "(SELECT PS.desigination FROM productstock AS PS WHERE PS.id = M.stocksource) AS stockOut \n"
                . "FROM movimentofgoods AS M \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . "   \n"
                . "ORDER BY invoicedate DESC  ; ";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function movimentPendingList($companyId, $docNumber, $docDate,
            $customerId, $operatorId, $dependencyId) {

        $companyId = $this->real_escape_string($companyId);
        $docNumber = $this->real_escape_string($docNumber);
        $docDate = $this->real_escape_string($docDate);
        $customerId = $this->real_escape_string($customerId);
        $operatorId = $this->real_escape_string($operatorId);
        $dependencyId = $this->real_escape_string($dependencyId);

        $where = "";
        if ($docNumber != -1) {
            $where .= " AND invoicenumber = '" . $docNumber . "' ";
        }
        if ($docDate != -1) {
            $where .= " AND CAST(invoicedate AS DATE) = CAST('" . $docDate . "' AS DATE)";
        }
        if ($customerId != -1) {
            $where .= " AND customerid = '" . $customerId . "' ";
        }
        if ($operatorId != -1) {
            $where .= " AND sourceid = '" . $operatorId . "' ";
        }


        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.sourceid LIMIT 1) AS operatorName \n"
                . "FROM invoice AS I \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . " AND UPPER(I.invoicestatus) != 'A'  "
                . "AND dependencyid = '" . $dependencyId . "' \n"
                . "AND invoicenumber IN (SELECT PSP.invoicenumber FROM productstockpending AS PSP WHERE PSP.companyid = '" . $companyId . "') \n"
                . "ORDER BY invoicedate ; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function movimentCancelMoviment($invoiceNumber, $originationOn, $reason, $userId, $fromOriginationOn) {
        $invoiceNumber = $this->real_escape_string($invoiceNumber);
        $originationOn = $this->real_escape_string($originationOn);
        $reason = $this->real_escape_string($reason);
        $userId = $this->real_escape_string($userId);
        $fromOriginationOn = $this->real_escape_string($fromOriginationOn);
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");

        //Cancel Entrance
        $sql = "UPDATE movimentofgoods SET \n"
                . "invoicestatus = 'A', "
                . "invoiceStatusDate = '" . $invoiceEntryDate . "', "
                . "Sourceidstatus = '" . $userId . "', "
                . "invoicestatusreason = '" . $reason . "' \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";
        //Add to Stock Source
        $sql .= "UPDATE productstockactual AS PSA SET \n"
                . "quantity = (quantity + (SELECT IL.quantity FROM movimentofgoodsline AS IL WHERE IL.productId = PSA.productid AND IL.invoiceNumber = '" . $invoiceNumber . "' AND IL.productStock = 1)) "
                . "WHERE PSA.stockid = (SELECT I.stocksource FROM movimentofgoods AS I WHERE I.invoicenumber = '" . $invoiceNumber . "') AND  "
                . "PSA.productid IN (SELECT IL.productId FROM movimentofgoodsline AS IL WHERE IL.invoiceNumber = '" . $invoiceNumber . "' AND IL.productStock = 1); \n";
        //Reset pending
        if ($originationOn != "") {
            if (!$fromOriginationOn) {
                $sql .= "UPDATE productstockpending AS PSP SET \n"
                        . "quantity = (quantity + (SELECT IL.quantity FROM movimentofgoodsline AS IL WHERE IL.productId = PSP.productid AND IL.invoiceNumber = '" . $invoiceNumber . "' AND IL.productStock = 1)) "
                        . "WHERE PSP.invoicenumber = '" . $originationOn . "' AND "
                        . "PSA.productid IN (SELECT IL.productId FROM movimentofgoodsline AS IL WHERE IL.invoiceNumber = '" . $invoiceNumber . "' AND IL.productStock = 1); \n";
                $sql .= "INSERT INTO productstockpending ("
                        . "companyid, invoicenumber, customerid, productid, quantity) \n"
                        . "SELECT I.companyid, '" . $originationOn . "', I.customerid, IL.productId, IL.quantity  \n"
                        . "FROM movimentofgoods AS I, movimentofgoodsline AS IL \n"
                        . "WHERE I.invoicenumber = '" . $invoiceNumber . "' AND I.invoicenumber = IL.invoiceNumber AND IL.productStock = 1 AND "
                        . "NOT EXISTS(SELECT id FROM productstockpending AS PSP WHERE PSP.invoicenumber = '" . $originationOn . "' AND PSP.productid = IL.productId); \n";
            }
        }
        //Transfer lines
        $sql .= "INSERT INTO movimentofgoodslinecanceled ("
                . "invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, serialnumber, lotenumber, devolution, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note) \n"
                . "SELECT invoiceNumber, customerid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, serialnumber, lotenumber, devolution, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note  \n"
                . "FROM movimentofgoodsline AS IL  \n"
                . "WHERE IL.invoiceNumber = '" . $invoiceNumber . "'; \n";
        //Delete old lines
        $sql .= "DELETE FROM movimentofgoodsline WHERE invoiceNumber = '" . $invoiceNumber . "'; \n";

        $this->multi_query($sql);

        return json_encode(array("status" => 1, "msg" => "Guia de entrada anulada com sucesso."));
    }

    public function systemUserGetPermissionListNoJasonGME($userId) {
        $userId = $this->real_escape_string($userId);
        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT SUP.permissionstatus FROM gmesystemuserpermission AS SUP "
                . "WHERE SUP.permissioncode = P.permissionCode AND SUP.userid = '" . $userId . "' LIMIT 1), 0)) AS permissionstatus \n"
                . "FROM gmepermission AS P \n"
                . "ORDER BY permissionCode;";

        return $this->query($sql);
    }

    public function companyListSearch($companyType, $SearchLimit, $searchTag) {

        $companyType = $this->real_escape_string($companyType);
        $SearchLimit = $this->real_escape_string($SearchLimit);
        $searchTag = $this->real_escape_string($searchTag);
        $where = "";
        if ($companyType != -1) {
            $where = " AND companytype = " . $companyType . " ";
        }
        if ($searchTag != "") {
            $where = $where . " AND (CAST(companyid AS CHAR) = '" . $searchTag . "' OR "
                    . "companyname LIKE '" . $searchTag . "%' OR "
                    . "companytaxid = '" . $searchTag . "') ";
        }

        $limit = "";
        if ($SearchLimit != -1) {
            $limit = is_numeric($SearchLimit) ? $SearchLimit : 5;
            $limit = "LIMIT " . $limit;
        }


        $sql = "SELECT * "
                . "FROM company "
                . "WHERE id > 0 " . $where
                . "ORDER BY companyname "
                . $limit . ";
";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function companyListGet($initialDate, $endDate,
            $contratorType, $fund, $systemType) {

        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);
        $contratorType = $this->real_escape_string($contratorType);
        $fund = $this->real_escape_string($fund);
        $systemType = $this->real_escape_string($systemType);

        $where = "";
        if ($contratorType != -1) {
            $where = " AND companytype = " . $contratorType . " ";
        }
        if ($fund != -1) {
            $where = " AND fundid = " . $fund . " ";
        }
        if ($systemType != -1) {
            $where = " AND systemtype = " . $systemType . " ";
        }


        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = C.entryuser LIMIT 1) AS operatorName, \n"
                . "(SELECT F.designation FROM fund AS F WHERE F.fundcode = C.fundid LIMIT 1) AS fund \n"
                . "FROM company AS C \n"
                . "WHERE (CAST(C.entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) " . $where
                . "ORDER BY companyname;";

        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function gmeFrontAccessGetList($initialDate, $endDate) {

        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $sql = "UPDATE frontacess SET \n"
                . "socialnetwork = 'fb' \n"
                . "WHERE browser LIKE '%facebook%' AND socialnetwork = ''; \n";


        $sql .= "SELECT id, recomendationuser, socialnetwork, \n"
                . "COALESCE((SELECT U.userfullname FROM systemuser AS U WHERE U.userid = FA.recomendationuser LIMIT 1), '') AS operatorName \n"
                . "FROM frontacess AS FA \n"
                . "WHERE (CAST(FA.acesstime AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "ORDER BY operatorName;";


        $arrayResult = array();
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        array_push($arrayResult, json_encode($row));
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        return $arrayResult;
    }

    public function scheduleActivitySave($arrayActivityInfo) {

        $companyid = $this->real_escape_string($arrayActivityInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayActivityInfo['dependencyid']);
        $activityId = $this->real_escape_string($arrayActivityInfo['activityId']);
        $activity = $this->real_escape_string($arrayActivityInfo['activity']);
        $details = $this->real_escape_string($arrayActivityInfo['details']);
        $localdatetime = $this->real_escape_string($arrayActivityInfo['localdatetime']);
        $location = $this->real_escape_string($arrayActivityInfo['location']);
        $customername = $this->real_escape_string($arrayActivityInfo['customername']);
        $contacts = $this->real_escape_string($arrayActivityInfo['contacts']);
        $email = $this->real_escape_string($arrayActivityInfo['email']);
        $employeename = $this->real_escape_string($arrayActivityInfo['employeename']);
        $sourceid = $this->real_escape_string($arrayActivityInfo['sourceid']);

        $sql = "";
        if (!is_numeric($activityId)) {
            $sql = "INSERT INTO scheduleactivity ("
                    . "companyid, dependencyid, activity, details, "
                    . "localdatetime, location, customername, contacts, email, "
                    . "employeename, entrydate, sourceid) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $activity . "', '" . $details . "', "
                    . "'" . $localdatetime . "', '" . $location . "', '" . $customername . "', '" . $contacts . "', '" . $email . "', "
                    . "'" . $employeename . "', NOW(), '" . $sourceid . "'  \n"
                    . "FROM insert_table; \n";
        } else {
            $sql = "UPDATE scheduleactivity SET \n"
                    . "activity = '" . $activity . "', "
                    . "details = '" . $details . "', "
                    . "localdatetime = '" . $localdatetime . "', "
                    . "location = '" . $location . "', "
                    . "customername = '" . $customername . "', "
                    . "contacts = '" . $contacts . "', "
                    . "email = '" . $email . "', "
                    . "employeename = '" . $employeename . "' \n"
                    . "WHERE id = " . $activityId . ";";
        }



        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Actividade agendada com sucesso."));
    }

    public function scheduleActivityList($companyId, $dependencyId,
            $activityId, $initialDate, $endDate) {


        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $activityId = $this->real_escape_string($activityId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $where = "";
        if ($activityId != -1) {
            $where = " AND id = '" . $activityId . "'  ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = SA.sourceid LIMIT 1) AS operatorName \n"
                . "FROM scheduleactivity AS SA "
                . "WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "' " . $where
                . "AND (CAST(localdatetime AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "ORDER BY (CAST(localdatetime AS DATETIME)); ";


        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function scheduleActivityDelete($activityId) {
        $activityId = $this->real_escape_string($activityId);

        $sql = "DELETE FROM scheduleactivity WHERE id = " . $activityId . "; ";
        $result = $this->query($sql);
        return json_encode(array("status" => 1, "msg" => "Actividade eliminada com sucesso."));
    }

    public function patrimonyListGet($companyId, $dependencyId,
            $activeId, $type, $subType, $taxSubject) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $activeId = $this->real_escape_string($activeId);
        $type = $this->real_escape_string($type);
        $subType = $this->real_escape_string($subType);
        $taxSubject = $this->real_escape_string($taxSubject);

        $where = "";

        if ($dependencyId != -1) {
            $where = " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($activeId != -1) {
            $where = " AND id = '" . $activeId . "' ";
        }
        if ($type != -1) {
            $where = " AND activetype = '" . $type . "' ";
        }
        if ($subType != -1) {
            $where = " AND activesubtype = '" . $subType . "' ";
        }
        if ($taxSubject != "") {
            $where = " AND taxsubject = '" . $taxSubject . "' ";
        }



        $sql = "SELECT *, \n"
                . "(SELECT S.companyname FROM supplier AS S WHERE S.id = S.sourceid LIMIT 1) AS supplierName, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.id = P.statussourceid LIMIT 1) AS operatorName \n"
                . "FROM patrimony AS P \n"
                . "WHERE companyid = '" . $companyId . "'  " . $where
                . "ORDER BY activetype, activesubtype, active ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function patrimonyActiveSave($arrayActive) {

        $companyid = $this->real_escape_string($arrayActive['companyid']);
        $dependencyid = $this->real_escape_string($arrayActive['dependencyid']);
        $activeId = $this->real_escape_string($arrayActive['activeId']);
        $active = $this->real_escape_string($arrayActive['active']);
        $details = $this->real_escape_string($arrayActive['details']);
        $activetype = $this->real_escape_string($arrayActive['activetype']);
        $activesubtype = $this->real_escape_string($arrayActive['activesubtype']);
        $quantity = $this->real_escape_string($arrayActive['quantity']);
        $location = $this->real_escape_string($arrayActive['location']);
        $taxsubject = $this->real_escape_string($arrayActive['taxsubject']);
        $taxestimated = $this->real_escape_string($arrayActive['taxestimated']);
        $aquisitionyear = $this->real_escape_string($arrayActive['aquisitionyear']);
        $initialstatus = $this->real_escape_string($arrayActive['initialstatus']);
        $investment = $this->real_escape_string($arrayActive['investment']);
        $supplierid = $this->real_escape_string($arrayActive['supplierid']);
        $currentstatus = $this->real_escape_string($arrayActive['currentstatus']);
        $activecode = $this->real_escape_string($arrayActive['activecode']);
        $sourceid = $this->real_escape_string($arrayActive['sourceid']);

        $sql = "";
        if (!is_numeric($activeId)) {
            $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(40);
            $sql = "INSERT INTO patrimony ("
                    . "companyid, dependencyid, active, "
                    . "sourceid, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $active . "', "
                    . "'" . $sourceid . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $activeId = " (SELECT id FROM patrimony WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $activeId = "'" . $activeId . "'";
        }

        $sql .= "UPDATE patrimony SET \n"
                . "active = '" . $active . "', "
                . "details = '" . $details . "', "
                . "activetype = '" . $activetype . "', "
                . "activesubtype = '" . $activesubtype . "', \n"
                . "quantity = '" . $quantity . "', "
                . "location = '" . $location . "', "
                . "taxsubject = '" . $taxsubject . "', "
                . "taxestimated = '" . $taxestimated . "', "
                . "aquisitionyear = '" . $aquisitionyear . "', "
                . "initialstatus = '" . $initialstatus . "', "
                . "investment = '" . $investment . "', "
                . "supplierid = '" . $supplierid . "', "
                . "currentstatus = '" . $currentstatus . "', "
                . "activecode = '" . $activecode . "', "
                . "statusdate = NOW(), "
                . "statussourceid = '" . $sourceid . "'  \n"
                . "WHERE id = " . $activeId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM patrimony \n"
                . "WHERE id = " . $activeId . "; \n";


        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        $activeId = $row['id'];
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!is_numeric($activeId)) {
            return json_encode(array("status" => '0', "msg" => 'Não foi actualizar este activo do património.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Activo do património actualizado com sucesso.'));
    }

    public function patrimonyActiveDelete($activeId) {

        $activeId = $this->real_escape_string($activeId);

        $sql = "DELETE FROM patrimony \n"
                . "WHERE id = '" . $activeId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM patrimony \n"
                . "WHERE id = '" . $activeId . "'; \n";

        $deleted = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $deleted = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!$deleted) {
            return json_encode(array("status" => '0', "msg" => 'Não foi eliminar este activo do património.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Activo do património eliminado com sucesso.'));
    }

    public function treasuryRubricListGet($companyId, $dependencyId,
            $costType) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $costType = $this->real_escape_string($costType);

        $where = "";
        if ($costType != -1) {
            $where .= " AND TR.costtype = '" . $costType . "' ";
        }


        $sql = "SELECT *, TR.id AS rubricid, \n"
                . "(COALESCE((SELECT TRP.percent FROM treasuryrubricpercent AS TRP WHERE TRP.rubricid = TR.id AND TRP.companyid = TR.companyid AND TRP.dependencyid = '" . $dependencyId . "' LIMIT 1), 0)) AS percent \n"
                . "FROM treasuryrubric AS TR \n"
                . "WHERE companyid = '" . $companyId . "'  " . $where
                . "ORDER BY percent DESC, description; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function treasuryRubricSaveSingle($companyId, $rubricId, $description, $costType) {

        $companyId = $this->real_escape_string($companyId);
        $rubricId = $this->real_escape_string($rubricId);
        $description = $this->real_escape_string($description);
        $costType = $this->real_escape_string($costType);

        $sql = "";
        if (!is_numeric($rubricId)) {
            $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(40);
            $sql = "INSERT INTO treasuryrubric ("
                    . "companyid, description, registernumber) \n"
                    . "SELECT '" . $companyId . "', '" . $description . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $rubricId = " (SELECT id FROM treasuryrubric WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $rubricId = "'" . $rubricId . "'";
        }

        $sql .= "UPDATE treasuryrubric SET \n"
                . "description = '" . $description . "', "
                . "costtype = '" . $costType . "'   \n"
                . "WHERE id = " . $rubricId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM treasuryrubric \n"
                . "WHERE id = " . $rubricId . "; \n";


        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        $rubricId = $row['id'];
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!is_numeric($rubricId)) {
            return json_encode(array("status" => '0', "msg" => 'Não foi actualizar esta rúbrica.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Rúbrica actualizada com sucesso.'));
    }

    public function treasuryRubricDeleteSingle($rubricId) {

        $rubricId = $this->real_escape_string($rubricId);

        $sql = "DELETE FROM treasuryrubric    \n"
                . "WHERE id = '" . $rubricId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM treasuryrubric \n"
                . "WHERE id = '" . $rubricId . "'; \n";

        $deleted = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $deleted = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!$deleted) {
            return json_encode(array("status" => '0', "msg" => 'Não foi eliminar esta rúbrica.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Rúbrica eliminada com sucesso.'));
    }

    public function treasuryRubricSave($dependencyId, $arrayRubric) {

        $dependencyId = $this->real_escape_string($dependencyId);

        $sql = "";
        foreach ($arrayRubric as $key => $trub) {
            $companyid = $this->real_escape_string($trub['companyid']);
            $rubricid = $this->real_escape_string($trub['rubricid']);
            $percent = $this->real_escape_string($trub['percent']);

            $sql .= "UPDATE treasuryrubricpercent SET \n"
                    . "percent = '" . $percent . "' \n"
                    . "WHERE companyid = '" . $companyid . "' AND dependencyid = '" . $dependencyId . "' AND rubricid = '" . $rubricid . "'; \n";
            $sql .= "INSERT INTO treasuryrubricpercent (companyid, dependencyid, rubricid, percent) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyId . "', '" . $rubricid . "', '" . $percent . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM treasuryrubricpercent WHERE companyid = '" . $companyid . "' AND dependencyid = '" . $dependencyId . "' AND rubricid = '" . $rubricid . "'); \n";
        }

        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        return json_encode(array("status" => '1', "msg" => 'Rúbrica actualizada com sucesso.'));
    }

    public function treasuryBillToPayListGet($companyId, $dependencyId,
            $billId, $initialDate, $endDate, $supplierId) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $billId = $this->real_escape_string($billId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);
        $supplierId = $this->real_escape_string($supplierId);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($billId != -1) {
            $where .= " AND id = '" . $billId . "' ";
        }
        if ($initialDate != -1) {
            $where .= " AND (CAST(limitdate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) ";
        }
        if ($supplierId != -1) {
            $where .= " AND supplierid = '" . $supplierId . "' ";
        }
        $debt = " (amount - paymentamount) ";

        $sql = "SELECT *,  \n"
                . $debt . " AS debt, \n"
                . "(CASE WHEN " . $debt . " > 0 THEN 'N' ELSE 'P' END) AS status, \n"
                . "(DATEDIFF(TB.limitdate, NOW())) AS delayDays, \n"
                . "(SELECT S.companyname FROM supplier AS S WHERE S.id = TB.supplierid LIMIT 1) AS supplierName, \n"
                . "(SELECT  U.userfullname FROM systemuser AS U WHERE U.userid = TB.sourceidstatus LIMIT 1) AS operatorName \n"
                . "FROM treasurybilltopay AS TB \n"
                . "WHERE companyid = '" . $companyId . "'  " . $where
                . "ORDER BY status, delayDays, debt DESC; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function treasuryBillToPaySave($arrayBill) {

        $billId = $this->real_escape_string($arrayBill['billId']);
        $companyid = $this->real_escape_string($arrayBill['companyid']);
        $dependencyid = $this->real_escape_string($arrayBill['dependencyid']);
        $supplierid = $this->real_escape_string($arrayBill['supplierid']);
        $invoicenumber = $this->real_escape_string($arrayBill['invoicenumber']);
        $amount = $this->real_escape_string($arrayBill['amount']);
        $invoicedate = $this->real_escape_string($arrayBill['invoicedate']);
        $limitdate = $this->real_escape_string($arrayBill['limitdate']);
        $rubricid = $this->real_escape_string($arrayBill['rubricid']);
        $description = $this->real_escape_string($arrayBill['description']);
        $sourceid = $this->real_escape_string($arrayBill['sourceid']);

        $sql = "";
        if (!is_numeric($billId)) {
            $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(40);
            $sql = "INSERT INTO treasurybilltopay ("
                    . "companyid, dependencyid, "
                    . "sourceid, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $sourceid . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $billId = " (SELECT id FROM treasurybilltopay WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $billId = "'" . $billId . "'";
        }

        $sql .= "UPDATE treasurybilltopay SET \n"
                . "supplierid = '" . $supplierid . "', "
                . "invoicenumber = '" . $invoicenumber . "', "
                . "amount = '" . $amount . "', "
                . "invoicedate = '" . $invoicedate . "', \n"
                . "limitdate = '" . $limitdate . "', "
                . "rubricid = '" . $rubricid . "', "
                . "description = '" . $description . "',  "
                . "statusdate = NOW(), "
                . "sourceidstatus = '" . $sourceid . "'  \n"
                . "WHERE id = " . $billId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM treasurybilltopay \n"
                . "WHERE id = " . $billId . "; \n";


        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        $billId = $row['id'];
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!is_numeric($billId)) {
            return json_encode(array("status" => '0', "msg" => 'Não foi actualizar esta conta a pagar.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Conta a pagar actualizada com sucesso.'));
    }

    public function treasuryBillToPayDelete($billId) {

        $billId = $this->real_escape_string($billId);

        $sql = "DELETE FROM treasurybilltopay \n"
                . "WHERE id = '" . $billId . "'; ";

        $sql .= "SELECT id \n"
                . "FROM treasurybilltopay \n"
                . "WHERE id = '" . $billId . "'; \n";

        $deleted = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $deleted = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }


        if (!$deleted) {
            return json_encode(array("status" => '0', "msg" => 'Não foi eliminar esta conta a pagar.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Conta a pagar eliminada com sucesso.'));
    }

    public function treasuryCashOutSave($arrayCashOutInfo, $arrayCashOutLines) {

        $cashoutId = $this->real_escape_string($arrayCashOutInfo['cashoutId']);
        $companyid = $this->real_escape_string($arrayCashOutInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayCashOutInfo['dependencyid']);
        $carrier = $this->real_escape_string($arrayCashOutInfo['carrier']);
        $cashoutdate = $this->real_escape_string($arrayCashOutInfo['cashoutdate']);
        $amount = $this->real_escape_string($arrayCashOutInfo['amount']);
        $paymentmechanism = $this->real_escape_string($arrayCashOutInfo['paymentmechanism']);
        $bankaccountid = $this->real_escape_string($arrayCashOutInfo['bankaccountid']);
        $description = $this->real_escape_string($arrayCashOutInfo['description']);
        $sourceid = $this->real_escape_string($arrayCashOutInfo['sourceid']);

        $sql = "";
        if (!is_numeric($cashoutId)) {
            $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(40);
            $sql .= "INSERT INTO treasurycashout ("
                    . "companyid, dependencyid, sourceid, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $sourceid . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $cashoutId = " (SELECT id FROM treasurycashout WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $cashoutId = "'" . $cashoutId . "'";
        }

        $sql .= "UPDATE treasurycashout SET \n"
                . "carrier = '" . $carrier . "', "
                . "cashoutdate = '" . $cashoutdate . "', "
                . "amount = '" . $amount . "', "
                . "paymentmechanism = '" . $paymentmechanism . "', "
                . "bankaccountid = '" . $bankaccountid . "', "
                . "description = '" . $description . "', "
                . "statusdate = NOW(), "
                . "sourceidstatus = '" . $sourceid . "' \n"
                . "WHERE id = " . $cashoutId . "; \n";

        foreach ($arrayCashOutLines as $key => $csoLine) {
            $id = $this->real_escape_string($csoLine['id']);
            $rubricid = $this->real_escape_string($csoLine['rubricid']);
            $supplierid = $this->real_escape_string($csoLine['supplierid']);
            $invoicenumber = $this->real_escape_string($csoLine['invoicenumber']);
            $invoicedate = $this->real_escape_string($csoLine['invoicedate']);
            $invoiceamount = $this->real_escape_string($csoLine['invoiceamount']);
            $taxbase = $this->real_escape_string($csoLine['taxbase']);
            $taxpayable = $this->real_escape_string($csoLine['taxpayable']);
            $deductibleamount = $this->real_escape_string($csoLine['deductibleamount']);
            $status = $this->real_escape_string($csoLine['status']);

            if ($status == 0) {
                $sql .= "DELETE FROM treasurycashoutdetail WHERE id = '" . $id . "'; \n";
            } else {
                $sql .= "UPDATE treasurycashoutdetail SET \n"
                        . "rubricid = '" . $rubricid . "', "
                        . "supplierid = '" . $supplierid . "', "
                        . "invoicenumber = '" . $invoicenumber . "', "
                        . "invoicedate = '" . $invoicedate . "', "
                        . "invoiceamount = '" . $invoiceamount . "', "
                        . "taxbase = '" . $taxbase . "', "
                        . "taxpayable = '" . $taxpayable . "', "
                        . "deductibleamount = '" . $deductibleamount . "' \n"
                        . "WHERE id = '" . $id . "'; \n";
                $sql .= "INSERT INTO treasurycashoutdetail ("
                        . "companyid, cashoutid, rubricid, "
                        . "supplierid, invoicenumber, invoicedate, invoiceamount, "
                        . "taxbase, taxpayable, deductibleamount) \n"
                        . "SELECT '" . $companyid . "', " . $cashoutId . ", '" . $rubricid . "', "
                        . "'" . $supplierid . "', '" . $invoicenumber . "', '" . $invoicedate . "', '" . $invoiceamount . "', "
                        . "'" . $taxbase . "', '" . $taxpayable . "', '" . $deductibleamount . "' \n"
                        . "FROM insert_table  \n"
                        . "WHERE NOT EXISTS (SELECT id FROM treasurycashoutdetail WHERE id = '" . $id . "'); \n";
            }
        }

        $sql .= "SELECT id \n"
                . "FROM treasurycashout \n"
                . "WHERE id = " . $cashoutId . "; \n";

        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $row = $this->converteArrayParaUtf8($row);
                        $cashoutId = $row['id'];
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!is_numeric($cashoutId)) {
            return json_encode(array("status" => '0', "msg" => 'Não foi actualizar esta saída de caixa.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Saída de caixa actualizada com sucesso.'));
    }

    public function treasuryCashOutDelete($cashoutId) {

        $cashoutId = $this->real_escape_string($cashoutId);

        $sql = "DELETE FROM treasurycashout \n"
                . "WHERE id = '" . $cashoutId . "'; \n";

        $sql .= "DELETE FROM treasurycashoutdetail \n"
                . "WHERE cashoutid = '" . $cashoutId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM treasurycashout \n"
                . "WHERE id = '" . $cashoutId . "'; \n";
        $deleted = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $deleted = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$deleted) {
            return json_encode(array("status" => '0', "msg" => 'Não foi eliminar esta saída de caixa.'));
        }

        return json_encode(array("status" => '1', "msg" => 'Saída de caixa eliminada com sucesso.'));
    }

    public function treasuryCashOutListGet($companyId, $dependencyId,
            $cashoutId, $initialDate, $endDate) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $cashoutId = $this->real_escape_string($cashoutId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        if ($cashoutId != -1) {
            $where .= " AND id = '" . $cashoutId . "' ";
        }
        if ($initialDate != -1) {
            $where .= " AND (CAST(cashoutdate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) ";
        }
        $initial = "(SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = (SELECT BC.nationalbankid FROM bankaccount AS BC WHERE BC.id = TCO.bankaccountid LIMIT 1) LIMIT 1) ";

        $sql = "SELECT *,  \n"
                . "(SELECT PM.mechanism FROM paymentmechanism AS PM WHERE PM.id = TCO.paymentmechanism LIMIT 1) AS mechanism, \n"
                . "(CONCAT (" . $initial . ", ' - ', (SELECT BA.accountnumber FROM bankaccount AS BA WHERE BA.id = TCO.bankaccountid LIMIT 1))) AS bankaccount, \n"
                . "(SELECT  U.userfullname FROM systemuser AS U WHERE U.userid = TCO.sourceidstatus LIMIT 1) AS operatorName \n"
                . "FROM treasurycashout AS TCO \n"
                . "WHERE companyid = '" . $companyId . "'  " . $where
                . "ORDER BY cashoutdate; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function treasuryCashOutLinesGet($cashoutId) {
        $cashoutId = $this->real_escape_string($cashoutId);

        $sql = "SELECT * \n"
                . "FROM treasurycashoutdetail AS TCOD \n"
                . "WHERE cashoutid = '" . $cashoutId . "'  "
                . "ORDER BY invoicedate; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentListGet($initialDate, $endDate) {

        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);


        $where = " AND (CAST(U.entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n";

        $sql = "SELECT U.userid, U.userfullname, U.email, A.taxid, A.iban, A.phone, U.entrydate, \n"
                . "(TIMESTAMPDIFF(YEAR, A.birthdate, NOW())) AS age, \n"
                . "(SELECT MAX(SL.sessiontime) FROM systemuserloged AS SL WHERE SL.userid = U.id) AS lastActivity \n"
                . "FROM systemuser AS U, agent AS A \n"
                . "WHERE U.id = A.userid AND U.companyid = '5000' AND U.openuser = 1  " . $where
                . "ORDER BY  U.userfullname; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentReportCommissionReport($paymentStatus, $initialDate, $endDate) {

        $paymentStatus = $this->real_escape_string($paymentStatus);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $where = "";
        if ($paymentStatus != -1) {
            if ($paymentStatus == 1) {
                $where = " AND IL.agentpaymentid > 0 ";
            } else {
                $where = " AND IL.agentpaymentid = 0 ";
            }
        }

        function comissionOnGmeReport($field, $userType) {
            return "SUM((CASE WHEN I." . $userType . " = U.id THEN (((IL.warehousePrice * (1 - (IL.settlementAmount/100))) * IL." . $field . "/100)*IL.quantity) ELSE 0 END)) \n";
        }

        $commission = "SELECT (" . comissionOnGmeReport('sellerComission', 'sellerUser') . " + "
                . comissionOnGmeReport('managerComission', 'managerUser') . ")    \n"
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.invoicenumber = IL.invoiceNumber AND UPPER(I.invoicestatus)!= 'A' AND UPPER(IL.status)= 'N' AND IL.note = 0 "//AND (CAST(IL.devolution AS DATE)) < (CAST(NOW() AS DATE))  \n"
                . "AND (I.sellerUser = U.id OR I.managerUser = U.id) \n"
                . "AND (CAST(IL.devolution AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "AND IL.warehousePrice > 0 " . $where
                . "LIMIT 1";

        $sql = "SELECT U.id, U.userfullname, \n"
                . "(SELECT A.iban FROM agent AS A WHERE A.userid = U.id LIMIT 1) AS iban, \n"
                . "(COALESCE((" . $commission . "), 0)) AS commission \n"
                . "FROM systemuser AS U \n"
                . "WHERE U.companyid = 5000 AND U.openuser = 1 \n"
                . "ORDER BY iban, U.userfullname; ";

        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentReportCommissionNewPayment($companyId, $userId, $initialDate, $endDate) {
        $companyId = $this->real_escape_string($companyId);
        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        function comissionNew($field, $userType, $userId) {
            return ", SUM((CASE WHEN I." . $userType . " = '" . $userId . "' THEN (((IL.warehousePrice * (1 - (IL.settlementAmount/100))) * IL." . $field . "/100)*IL.quantity) ELSE 0 END)) AS " . $field . " \n";
        }

        $sql = "SELECT I.invoicenumber, I.invoicedate, IL.id, IL.productDescription    "
                . comissionNew('sellerComission', 'sellerUser', $userId)
                . comissionNew('managerComission', 'managerUser', $userId)
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.invoicenumber = IL.invoiceNumber AND UPPER(I.invoicestatus)!= 'A' AND UPPER(IL.status)= 'N' AND IL.note = 0 "//AND (CAST(IL.devolution AS DATE)) < (CAST(NOW() AS DATE))  \n"
                . "AND (I.sellerUser = '" . $userId . "' OR I.managerUser = '" . $userId . "') \n"
                . "AND (CAST(IL.devolution AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) "
                . "AND IL.warehousePrice > 0 AND IL.agentpaymentid = 0 \n"
                . "GROUP BY I.invoicenumber, I.invoicedate, IL.id, IL.productDescription; ";

        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentReportCommissionSavePayment($arrayPaymentInfo, $productsInProcess) {

        $companyid = $this->real_escape_string($arrayPaymentInfo['companyid']);
        $userid = $this->real_escape_string($arrayPaymentInfo['userid']);
        $agentname = $this->real_escape_string($arrayPaymentInfo['agentname']);
        $startdate = $this->real_escape_string($arrayPaymentInfo['startdate']);
        $enddate = $this->real_escape_string($arrayPaymentInfo['enddate']);
        $amountestimated = $this->real_escape_string($arrayPaymentInfo['amountestimated']);
        $withholdingtaxamount = $this->real_escape_string($arrayPaymentInfo['withholdingtaxamount']);
        $amounteprocessed = $this->real_escape_string($arrayPaymentInfo['amounteprocessed']);
        $accountbank = $this->real_escape_string($arrayPaymentInfo['accountbank']);
        $processeddate = $this->real_escape_string($arrayPaymentInfo['processeddate']);
        $accountbanksource = $this->real_escape_string($arrayPaymentInfo['accountbanksource']);
        $sourceid = $this->real_escape_string($arrayPaymentInfo['sourceid']);
        $registernumber = $this->getRandomString(5) . round(microtime(true) * 1000);

        //Process payment
        $sql = "INSERT INTO agentpayment (\n"
                . "companyid, userid, startdate, enddate, "
                . "amountestimated, withholdingtaxamount, amounteprocessed, "
                . "accountbank, processeddate, accountbanksource, sourceid, "
                . "sourceidstatus, registernumber) \n"
                . "SELECT '" . $companyid . "', '" . $userid . "', '" . $startdate . "', '" . $enddate . "', "
                . "'" . $amountestimated . "', '" . $withholdingtaxamount . "', '" . $amounteprocessed . "', "
                . "'" . $accountbank . "', '" . $processeddate . "', '" . $accountbanksource . "', '" . $sourceid . "', "
                . "'" . $sourceid . "', '" . $registernumber . "' \n"
                . "FROM insert_table; \n";
        $agentpaymentid = "(SELECT id FROM agentpayment WHERE registernumber = '" . $registernumber . "' LIMIT 1)";
        //Register CashOut Flow
        $sql .= "INSERT INTO treasurycashout (\n"
                . "companyid, dependencyid, carrier, cashoutdate, "
                . "amount, paymentmechanism, bankaccountid, description, "
                . "sourceid, sourceidstatus, agentpaymentid, registernumber) \n"
                . "SELECT '" . $companyid . "', '2', '" . $agentname . "', '" . $processeddate . "', "
                . "'" . $amounteprocessed . "', '4', '" . $accountbanksource . "', 'COMISSÃO DE AGENTE " . $startdate . " / " . $enddate . "', "
                . "'" . $sourceid . "', '" . $sourceid . "', " . $agentpaymentid . ", '" . $registernumber . "' \n"
                . "FROM insert_table; \n";
        //Update invoice line
        $sql .= "UPDATE invoiceline SET \n"
                . "agentpaymentid = " . $agentpaymentid . " \n"
                . "WHERE id IN (" . $productsInProcess . ");\n";

        $sql .= "SELECT id \n"
                . "FROM agentpayment  \n"
                . "WHERE registernumber = '" . $registernumber . "';";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível processar este pagamento."));
        }
        return json_encode(array("status" => 1, "msg" => "Pagamento processado com sucesso"));
    }

    public function gmeAgentPaymentProcessedList($userId, $initialDate, $endDate) {

        $userId = $this->real_escape_string($userId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        $where = "";
        if ($userId != -1) {
            $where = " AND AP.userid = '" . $userId . "' ";
        }


        $sql = "SELECT AP.id, AP.userid, AP.startdate, AP.enddate, AP.amountestimated, \n"
                . "AP.withholdingtaxamount, AP.amounteprocessed, AP.accountbank, AP.processeddate,"
                . "AP.accountbanksource, AP.paymentstatus, AP.sourceidstatus, AP.statusdate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.id = AP.userid LIMIT 1) AS agentName, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.id = AP.sourceidstatus LIMIT 1) AS opratorName \n"
                . "FROM agentpayment AS AP \n"
                . "WHERE AP.companyid = 5000 " . $where . " AND \n"
                . "(CAST(AP.processeddate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) "
                . "ORDER BY amounteprocessed, agentName; ";

        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentPaymentProcessedDetail($paymentAgentId, $userId) {
        $paymentAgentId = $this->real_escape_string($paymentAgentId);
        $userId = $this->real_escape_string($userId);

        function comissionProcessed($field, $userType, $userId) {
            return ", SUM((CASE WHEN I." . $userType . " = '" . $userId . "' THEN (((IL.warehousePrice * (1 - (IL.settlementAmount/100))) * IL." . $field . "/100)*IL.quantity) ELSE 0 END)) AS " . $field . " \n";
        }

        $sql = "SELECT I.invoicenumber, I.invoicedate, IL.id, IL.productDescription    "
                . comissionProcessed('sellerComission', 'sellerUser', $userId)
                . comissionProcessed('managerComission', 'managerUser', $userId)
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.invoicenumber = IL.invoiceNumber AND UPPER(I.invoicestatus)!= 'A' AND UPPER(IL.status)= 'N' AND IL.agentpaymentid = '" . $paymentAgentId . "'  \n"
                . "GROUP BY I.invoicenumber, I.invoicedate, IL.id, IL.productDescription; ";

        $result = $this->query($sql);


        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function gmeAgentReportCommissionCancelPayment($agentPaymentId, $sourceId) {

        $agentPaymentId = $this->real_escape_string($agentPaymentId);
        $sourceId = $this->real_escape_string($sourceId);

        //Cancel payment
        $sql = "UPDATE agentpayment SET \n"
                . "paymentstatus = 'A', "
                . "statusdate = NOW(), "
                . "sourceidstatus = '" . $sourceId . "' \n"
                . "WHERE id = '" . $agentPaymentId . "'; \n";
        //Delete CashOut Flow
        $sql .= "DELETE FROM treasurycashout \n"
                . "WHERE agentpaymentid = '" . $agentPaymentId . "'; \n";
        //Update invoice line
        $sql .= "UPDATE invoiceline SET \n"
                . "agentpaymentid = 0 \n"
                . "WHERE agentpaymentid = '" . $agentPaymentId . "';\n";

        $sql .= "SELECT paymentstatus \n"
                . "FROM agentpayment  \n"
                . "WHERE id = '" . $agentPaymentId . "';";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['paymentstatus'] == "A") {
                            $saved = true;
                        }
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível anular este pagamento."));
        }
        return json_encode(array("status" => 1, "msg" => "Pagamento anulado com sucesso"));
    }

    public function linkRequestSave($arrayLinkRequestInfo) {

        $requestid = $this->real_escape_string($arrayLinkRequestInfo['requestid']);
        $companyid = $this->real_escape_string($arrayLinkRequestInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayLinkRequestInfo['dependencyid']);
        $requestdate = $this->real_escape_string($arrayLinkRequestInfo['requestdate']);
        $requesttype = $this->real_escape_string($arrayLinkRequestInfo['requesttype']);
        $cil = $this->real_escape_string($arrayLinkRequestInfo['cil']);
        $requeststatus = $this->real_escape_string($arrayLinkRequestInfo['requeststatus']);

        $requestername = $this->real_escape_string($arrayLinkRequestInfo['requestername']);
        $requesterdocnumber = $this->real_escape_string($arrayLinkRequestInfo['requesterdocnumber']);

        $requesterdocemission = $this->real_escape_string($arrayLinkRequestInfo['requesterdocemission']);
        $requestermaritalstatus = $this->real_escape_string($arrayLinkRequestInfo['requestermaritalstatus']);
        $requesterbirthdate = $this->real_escape_string($arrayLinkRequestInfo['requesterbirthdate']);
        $requesterbirthprovince = $this->real_escape_string($arrayLinkRequestInfo['requesterbirthprovince']);
        $requesterbirthmunicipality = $this->real_escape_string($arrayLinkRequestInfo['requesterbirthmunicipality']);
        $requesterfather = $this->real_escape_string($arrayLinkRequestInfo['requesterfather']);
        $requestermother = $this->real_escape_string($arrayLinkRequestInfo['requestermother']);

        $billingprovince = $this->real_escape_string($arrayLinkRequestInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arrayLinkRequestInfo['billingmunicipality']);
        $billingcomuna = $this->real_escape_string($arrayLinkRequestInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayLinkRequestInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayLinkRequestInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayLinkRequestInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayLinkRequestInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arrayLinkRequestInfo['billingpostalcode']);
        $telephone1 = $this->real_escape_string($arrayLinkRequestInfo['telephone1']);
        $telephone2 = $this->real_escape_string($arrayLinkRequestInfo['telephone2']);
        $telephone3 = $this->real_escape_string($arrayLinkRequestInfo['telephone3']);
        $email = $this->real_escape_string($arrayLinkRequestInfo['email']);

        $statususer = $this->real_escape_string($arrayLinkRequestInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $sql = "";
        if (!is_numeric($requestid)) {
            $sql .= "INSERT INTO linkrequest (\n"
                    . "companyid, dependencyid, entryuser, "
                    . "requeststatus, statususer, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $statususer . "', "
                    . "'1', '" . $statususer . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $requestid = " (SELECT id FROM linkrequest WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $requestid = "'" . $requestid . "'";
        }

        $sql .= "UPDATE linkrequest SET \n"
                . "requestdate = '" . $requestdate . "', "
                . "requesttype = '" . $requesttype . "', "
                . "cil = '" . $cil . "', "
                . "requestername = '" . $requestername . "', "
                . "requesterdocnumber = '" . $requesterdocnumber . "', \n"
                . "requesterdocemission = '" . $requesterdocemission . "', "
                . "requestermaritalstatus = '" . $requestermaritalstatus . "', "
                . "requesterbirthdate = '" . $requesterbirthdate . "', "
                . "requesterbirthprovince = '" . $requesterbirthprovince . "', "
                . "requesterbirthmunicipality = '" . $requesterbirthmunicipality . "', "
                . "requesterfather = '" . $requesterfather . "', "
                . "requestermother = '" . $requestermother . "', \n"
                . "billingprovince = '" . $billingprovince . "', "
                . "billingmunicipality = '" . $billingmunicipality . "', "
                . "billingcomuna = '" . $billingcomuna . "', "
                . "billingneiborhood = '" . $billingneiborhood . "', "
                . "billingstreetname = '" . $billingstreetname . "', "
                . "billingblock = '" . $billingblock . "', "
                . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
                . "billingpostalcode = '" . $billingpostalcode . "', \n"
                . "telephone1 = '" . $telephone1 . "', "
                . "telephone2 = '" . $telephone2 . "', "
                . "telephone3 = '" . $telephone3 . "', "
                . "email = '" . $email . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $requestid . "; \n";

        $sql .= "SELECT id \n"
                . "FROM linkrequest \n"
                . "WHERE id = " . $requestid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a solicitação."));
        }
        return json_encode(array("status" => 1, "msg" => "Solicitação guardada com sucesso"));
    }

    public function linkRequestChangeStatus($requestId, $status, $userId) {

        $requestId = $this->real_escape_string($requestId);
        $status = $this->real_escape_string($status);
        $userId = $this->real_escape_string($userId);


        $sql = "UPDATE linkrequest SET \n"
                . "requeststatus = '" . $status . "', "
                . "statususer = '" . $userId . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = '" . $requestId . "'; \n";

        $sql .= "SELECT requeststatus \n"
                . "FROM linkrequest \n"
                . "WHERE id = '" . $requestId . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['requeststatus'] == $status) {
                            $saved = true;
                        }
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível alterar o estado da solicitação."));
        }
        return json_encode(array("status" => 1, "msg" => "Estado da solicitação actualizado com sucesso"));
    }

    public function linkRequestGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $requestType = $this->real_escape_string($arrayFilterInfo['requestType']);
        $requestStatus = $this->real_escape_string($arrayFilterInfo['requestStatus']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $requesterId = $this->real_escape_string($arrayFilterInfo['requesterId']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);


        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND LR.dependencyid = '" . $dependencyId . "' ";
        }
        if ($requestType != -1) {
            $where .= " AND LR.requesttype = '" . $requestType . "' ";
        }
        if ($requestStatus != -1) {
            $where .= " AND LR.requeststatus = '" . $requestStatus . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND LR.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND LR.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND LR.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND ( CAST(LR.requestdate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($requesterId != -1) {
            $where = " AND LR.id = '" . $requesterId . "' ";
        }

        $sql = "SELECT *, "
                . "(COALESCE((SELECT I.id FROM inspection AS I WHERE I.linkrequestid = LR.id LIMIT 1), 0)) AS inspection, \n"
                . "(COALESCE((SELECT C.customerid FROM customer AS C WHERE C.linkrequestid = LR.id LIMIT 1), 0)) AS customerid, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = LR.requesterbirthmunicipality LIMIT 1) AS birthMunicipality, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = LR.requesterbirthprovince LIMIT 1) AS birthProvince, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = LR.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = LR.billingmunicipality LIMIT 1) AS billingMunicipality \n"
                . "FROM linkrequest AS LR \n"
                . "WHERE LR.companyid = '" . $companyId . "' " . $where
                . "; "; //ORDER BY  requeststatus, requestername, requestdate

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function linkRequestGetDetails($requestId) {

        $requestId = $this->real_escape_string($requestId);


        $sql = "SELECT *, "
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = LR.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = LR.statususer LIMIT 1) AS operatorName \n"
                . "FROM linkrequest AS LR \n"
                . "WHERE LR.id = '" . $requestId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function linkRequestListSearch($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $requestType = $this->real_escape_string($arrayFilterInfo['requestType']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $requestStatus = $this->real_escape_string($arrayFilterInfo['requestStatus']);
        $searchLimit = $this->real_escape_string($arrayFilterInfo['searchLimit']);
        $searchTag = $this->real_escape_string($arrayFilterInfo['searchTag']);

        $where = "";
        if ($dependencyId != -1) {
            $where = " AND dependencyid = " . $dependencyId . " ";
        }
        if ($requestType != -1) {
            $where = " AND requesttype = " . $requestType . " ";
        }
        if ($neiborhood != "") {
            $where = " AND billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($requestStatus != -1) {
            $where = " AND requeststatus = '" . $requestStatus . "' ";
        }
        if ($searchTag != "") {
            $where .= " AND (CAST(id AS CHAR) = '" . $searchTag . "' OR "
                    . "requestername LIKE '%" . $searchTag . "%' OR "
                    . "requesterdocnumber = '" . $searchTag . "') ";
        }

        $limit = is_numeric($searchLimit) ? $searchLimit : 5;

        $sql = "SELECT * \n"
                . "FROM linkrequest \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY requestername \n"
                . "LIMIT " . $limit . ";";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function inspectionSave($arrayInspectionInfo) {

        $inspectionId = $this->real_escape_string($arrayInspectionInfo['inspectionId']);
        $companyid = $this->real_escape_string($arrayInspectionInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayInspectionInfo['dependencyid']);
        $inspectiondate = $this->real_escape_string($arrayInspectionInfo['inspectiondate']);
        $linkrequestid = $this->real_escape_string($arrayInspectionInfo['linkrequestid']);
        $nextStatus = $this->real_escape_string($arrayInspectionInfo['nextStatus']);
        $statususer = $this->real_escape_string($arrayInspectionInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $sql = "";
        if (!is_numeric($inspectionId)) {
            $sql .= "INSERT INTO inspection (\n"
                    . "companyid, dependencyid, entryuser, "
                    . "linkrequestid, inspectionstatus, statususer, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $statususer . "', "
                    . "'" . $linkrequestid . "', '1', '" . $statususer . "', '" . $registerNumber . "' \n"
                    . "FROM insert_table; \n";
            $inspectionId = " (SELECT id FROM inspection WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $inspectionId = "'" . $inspectionId . "'";
        }
        $partSql = "";
        if ($nextStatus != -1) {
            $partSql = " inspectionstatus = '" . $nextStatus . "', ";
        }

        $sql .= "UPDATE inspection SET \n"
                . $partSql
                . "inspectiondate = '" . $inspectiondate . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $inspectionId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM inspection \n"
                . "WHERE id = " . $inspectionId . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a vistoria."));
        }
        return json_encode(array("status" => 1, "msg" => "Vistoria guardada com sucesso"));
    }

    public function inspectionGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $inspectionStatus = $this->real_escape_string($arrayFilterInfo['inspectionStatus']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " \n";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }
        if ($inspectionStatus != -1) {
            $where .= " AND I.inspectionstatus = '" . $inspectionStatus . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND LR.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND LR.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND I.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND ( CAST(I.inspectiondate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }

        $fields = "I.id, I.linkrequestid, I.inspectiondate, I.inspectionstatus, "
                . "LR.requestername, LR.billingneiborhood, LR.billingstreetname, LR.billingblock, "
                . "LR.billingbuildingnumber, LR.billingpostalcode, LR.telephone1, LR.telephone2, "
                . "I.statusdate, I.statususer, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = LR.requesterbirthmunicipality LIMIT 1) AS birthMunicipality, \n"
                . "(SELECT P.province FROM province AS P WHERE P.id = LR.requesterbirthprovince LIMIT 1) AS birthProvince, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = LR.billingmunicipality LIMIT 1) AS billingMunicipality \n";
        if ($onlynumber == 1) {
            $fields = " COUNT(I.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " "
                . "FROM inspection AS I, linkrequest AS LR \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.linkrequestid = LR.id " . $where
                . $limit
                . "; "; //ORDER BY  requeststatus, requestername, requestdate

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function inspectionGetDetails($inspectionId) {

        $inspectionId = $this->real_escape_string($inspectionId);

        $sql = "SELECT I.id, I.linkrequestid, I.inspectiondate, I.inspectionstatus, I.statusdate, I.statususer, \n"
                . "I.entryuser, I.entrydate, LR.requestername, LR.billingmunicipality, LR.billingcomuna, "
                . "LR.billingneiborhood, LR.billingstreetname, LR.billingblock, LR.billingbuildingnumber, "
                . "LR.billingpostalcode, LR.telephone1, LR.telephone2, LR.telephone3, LR.email, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.statususer LIMIT 1) AS operatorName \n"
                . "FROM inspection AS I, linkrequest AS LR \n"
                . "WHERE I.id = '" . $inspectionId . "' AND I.linkrequestid = LR.id ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function inspectionCheckListGet() {


        $sql = "SELECT * "
                . "FROM inspectionchecklist \n"
                . "WHERE 1 \n"
                . "; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function contractSave($arrayContractInfo) {

        $companyid = $this->real_escape_string($arrayContractInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayContractInfo['dependencyid']);
        $serie = $this->real_escape_string($arrayContractInfo['serie']);
        $contractid = $this->real_escape_string($arrayContractInfo['contractid']);
        $oldcontractnumber = $this->real_escape_string($arrayContractInfo['oldcontractnumber']);
        $customerid = $this->real_escape_string($arrayContractInfo['customerid']);
        $cilserie = $this->real_escape_string($arrayContractInfo['cilserie']);
        $cil = $this->real_escape_string($arrayContractInfo['cil']);
        $contractdate = $this->real_escape_string($arrayContractInfo['contractdate']);
        $consumertype = $this->real_escape_string($arrayContractInfo['consumertype']);
        $contractstatus = $this->real_escape_string($arrayContractInfo['contractstatus']);
        $contractsituation = $this->real_escape_string($arrayContractInfo['contractsituation']);

        $billingprovince = $this->real_escape_string($arrayContractInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arrayContractInfo['billingmunicipality']);
        $billingcomuna = $this->real_escape_string($arrayContractInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayContractInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayContractInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayContractInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayContractInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arrayContractInfo['billingpostalcode']);

        $nextStatus = $this->real_escape_string($arrayContractInfo['nextStatus']);
        $statususer = $this->real_escape_string($arrayContractInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $sql = "";
        if ($contractid == "NOVO*") {
            $result = $this->set_customerID($serie, "contract");
            $sequence = $result['sequence'];
            $contractid = $result['customerId'];


            $sql .= "INSERT INTO contract (\n"
                    . "companyid, dependencyid, serie, sequencenumber, contractid, \n"
                    . "customerid, contractstatus, entryuser, statususer, \n"
                    . "waterlinkstatus, waterlinkstatususer, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $serie . "', '" . $sequence . "', '" . $contractid . "', \n"
                    . "'" . $customerid . "', '1', '" . $statususer . "', '" . $statususer . "', "
                    . "'0', '" . $statususer . "', '" . $registerNumber . "'  \n"
                    . "FROM insert_table; ";
            $contractid = "(SELECT contractid FROM contract WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $contractid = "'" . $contractid . "'";
            $partSQl = "";
            if (($nextStatus == 2) || ($contractstatus == 2)) {
                if ($cil == "") {
                    $resultCil = $this->set_customerID($cilserie, "contract", "cil");
                    $sequenceCIl = $resultCil['sequence'];
                    $cil = $resultCil['customerId'];
                    $partSQl = "cilserie = '" . $cilserie . "', "
                            . "cilsequencenumber = '" . $sequenceCIl . "', "
                            . "cil = '" . $cil . "', \n";
                }
            }
            if (($nextStatus == 1) && ($contractstatus == 2)) {
                $nextStatus = 2;
            }
            $sql .= "UPDATE contract SET \n"
                    . $partSQl
                    . "contractstatus = '" . $nextStatus . "' \n"
                    . "WHERE contractid = " . $contractid . "; \n";
        }

        $sql .= "UPDATE contract SET \n"
                . "oldcontractnumber = '" . $oldcontractnumber . "', "
                . "contractdate = '" . $contractdate . "', "
                . "consumertype = '" . $consumertype . "', "
                . "contractsituation = '" . $contractsituation . "', "
                . "billingprovince = '" . $billingprovince . "', "
                . "billingmunicipality = '" . $billingmunicipality . "', "
                . "billingcomuna = '" . $billingcomuna . "', "
                . "billingneiborhood = '" . $billingneiborhood . "', "
                . "billingstreetname = '" . $billingstreetname . "', "
                . "billingblock = '" . $billingblock . "', "
                . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
                . "billingpostalcode = '" . $billingpostalcode . "', \n"
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE contractid = " . $contractid . "; \n";

        //Process invoice by approved contract
        if (($nextStatus == 2) && ($contractstatus != 2)) {
            $arrayContractInfo['cil'] = $cil;
            $sql .= $this->contractSaveProcessInvoice($arrayContractInfo);
        }

        $sql .= "SELECT id \n"
                . "FROM contract \n"
                . "WHERE contractid = " . $contractid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o contracto."));
        }
        return json_encode(array("status" => 1, "msg" => "Contracto guardado com sucesso"));
    }

    public function contractSaveLote($arrayContractInfo) {

        $companyid = $this->real_escape_string($arrayContractInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayContractInfo['dependencyid']);
        $serie = $this->real_escape_string($arrayContractInfo['serie']);
        $sequencenumber = $this->real_escape_string($arrayContractInfo['sequencenumber']);
        $contractid = $this->real_escape_string($arrayContractInfo['contractid']);
        $oldcontractnumber = $this->real_escape_string($arrayContractInfo['oldcontractnumber']);
        $customerid = $this->real_escape_string($arrayContractInfo['customerid']);
        $cilserie = $this->real_escape_string($arrayContractInfo['cilserie']);
        $cilsequencenumber = $this->real_escape_string($arrayContractInfo['cilsequencenumber']);
        $cil = $this->real_escape_string($arrayContractInfo['cil']);
        $contractdate = $this->real_escape_string($arrayContractInfo['contractdate']);
        $consumertype = $this->real_escape_string($arrayContractInfo['consumertype']);
        $contractstatus = $this->real_escape_string($arrayContractInfo['contractstatus']);
        $contractsituation = $this->real_escape_string($arrayContractInfo['contractsituation']);
        $lastinvoicedate = $this->real_escape_string($arrayContractInfo['lastinvoicedate']);

        $billingprovince = $this->real_escape_string($arrayContractInfo['billingprovince']);
        $billingmunicipality = $this->real_escape_string($arrayContractInfo['billingmunicipality']);
        $billingcomuna = $this->real_escape_string($arrayContractInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayContractInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayContractInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayContractInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayContractInfo['billingbuildingnumber']);
        $billingpostalcode = $this->real_escape_string($arrayContractInfo['billingpostalcode']);

        $statususer = $this->real_escape_string($arrayContractInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        //Check contract id
        $sql = "SELECT contractid \n"
                . "FROM contract \n"
                . "WHERE contractid = '" . $contractid . "'; ";
        $checkContract = $this->query($sql);
        if ($checkContract->num_rows > 0) {
            mysqli_free_result($checkContract);
            echo json_encode(array("status" => 0, "msg" => "O nº de contracto já foi cadastrado."));
            return false;
        }

        //Check customer id
        $sql = "SELECT customerid \n"
                . "FROM customer \n"
                . "WHERE customerid = '" . $customerid . "'; ";
        $checkResult = $this->query($sql);
        if ($checkResult->num_rows <= 0) {
            mysqli_free_result($checkResult);
            echo json_encode(array("status" => 0, "msg" => "O nº de cliente não existe."));
            return false;
        }

        $sql = "INSERT INTO contract (\n"
                . "companyid, dependencyid, serie, sequencenumber, contractid, \n"
                . "customerid, contractstatus, entryuser, statususer, \n"
                . "waterlinkstatus, waterlinkstatususer, registernumber, "
                . "billingmunicipality, billingcomuna, billingneiborhood, "
                . "billingstreetname, billingblock, billingbuildingnumber, billingpostalcode) \n"
                . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                . "'" . $serie . "', '" . $sequencenumber . "', '" . $contractid . "', \n"
                . "'" . $customerid . "', '" . $contractstatus . "', '" . $statususer . "', '" . $statususer . "', "
                . "'1', '" . $statususer . "', '" . $registerNumber . "',  \n"
                . "billingmunicipality, billingcomuna, billingneiborhood, "
                . "billingstreetname, billingblock, billingbuildingnumber, billingpostalcode \n"
                . "FROM customer \n"
                . "WHERE customerid = '" . $customerid . "' \n"
                . "LIMIT 1; \n";
        $contractid = "(SELECT contractid FROM contract WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";

        $sql .= "UPDATE contract SET \n"
                . "oldcontractnumber = '" . $oldcontractnumber . "', "
                . "contractdate = '" . $contractdate . "', "
                . "consumertype = '" . $consumertype . "', "
                . "contractsituation = '" . $contractsituation . "', "
                . "lastinvoicedate = '" . $lastinvoicedate . "', "
                . "cilserie = '" . $cilserie . "', "
                . "cilsequencenumber = '" . $cilsequencenumber . "', "
                . "cil = '" . $cil . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE contractid = " . $contractid . "; \n";
        /*
          . "billingprovince = '" . $billingprovince . "', "
          . "billingmunicipality = '" . $billingmunicipality . "', "
          . "billingcomuna = '" . $billingcomuna . "', "
          . "billingneiborhood = '" . $billingneiborhood . "', "
          . "billingstreetname = '" . $billingstreetname . "', "
          . "billingblock = '" . $billingblock . "', "
          . "billingbuildingnumber = '" . $billingbuildingnumber . "', "
          . "billingpostalcode = '" . $billingpostalcode . "', \n" */

        $sql .= "SELECT id \n"
                . "FROM contract \n"
                . "WHERE contractid = " . $contractid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o contracto."));
        }
        return json_encode(array("status" => 1, "msg" => "Contracto guardado com sucesso"));
    }

    public function contractSaveProcessInvoice($arrayContractInfo) {

        $companyId = $this->real_escape_string($arrayContractInfo['companyid']);
        $userId = $this->real_escape_string($arrayContractInfo['statususer']);
        $invoiceSerie = $this->real_escape_string($arrayContractInfo['invoiceSerie']);
        $taxGroup = $this->real_escape_string($arrayContractInfo['taxGroup']);
        $producttag = $this->real_escape_string($arrayContractInfo['producttag']);

        $customerid = $this->real_escape_string($arrayContractInfo['customerid']);
        $contractid = $this->real_escape_string($arrayContractInfo['contractid']);
        $cil = $this->real_escape_string($arrayContractInfo['cil']);

        //  $companyname = $this->real_escape_string($arrayContractInfo['companyname']);
        // $customertaxid = $this->real_escape_string($arrayContractInfo['customertaxid']);
//        $municipality = $this->real_escape_string($arrayContractInfo['municipality']);
        $billingcomuna = $this->real_escape_string($arrayContractInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayContractInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayContractInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayContractInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayContractInfo['billingbuildingnumber']);
        //   $telephone1 = $this->real_escape_string($arrayContractInfo['telephone1']);
        // $telephone2 = $this->real_escape_string($arrayContractInfo['telephone2']);
//        $telephone3 = $this->real_escape_string($arrayContractInfo['telephone3']);
        $address = $billingstreetname;
        if ($billingbuildingnumber != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= $billingbuildingnumber;
        }
        if ($address != "") {
            $address .= ", ";
        }
        $address .= "Bairro " . $billingneiborhood;
        if ($billingblock != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= $billingblock;
        }
        if ($billingcomuna != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= "Comuna " . $billingcomuna;
        }
        /*    $customerphone = $telephone1;
          if ($telephone2 != "") {
          if ($customerphone != "") {
          $customerphone .= ", ";
          }
          $customerphone .= $telephone2;
          }
          if ($telephone3 != "") {
          if ($customerphone != "") {
          $customerphone .= ", ";
          }
          $customerphone .= $telephone3;
          }

          $hydrometerid = $this->real_escape_string($arrayContractInfo['hydrometerid']);
          $consumertype = $this->real_escape_string($arrayContractInfo['consumertype']);
          $consumptionamount = $this->real_escape_string($arrayContractInfo['consumptionamount']);

          $hydrometerrecorddate = "";
          $hydrometerrecordvalue = "";
          $hydrometerbeforerecorddate = "";
          $hydrometerbeforerecordvalue = "";
          if ($hydrometerid != "") {
          $lastRecord = $this->saleConsumptionLastRecords($companyId, $contractid, $billingdate1);
          if ($lastRecord['recorddate'] != "") {
          $hydrometerbeforerecorddate = $lastRecord['beforedate'];
          $hydrometerbeforerecordvalue = $lastRecord['recordvalue'] + $lastRecord['lastconsumption'];
          $hydrometerrecorddate = $billingdate2;
          $hydrometerrecordvalue = $hydrometerbeforerecordvalue + $consumptionamount;
          }
          } */

        $productFull = json_decode($this->productGetFullDescription($producttag, $companyId), true);
        $iva = $productFull['iva'];
        if ($taxGroup <= 1) {
            $iva = 0;
        }
        $warehousePrice = $productFull['warehouseprice'];
        $ivaValue = $warehousePrice * ($iva / 100);
        $subtotalLine = $warehousePrice + $ivaValue;

        //invoice
        $invoiceType = "FT";
        $shelflifedays = $this->real_escape_string($arrayContractInfo['shelflife']);
        $shelflife = Date('Y-m-d', strtotime('+' . $shelflifedays . ' days'));

        $totalItems = 1;
        $subtotalUnitPrice = $warehousePrice;
        $subtotalIva = $ivaValue;
        $subtotalIvaWithoutDescount = number_format($subtotalIva, 2, '.', '');
        $totalInvoice = $subtotalUnitPrice + $subtotalIva;
        $grosstotal = number_format($totalInvoice, 2, '.', '');

        $sellerUser = $userId;
        $invoiceEntryDate = date_create(date("Y-m-d H:i:s"));
        $printnumber = $invoiceType . $this->getRandomString(10) . round(microtime(true) * 1000);

        $invoiceTable = "invoice";
        $invoiceLineTable = "invoiceline";

        $arrayHash = $this->invoiceSetHash($invoiceType, $invoiceSerie, $invoiceEntryDate, $grosstotal, $invoiceTable);
        $invoiceSequence = $arrayHash['invoiceSequence'];
        $invoiceNumber = $arrayHash['invoiceNumber'];
        $hash = $arrayHash['hash'];
        $hashControl = $arrayHash['hashControl'];
        $invoiceEntryDate = date_format($invoiceEntryDate, "Y-m-d\TH:i:s");

        $fieldsShipTo = ", 'Domicílio', '" . $invoiceEntryDate . "', CT.billingcountry, CT.billingprovince, CT.billingmunicipality, "
                . "CT.billingcity, CT.billingdistrict, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, CT.billingbuildingnumber, "
                . "CT.billingpostalcode \n";
        $fieldShipFrom = ", 'Loja', '" . $invoiceEntryDate . "', CY.billingcountry, CY.billingprovince, CY.billingmunicipality, "
                . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                . "CY.billingpostalcode \n";

        $sql = "INSERT INTO " . $invoiceTable . " (companyid, dependencyid, invoicetype, invoiceserie, "
                . "invoicesequence, invoicenumber, invoicestatus, invoiceStatusDate, Sourceidstatus, "
                . "Sourcebilling, hash, hashcontrol, invoicedate, sourceid, systementrydate, "
                . "customerid, contractid, grosstotal, printnumber, \n"
                . "deliveryIdShipto, deliverydateshipto, countryshipto, provinceshipto, municipalityshipto, "
                . "cityshipto, districtshipto, comunashipto, neiborhoodshipto, streetnameshipto, buildingnumbershipto, "
                . "postalcodeshipto,  \n"
                . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                . "postalCodeshipfrom, \n"
                . "customername, customertaxid, customercountry, customercity, "
                . "customerfulladdress, customerphone, customerpostalcode) \n"
                . "SELECT '" . $companyId . "', CT.dependencyid, '" . $invoiceType . "', '" . $invoiceSerie . "', \n"
                . "'" . $invoiceSequence . "', '" . $invoiceNumber . "', 'N', '" . $invoiceEntryDate . "', '" . $userId . "', \n"
                . "'P', '" . $hash . "', '" . $hashControl . "', '" . $invoiceEntryDate . "', '" . $userId . "', '" . $invoiceEntryDate . "', \n"
                . "'" . $customerid . "', '" . $contractid . "', '" . $grosstotal . "', '" . $printnumber . "' \n"
                . $fieldsShipTo . $fieldShipFrom
                . ", C.companyname, C.customertaxid, 1, (COALESCE((SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1), '')), "
                . "'" . $address . "', C.telephone1, CT.billingpostalcode  \n"
                . "FROM company AS CY , contract AS CT, customer AS C  \n"
                . "WHERE CY.companyid = '" . $companyId . "' AND CT.companyid = '" . $companyId . "' AND C.companyid = CT.companyid AND \n"
                . "CT.contractid = '" . $contractid . "' AND C.customerid = CT.customerid \n"
                . "LIMIT 1; \n";

        $sql .= "UPDATE " . $invoiceTable . " SET \n"
                . "cil = '" . $cil . "', "
                . "shelflife = '" . $shelflife . "', "
                . "totalItems = '" . $totalItems . "', "
                . "subtotalUnitPrice = '" . $subtotalUnitPrice . "', "
                . "subtotalIva = '" . $subtotalIva . "', "
                . "subtotalIvaWithoutDescount = '" . $subtotalIvaWithoutDescount . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "taxGroup = '" . $taxGroup . "', "
                . "sellerUser = '" . $sellerUser . "'  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        $sql .= "INSERT INTO customeraccount ("
                . "companyid, customerid, contractid, source, reason, amount) \n"
                . "SELECT '" . $companyId . "', '" . $customerid . "', '" . $contractid . "', \n"
                . "'" . $invoiceNumber . "', 'Factura', '-" . $totalInvoice . "' \n"
                . "FROM insert_table; \n";

        /*   LINES */
        $productID = $producttag;
        $productType = "S";
        $productDescription = $productFull['productdescription'];
        $productBarCode = "";
        $productUnit = "UN";
        $productSection = 1;
        $productIvaCategory = 18;
        $productStock = 0;
        $productWeight = 0;
        $productNetWeight = 0;
        $devolutionDate = Date('Y-m-d', strtotime('+5 days'));
        $productStockActual = 0;

        $periodicService = 0;
        $periodicServiceDateBefore = Date('Y-m-d');
        $periodicServiceDate = Date('Y-m-d');

        $quant = 1;
        $indirectCost = 0;
        $commercialCost = 0;
        $estimatedProfit = 0;
        $sellerComission = 0;
        $managerComission = 0;
        $fundInvestment = 0;
        $fundReserve = 0;
        $fundSocialaction = 0;

        $priceWithComission = $warehousePrice;
        $creditAmount = number_format($warehousePrice, 2, '.', '');
        $descount = 0;
        //$iva = $iva;
        //$ivaValue = $taxvariableIVA;
        $ivaWithoutDescountValue = $ivaValue;
        //   $subtotalLine = $taxvariableTotal + $taxvariableIVA;
        $exemptionCode = "";
        $exemptionReason = "";
        if ($iva == 0) {
            $exemptionCode = "M02";
            $exemptionReason = "Transmissão de bens e serviço não sujeita";
        }
        $gmelicense = 0;

        $strInsertLine = "INSERT INTO " . $invoiceLineTable . " (invoiceNumber, customerid, contractid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note, gmelicense) \n"
                . "SELECT '" . $invoiceNumber . "', '" . $customerid . "', '" . $contractid . "', '" . $productID . "', '" . $productDescription . "', '" . $quant . "', \n"
                . "'" . $productUnit . "', '" . $warehousePrice . "', '" . $indirectCost . "', '" . $commercialCost . "', '" . $estimatedProfit . "',  \n"
                . "'" . $invoiceEntryDate . "', '" . $productDescription . "', '" . $creditAmount . "', '" . $iva . "', \n"
                . "'" . $exemptionReason . "', '" . $exemptionCode . "', '" . $descount . "', \n"
                . "'" . $productID . "', '" . $productType . "', '" . $productBarCode . "', '" . $productSection . "', '" . $productIvaCategory . "', '" . $productStock . "', \n"
                . "'" . $productWeight . "', '" . $productNetWeight . "', '" . $devolutionDate . "', \n"
                . "'" . $periodicService . "', '" . $periodicServiceDateBefore . "', '" . $periodicServiceDate . "', \n"
                . "'" . $sellerComission . "', '" . $managerComission . "', \n"
                . "'" . $fundInvestment . "', '" . $fundReserve . "', '" . $fundSocialaction . "', \n"
                . "'" . $priceWithComission . "', '" . $ivaValue . "', '" . $ivaWithoutDescountValue . "', '" . $subtotalLine . "', '0', '" . $gmelicense . "'  \n"
                . "FROM insert_table \n";

        $strInsertLine .= "WHERE NOT EXISTS (SELECT id FROM " . $invoiceLineTable . " WHERE invoiceNumber = '" . $invoiceNumber . "' AND productId = '" . $productID . "')";

        $strInsertLine .= "; \n";

        $sql .= $strInsertLine;


        /* PAYMENTS SCHEDULE  */
        $installment = $invoiceNumber;
        $installmentDate = $shelflife;
        $paymentAmount = $totalInvoice;

        $sql .= "INSERT INTO invoicepaymentschedule ("
                . "companyid, invoicenumber, customerid, contractid, installment, installmentdate, "
                . "paymentamount, installmentstatus)  \n"
                . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', "
                . "'" . $customerid . "', '" . $contractid . "', '" . $installment . "', '" . $installmentDate . "', "
                . "'" . $paymentAmount . "', 'AP'  \n"
                . "FROM insert_table ; \n";

        // confirm operations
        $sql .= "SELECT * \n"
                . "FROM " . $invoiceTable . "  \n"
                . "WHERE printnumber = '" . $printnumber . "';";

        return $sql;
    }

    public function contractGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $contractStatus = $this->real_escape_string($arrayFilterInfo['contractStatus']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($contractStatus != -1) {
            $where .= " AND CT.contractstatus = '" . $contractStatus . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND CT.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  ( CAST(CT.contractdate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = "LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "CT.contractid, CT.cil, CT.contractdate, CT.consumertype, "
                . "C.companyname, C.customertaxid, CT.customerid, \n"
                . "CT.billingneiborhood,  CT.billingblock, C.telephone1, C.telephone2, C.telephone3, C.email, "
                . "CT.contractstatus, CT.statusdate, CT.statususer, \n"
                . "(SELECT hydrometerid FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1) AS hydrometerid, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality ";

        if ($onlynumber == 1) {
            $fields = " COUNT(CT.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.companyid = '" . $companyId . "'   " . $where
                . $limit
                . "; "; //ORDER BY  CT.contractstatus, C.companyname

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function contractGetDetails($contractId) {

        $contractId = $this->real_escape_string($contractId);


        $sql = "SELECT CT.serie, CT.contractid, CT.oldcontractnumber, CT.contractdate, \n"
                . "CT.consumertype, CT.contractstatus, CT.contractsituation, CT.cil, CT.customerid, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "CT.entryuser, CT.entrydate, CT.statususer, CT.statusdate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.statususer LIMIT 1) AS operatorName \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.contractid = '" . $contractId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function contractChangeStatus($contractId, $status, $userId) {

        $contractId = $this->real_escape_string($contractId);
        $status = $this->real_escape_string($status);
        $userId = $this->real_escape_string($userId);


        $sql = "UPDATE contract SET \n"
                . "contractstatus = '" . $status . "', "
                . "statususer = '" . $userId . "', "
                . "statusdate = NOW() \n"
                . "WHERE contractid = '" . $contractId . "'; \n";

        $sql .= "SELECT contractstatus \n"
                . "FROM contract \n"
                . "WHERE contractid = '" . $contractId . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['contractstatus'] == $status) {
                            $saved = true;
                        }
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível alterar o estado do contracto."));
        }
        return json_encode(array("status" => 1, "msg" => "Estado do contracto actualizado com sucesso"));
    }

    public function contractAutocompleteList($companyId, $dependencyId) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);

        $where = "";
        if ($dependencyId != -1) {
            $where = " AND dependencyid = '" . $dependencyId . "' ";
        }

        $sql = "SELECT DISTINCT contractid, cil  \n"
                . "FROM contract  \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . " \n"
                . "ORDER BY contractid ;";

        $result = $this->query($sql);

        $str = "";
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= '-/-' . $row['contractid'] . '-/-' . $row['cil'];
        }
        mysqli_free_result($result);

        $sql = "SELECT DISTINCT customerid, customertaxid, companyname  \n"
                . "FROM customer \n"
                . "WHERE companyid = '" . $companyId . "' " . $where . " \n"
                . "ORDER BY companyname ;";

        $result2 = $this->query($sql);

        while ($row = mysqli_fetch_array($result2)) {
            $row = $this->converteArrayParaUtf8($row);
            $str .= '-/-' . $row['customerid'] . '-/-' . $row['customertaxid'] . '-/-' . $row['companyname'];
        }
        mysqli_free_result($result2);


        return $str;
    }

    public function contractListSearch($companyId, $dependencyId,
            $consumerType, $neiborhood, $searchLimit, $searchTag, $forHydrometer, $field) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $consumerType = $this->real_escape_string($consumerType);
        $neiborhood = $this->real_escape_string($neiborhood);
        $searchLimit = $this->real_escape_string($searchLimit);
        $searchTag = $this->real_escape_string($searchTag);
        $forHydrometer = $this->real_escape_string($forHydrometer);
        $field = $this->real_escape_string($field);

        $where = "";
        if ($dependencyId != -1) {
            $where = " AND CT.dependencyid = " . $dependencyId . " ";
        }
        if ($consumerType != -1) {
            $where = " AND CT.consumertype = " . $consumerType . " ";
        }
        if ($neiborhood != "") {
            $where = " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($searchTag != "") {
            if ($field == 1) {
                $where .= " AND (CT.cil = '" . $searchTag . "') ";
            } elseif ($field == 2) {
                $where .= " AND (CT.contractid = '" . $searchTag . "') ";
            } elseif ($field == 3) {
                $where .= " AND (CT.customerid = '" . $searchTag . "') ";
            } else {
                $where .= " AND (C.companyname LIKE '%" . $searchTag . "%' OR "
                        . "C.customertaxid = '" . $searchTag . "') ";
            }
        }
        if ($forHydrometer == 1) {
            $where .= " AND ( NOT EXISTS (SELECT H.contractid FROM hydrometer AS H WHERE CT.contractid = H.contractid) ) ";
        }

        $limit = is_numeric($searchLimit) ? $searchLimit : 5;
        /* . "(COALESCE((SELECT CONCAT('Contador ', hydrometerid) FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1), 'Sem contador')) AS hydrometerid, \n"
          . "(COALESCE((SELECT SUM(CA.amount) FROM customeraccount AS CA WHERE CA.customerid = CT.customerid), 0)) AS creditaccount, \n"
         */
        $sql = "SELECT CT.dependencyid, CT.serie, CT.contractid, CT.oldcontractnumber, CT.contractdate, \n"
                . "CT.consumertype, CT.contractstatus, CT.cil, CT.customerid, CT.waterlinkstatus, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "C.email, C.telephone1, C.telephone2, C.telephone3, \n"
                . "CT.entryuser, CT.entrydate, CT.statususer, CT.statusdate, \n"
                . "(COALESCE((SELECT CONCAT('Contador ', hydrometerid) FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1), 'Sem contador')) AS hydrometerid, \n"
                . "0 AS creditaccount, \n"
                . "(SELECT MAX(I.billingenddate) FROM invoice AS I WHERE I.contractid = CT.contractid AND I.consumptioninvoice = 1) AS lastConsuptionInvoice, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.statususer LIMIT 1) AS operatorName \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.companyid = '" . $companyId . "' AND CT.contractstatus = 2  " . $where
                . " \n"
                . "LIMIT " . $limit . ";";
        $result = $this->query($sql);
        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function contractWaterLinkList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $waterLinkStatus = $this->real_escape_string($arrayFilterInfo['waterLinkStatus']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);


        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($waterLinkStatus != -1) {
            $where .= " AND CT.waterlinkstatus = '" . $waterLinkStatus . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND CT.waterlinkstatususer = '" . $userId . "' ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        }


        $sql = "SELECT CT.contractid, CT.cil, CT.contractdate, CT.consumertype, C.companyname, CT.customerid, \n"
                . "CT.billingneiborhood, C.telephone1, C.telephone2, C.telephone3, C.email, \n"
                . "CT.waterlinkstatus, CT.waterlinkstatusreason, CT.waterlinkstatusdate, CT.waterlinkstatususer, \n"
                . "(SELECT hydrometerid FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1) AS hydrometerid, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.waterlinkstatususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.companyid = '" . $companyId . "' AND "
                . "CT.contractstatus >= 2  " . $where
                . "; "; //ORDER BY  CT.waterlinkstatus, C.companyname

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function contractWaterLinkChangeStatus($contractId, $status, $reason, $userId) {

        $contractId = $this->real_escape_string($contractId);
        $status = $this->real_escape_string($status);
        $reason = $this->real_escape_string($reason);
        $userId = $this->real_escape_string($userId);


        $sql = "UPDATE contract SET \n"
                . "waterlinkstatus = '" . $status . "', "
                . "waterlinkstatusreason = '" . $reason . "', "
                . "waterlinkstatususer = '" . $userId . "', "
                . "waterlinkstatusdate = NOW() \n"
                . "WHERE contractid = '" . $contractId . "'; \n";

        $sql .= "SELECT waterlinkstatus \n"
                . "FROM contract \n"
                . "WHERE contractid = '" . $contractId . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['waterlinkstatus'] == $status) {
                            $saved = true;
                        }
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível alterar o estado da ligação."));
        }
        return json_encode(array("status" => 1, "msg" => "Estado da ligação actualizado com sucesso"));
    }

    public function contractWithoutHydrometerList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        $where .= " AND CT.contractstatus = '2' ";
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND CT.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  ( CAST(CT.contractdate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = "LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "CT.contractid, CT.cil, CT.contractdate, CT.consumertype, "
                . "C.companyname, C.customertaxid, CT.customerid, C.customertype, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingstreetname, "
                . "CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "CT.billingneiborhood,  CT.billingblock, C.telephone1, C.telephone2, C.telephone3, C.email, "
                . "CT.contractstatus, CT.statusdate, CT.statususer, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CT.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality ";

        if ($onlynumber == 1) {
            $fields = " COUNT(CT.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.companyid = '" . $companyId . "'   " . $where
                . " AND NOT EXISTS(SELECT hydrometerid FROM hydrometer AS H WHERE H.contractid = CT.contractid LIMIT 1) "
                . $limit
                . "; "; //ORDER BY  CT.contractstatus, C.companyname

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function hydrometerSave($arrayHydrometerInfo) {

        $id = $this->real_escape_string($arrayHydrometerInfo['id']);
        $companyid = $this->real_escape_string($arrayHydrometerInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayHydrometerInfo['dependencyid']);
        $hydrometerid = $this->real_escape_string($arrayHydrometerInfo['hydrometerid']);
        $contractid = $this->real_escape_string($arrayHydrometerInfo['contractid']);
        $customerid = $this->real_escape_string($arrayHydrometerInfo['customerid']);
        $functionalstatus = $this->real_escape_string($arrayHydrometerInfo['functionalstatus']);
        $brand = $this->real_escape_string($arrayHydrometerInfo['brand']);
        $hydrometertype = $this->real_escape_string($arrayHydrometerInfo['hydrometertype']);
        $diameter = $this->real_escape_string($arrayHydrometerInfo['diameter']);
        $classification = $this->real_escape_string($arrayHydrometerInfo['classification']);
        $manufacturedate = $this->real_escape_string($arrayHydrometerInfo['manufacturedate']);
        $installationdate = $this->real_escape_string($arrayHydrometerInfo['installationdate']);
        $installationdate = $this->real_escape_string($arrayHydrometerInfo['installationdate']);
        $installationrecord = $this->real_escape_string($arrayHydrometerInfo['installationrecord']);
        $statususer = $this->real_escape_string($arrayHydrometerInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if ($this->validateDuplicateElement($companyid, $id, "id",
                        $hydrometerid, "hydrometer", "hydrometerid") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Número do contador já cadastrado'));
        }
        $sql = "";
        if (!is_numeric($id)) {
            $sql .= "INSERT INTO hydrometer (\n"
                    . "companyid, dependencyid, hydrometerid, contractid, \n"
                    . "customerid, hydrometerstatus, entryuser, statususer, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                    . "'" . $customerid . "', '1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS "
                    . "(SELECT id FROM hydrometer WHERE companyid = '" . $companyid . "' AND hydrometerid = '" . $hydrometerid . "');  \n";
            $id = " (SELECT id FROM hydrometer WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
            //Installation Records
            $sql .= "INSERT INTO hydrometerrecord (\n"
                    . "companyid, dependencyid, hydrometerid, contractid, \n"
                    . "customerid, recorddate, recordvalue, recordstatus, "
                    . "beforerecorddate, beforerecordvalue, entryuser, statususer) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                    . "'" . $customerid . "', '" . $installationdate . "', '" . $installationrecord . "', '1', "
                    . "'" . $installationdate . "', '" . $installationrecord . "', '" . $statususer . "', '" . $statususer . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM hydrometerrecord WHERE contractid = '" . $contractid . "' AND "
                    . "CAST(recorddate AS DATE) <= CAST('" . $installationdate . "' AS DATE)); \n";
        } else {
            $id = "'" . $id . "'";
        }
        $sql .= "UPDATE hydrometer SET \n"
                . "hydrometerid = '" . $hydrometerid . "', "
                . "functionalstatus = '" . $functionalstatus . "', "
                . "brand = '" . $brand . "', "
                . "hydrometertype = '" . $hydrometertype . "', "
                . "diameter = '" . $diameter . "', "
                . "classification = '" . $classification . "', "
                . "manufacturedate = '" . $manufacturedate . "', "
                . "installationdate = '" . $installationdate . "', "
                . "installationrecord = '" . $installationrecord . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $id . "; \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometer \n"
                . "WHERE id = " . $id . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o contador."));
        }
        return json_encode(array("status" => 1, "msg" => "Contador guardado com sucesso"));
    }

    public function hydrometerSaveLote($arrayHydrometerInfo) {

        $id = $this->real_escape_string($arrayHydrometerInfo['id']);
        $companyid = $this->real_escape_string($arrayHydrometerInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayHydrometerInfo['dependencyid']);
        $hydrometerid = $this->real_escape_string($arrayHydrometerInfo['hydrometerid']);
        $contractid = $this->real_escape_string($arrayHydrometerInfo['contractid']);
        $customerid = $this->real_escape_string($arrayHydrometerInfo['customerid']);
        $functionalstatus = $this->real_escape_string($arrayHydrometerInfo['functionalstatus']);
        $brand = $this->real_escape_string($arrayHydrometerInfo['brand']);
        $hydrometertype = $this->real_escape_string($arrayHydrometerInfo['hydrometertype']);
        $diameter = $this->real_escape_string($arrayHydrometerInfo['diameter']);
        $classification = $this->real_escape_string($arrayHydrometerInfo['classification']);
        $manufacturedate = $this->real_escape_string($arrayHydrometerInfo['manufacturedate']);
        $installationdate = $this->real_escape_string($arrayHydrometerInfo['installationdate']);
        $installationrecord = $this->real_escape_string($arrayHydrometerInfo['installationrecord']);
        $hydrometerstatus = $this->real_escape_string($arrayHydrometerInfo['hydrometerstatus']);
        $cilserie = $this->real_escape_string($arrayHydrometerInfo['cilserie']);
        $cilsequencenumber = $this->real_escape_string($arrayHydrometerInfo['cilsequencenumber']);
        $cil = $this->real_escape_string($arrayHydrometerInfo['cil']);
        $updateContractCil = $this->real_escape_string($arrayHydrometerInfo['updateContractCil']);
        $statususer = $this->real_escape_string($arrayHydrometerInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        //Check hydrometer id
        $sql = "SELECT hydrometerid \n"
                . "FROM hydrometer \n"
                . "WHERE companyid = '" . $companyid . "' AND hydrometerid = '" . $hydrometerid . "'; ";
        $checkHydrometer = $this->query($sql);
        if ($checkHydrometer->num_rows > 0) {
            mysqli_free_result($checkHydrometer);
            echo json_encode(array("status" => 0, "msg" => "O nº de contador já foi cadastrado."));
            return false;
        }

        //Check contract id
        $sql = "SELECT contractid \n"
                . "FROM contract \n"
                . "WHERE contractid = '" . $contractid . "'; ";

        $checkResult = $this->query($sql);
        if ($checkResult->num_rows <= 0) {
            mysqli_free_result($checkResult);
            echo json_encode(array("status" => 0, "msg" => "O nº de contrato não existe."));
            return false;
        }

        $sql = "INSERT INTO hydrometer (\n"
                . "companyid, dependencyid, hydrometerid, contractid, \n"
                . "customerid, hydrometerstatus, entryuser, statususer, registernumber) \n"
                . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                . "(SELECT customerid FROM contract WHERE contractid = '" . $contractid . "' LIMIT 1), "
                . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "'  \n"
                . "FROM insert_table; \n";

        $hydrometerid = "(SELECT hydrometerid FROM hydrometer WHERE registernumber = '" . $registerNumber . "' LIMIT 1)";

        $sql .= "UPDATE hydrometer SET \n"
                . "functionalstatus = '" . $functionalstatus . "', "
                . "brand = '" . $brand . "', "
                . "hydrometertype = '" . $hydrometertype . "', "
                . "diameter = '" . $diameter . "', "
                . "classification = '" . $classification . "', "
                . "manufacturedate = '" . $manufacturedate . "', "
                . "installationdate = '" . $installationdate . "', "
                . "installationrecord = '" . $installationrecord . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE hydrometerid = " . $hydrometerid . "; \n";

        if ($updateContractCil == 1) {
            $sql .= "UPDATE contract SET \n"
                    . "cilserie = '" . $cilserie . "', "
                    . "cilsequencenumber = '" . $cilsequencenumber . "', "
                    . "cil = '" . $cil . "' \n"
                    . "WHERE companyid = '" . $companyid . "' AND contractid = '" . $contractid . "'; \n";
        }

        $sql .= "SELECT id \n"
                . "FROM hydrometer \n"
                . "WHERE hydrometerid = " . $hydrometerid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar o contador."));
        }
        return json_encode(array("status" => 1, "msg" => "Contador guardado com sucesso"));
    }

    public function hydrometerGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $functionalStatus = $this->real_escape_string($arrayFilterInfo['functionalStatus']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND H.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($functionalStatus != -1) {
            $where .= " AND H.functionalstatus = '" . $functionalStatus . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND H.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  ( CAST(H.installationdate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        }
//. "(COALESCE((SELECT HR.recorddate FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.id DESC LIMIT 1), H.installationdate)) AS lastrecorddate, \n"

        $fields = "H.id, H.hydrometerid, H.functionalstatus, H.brand, H.hydrometertype, H.diameter, \n"
                . "H.classification, H.manufacturedate, H.installationdate, H.hydrometerstatus, \n"
                . "H.statusdate, H.statususer, \n"
                . "(COALESCE((SELECT HR.recorddate FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.id DESC LIMIT 1), '')) AS lastrecord, \n"
                . "CT.contractid, CT.contractdate, CT.consumertype, C.companyname, CT.customerid, \n"
                . "CT.billingneiborhood, CT.billingblock, CT.billingstreetname, CT.billingbuildingnumber, "
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingpostalcode, "
                . "CT.cil, C.telephone1, C.telephone2, C.telephone3, "
                . "CT.contractstatus, \n"
                . "(COALESCE((SELECT HR.recorddate FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.recorddate DESC LIMIT 1), H.installationdate)) AS lastrecorddate, \n"
                . "(COALESCE((SELECT HR.recordvalue FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.recorddate DESC LIMIT 1), 0)) AS lastrecordvalue, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = H.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality ";
        if ($onlynumber == 1) {
            $fields = " COUNT(H.id) ";
            $limit = "";
        }
        $sql = "SELECT " . $fields . " \n"
                . "FROM hydrometer AS H, contract AS CT, customer AS C \n"
                . "WHERE H.contractid = CT.contractid AND H.customerid = C.customerid AND CT.customerid = C.customerid AND "
                . "H.companyid = '" . $companyId . "'   " . $where
                . $limit
                . "; "; //ORDER BY  H.hydrometerstatus, C.companyname

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function hydrometerGetDetails($companyId, $hydrometerId) {

        $companyId = $this->real_escape_string($companyId);
        $hydrometerId = $this->real_escape_string($hydrometerId);


        $sql = "SELECT H.id, H.hydrometerid, H.functionalstatus, H.brand, H.hydrometertype, \n"
                . "H.diameter, H.classification, H.manufacturedate, H.installationdate, H.hydrometerstatus, \n"
                . "CT.contractid, CT.contractdate, \n"
                . "CT.consumertype, CT.contractstatus, CT.cil, CT.customerid, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "H.entryuser, H.entrydate, H.statususer, H.statusdate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = H.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = H.statususer LIMIT 1) AS operatorName \n"
                . "FROM hydrometer AS H, contract AS CT, customer AS C \n"
                . "WHERE H.contractid = CT.contractid AND H.customerid = C.customerid AND "
                . "CT.customerid = C.customerid AND H.companyid = '" . $companyId . "' AND H.hydrometerid = '" . $hydrometerId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function hydrometerChangeStatus($hydrometerId, $userId) {

        $hydrometerId = $this->real_escape_string($hydrometerId);
        $userId = $this->real_escape_string($userId);


        $sql = "DELETE FROM hydrometer  \n"
                . "WHERE id = '" . $hydrometerId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometer \n"
                . "WHERE id = '" . $hydrometerId . "'; \n";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível alterar o estado do contador."));
        }
        return json_encode(array("status" => 1, "msg" => "Estado do contador actualizado com sucesso"));
    }

    public function hydrometerListSearch($companyId, $dependencyId,
            $consumerType, $neiborhood, $searchLimit, $searchTag, $field) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $consumerType = $this->real_escape_string($consumerType);
        $neiborhood = $this->real_escape_string($neiborhood);
        $searchLimit = $this->real_escape_string($searchLimit);
        $searchTag = $this->real_escape_string($searchTag);
        $field = $this->real_escape_string($field);

        $where = "";
        if ($dependencyId != -1) {
            $where = " AND H.dependencyid = " . $dependencyId . " ";
        }
        if ($consumerType != -1) {
            $where = " AND CT.consumertype = " . $consumerType . " ";
        }
        if ($neiborhood != "") {
            $where = " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($searchTag != "") {
            if ($field == 1) {
                $where .= " AND (CT.cil = '" . $searchTag . "') ";
            } elseif ($field == 2) {
                $where .= " AND (CT.contractid = '" . $searchTag . "') ";
            } elseif ($field == 3) {
                $where .= " AND (CT.customerid = '" . $searchTag . "') ";
            } elseif ($field == 5) {
                $where .= " AND (H.hydrometerid = '" . $searchTag . "') ";
            } else {
                $where .= " AND (C.companyname LIKE '%" . $searchTag . "%' OR "
                        . "C.customertaxid = '" . $searchTag . "') ";
            }
        }

        $limit = is_numeric($searchLimit) ? $searchLimit : 5;

        $sql = "SELECT H.hydrometerid, H.functionalstatus, CT.contractid, CT.cil, CT.customerid, \n"
                . "C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, CT.waterlinkstatus, \n"
                . "(COALESCE((SELECT HR.recorddate FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.recorddate DESC LIMIT 1), H.installationdate)) AS lastrecorddate, \n"
                . "(COALESCE((SELECT HR.recordvalue FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.recorddate DESC LIMIT 1), 0)) AS lastrecordvalue \n"
                . "FROM hydrometer AS H, contract AS CT, customer AS C \n"
                . "WHERE H.contractid = CT.contractid AND H.customerid = C.customerid AND CT.customerid = C.customerid  "
                . "AND CT.companyid = '" . $companyId . "' AND H.hydrometerstatus = 1 AND CT.contractstatus = 2 " . $where
                . " \n"
                . "LIMIT " . $limit . ";";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function hydrometerRecordSave($arrayHydrometerRecordInfo) {

        $companyid = $this->real_escape_string($arrayHydrometerRecordInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayHydrometerRecordInfo['dependencyid']);
        $hydrometerrecordid = $this->real_escape_string($arrayHydrometerRecordInfo['hydrometerrecordid']);
        $hydrometerid = $this->real_escape_string($arrayHydrometerRecordInfo['hydrometerid']);
        $contractid = $this->real_escape_string($arrayHydrometerRecordInfo['contractid']);
        $customerid = $this->real_escape_string($arrayHydrometerRecordInfo['customerid']);
        $recorddate = $this->real_escape_string($arrayHydrometerRecordInfo['recorddate']);
        $recordvalue = $this->real_escape_string($arrayHydrometerRecordInfo['recordvalue']);
        $amount = $this->real_escape_string($arrayHydrometerRecordInfo['amount']);
        $beforerecorddate = $this->real_escape_string($arrayHydrometerRecordInfo['beforerecorddate']);
        $beforerecordvalue = $this->real_escape_string($arrayHydrometerRecordInfo['beforerecordvalue']);
        $days = $this->real_escape_string($arrayHydrometerRecordInfo['days']);
        $cmd = $this->real_escape_string($arrayHydrometerRecordInfo['cmd']);
        $customerportalstatus = $this->real_escape_string($arrayHydrometerRecordInfo['customerportalstatus']);
        $statususer = $this->real_escape_string($arrayHydrometerRecordInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        //Save record
        $sql = "";
        if (!is_numeric($hydrometerrecordid)) {
            //Confirm before record
            $sql .= "INSERT INTO hydrometerrecord (\n"
                    . "companyid, dependencyid, hydrometerid, contractid, \n"
                    . "customerid, recorddate, recordvalue, recordstatus, "
                    . "beforerecorddate, beforerecordvalue) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                    . "'" . $customerid . "', (DATE_ADD('" . $beforerecorddate . "', INTERVAL -31 DAY)), "
                    . "'" . $beforerecordvalue . "', '1', (DATE_ADD('" . $beforerecorddate . "', INTERVAL -31 DAY)), "
                    . "'" . $beforerecordvalue . "'   \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM hydrometerrecord WHERE contractid = '" . $contractid . "' AND "
                    . "CAST(recorddate AS DATE) <= (DATE_ADD('" . $beforerecorddate . "', INTERVAL -31 DAY))); \n";
            //real record
            $sql .= "INSERT INTO hydrometerrecord (\n"
                    . "companyid, dependencyid, hydrometerid, contractid, \n"
                    . "customerid, recordstatus, entryuser, statususer, "
                    . "customerportalstatus, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                    . "'" . $customerid . "', '1', '" . $statususer . "', '" . $statususer . "', "
                    . "'" . $customerportalstatus . "', '" . $registerNumber . "'  \n"
                    . "FROM insert_table; \n";
            $hydrometerrecordid = "(SELECT id FROM hydrometerrecord WHERE registernumber = '" . $registerNumber . "')";
        } else {
            $hydrometerrecordid = "'" . $hydrometerrecordid . "'";
        }

        $sql .= "UPDATE hydrometerrecord SET \n"
                . "recorddate = '" . $recorddate . "', "
                . "recordvalue = '" . $recordvalue . "', "
                . "amount = '" . $amount . "', "
                . "beforerecorddate = '" . $beforerecorddate . "', "
                . "beforerecordvalue = '" . $beforerecordvalue . "', "
                . "days = '" . $days . "', "
                . "cmd = '" . $cmd . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $hydrometerrecordid . "; \n";

        $currentConsuption = 0;
        //Save consumption
        for ($index = 1; $index <= $days; $index++) {
            $currentConsuption += $cmd;
            $currYear = (int) date("Y", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currMonth = (int) date("n", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currDay = (int) date("d", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currQuarter = monthQuarter($currDay);

            $nextYear = (int) date("Y", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextMonth = (int) date("n", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextDay = (int) date("d", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextQuarter = monthQuarter($nextDay);

            $entryData = false;
            if ($index == $days) {
                $entryData = true;
            } else {
                if ($currYear != $nextYear) {
                    $entryData = true;
                } else {
                    if ($currMonth != $nextMonth) {
                        $entryData = true;
                    } else {
                        if ($currQuarter != $nextQuarter) {
                            $entryData = true;
                        }
                    }
                }
            }

            if ($entryData) {
                //Most important is company, contract and consumption date
                //Insert if new
                $consumptiondate = date("Y-m-d", strtotime($currYear . "-" . $currMonth . "-01"));
                $sql .= "INSERT INTO consumption (\n"
                        . "companyid, dependencyid, hydrometerid, contractid, "
                        . "customerid, hydrometerrecordid, consumptiondate, monthquater, cmd, \n"
                        . "consumptionstatus, statususer, entryuser, registernumber) \n"
                        . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $hydrometerid . "', '" . $contractid . "', \n"
                        . "'" . $customerid . "', " . $hydrometerrecordid . ", "
                        . "'" . $consumptiondate . "', '" . $currQuarter . "', '" . $currentConsuption . "', \n"
                        . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "' \n"
                        . "FROM insert_table \n"
                        . "WHERE NOT EXISTS (SELECT id FROM consumption WHERE companyid = '" . $companyid . "' AND  "
                        . "contractid = '" . $contractid . "' AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "'); \n";
                //Update if exists
                $sql .= "UPDATE consumption SET \n"
                        . "hydrometerid = '" . $hydrometerid . "', "
                        . "hydrometerrecordid = " . $hydrometerrecordid . ", "
                        . "cmd = '" . $currentConsuption . "', "
                        . "statususer = '" . $statususer . "', "
                        . "statusdate = NOW() \n"
                        . "WHERE companyid = '" . $companyid . "' AND   "
                        . "contractid = '" . $contractid . "' AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "'; \n";

                $currentConsuption = 0;
            }

            //$consumptiondate = Date($beforerecorddate, strtotime('+' . $index . ' days'));
            //$consumptiondate = "DATE_ADD('" . $beforerecorddate . "', INTERVAL " . $index . " DAY)";
        }

        //Delete consumption upper record date
        $sql .= "DELETE FROM consumption \n"
                . "WHERE companyid = '" . $companyid . "' AND contractid = '" . $contractid . "' AND "
                . "hydrometerrecordid = " . $hydrometerrecordid . " AND "
                . "(CAST(consumptiondate AS DATE) > CAST('" . $recorddate . "' AS DATE)); \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometerrecord \n"
                . "WHERE id = " . $hydrometerrecordid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a leitura."));
        }
        return json_encode(array("status" => 1, "msg" => "Leitura guardada com sucesso"));
    }

    public function hydrometerRecordLoteSave($arrayHydrometerRecordInfo) {

        $companyid = $this->real_escape_string($arrayHydrometerRecordInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayHydrometerRecordInfo['dependencyid']);
        $hydrometerrecordid = $this->real_escape_string($arrayHydrometerRecordInfo['hydrometerrecordid']);
        $hydrometerid = $this->real_escape_string($arrayHydrometerRecordInfo['hydrometerid']);
        $contractid = $this->real_escape_string($arrayHydrometerRecordInfo['contractid']);
        $customerid = $this->real_escape_string($arrayHydrometerRecordInfo['customerid']);
        $recorddate = $this->real_escape_string($arrayHydrometerRecordInfo['recorddate']);
        $recordvalue = $this->real_escape_string($arrayHydrometerRecordInfo['recordvalue']);
        $amount = $this->real_escape_string($arrayHydrometerRecordInfo['amount']);
        $beforerecorddate = $this->real_escape_string($arrayHydrometerRecordInfo['beforerecorddate']);
        $beforerecordvalue = $this->real_escape_string($arrayHydrometerRecordInfo['beforerecordvalue']);
        $days = $this->real_escape_string($arrayHydrometerRecordInfo['days']);
        $cmd = $this->real_escape_string($arrayHydrometerRecordInfo['cmd']);
        $statususer = $this->real_escape_string($arrayHydrometerRecordInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if ($customerid == "") {
            $customerid = " (SELECT customerid FROM contract WHERE contractid = '" . $contractid . "' LIMIT 1) ";
        } else {
            $customerid = "'" . $customerid . "'";
        }

        /*   //Check hydrometer number
          $sql = "SELECT hydrometerid \n"
          . "FROM hydrometer \n"
          . "WHERE companyid = '" . $companyid . "' AND hydrometerid = '" . $hydrometerid . "'; ";
          $checkHydromter = $this->query($sql);
          if ($checkHydromter->num_rows <= 0) {
          mysqli_free_result($checkHydromter);
          echo json_encode(array("status" => 0, "msg" => "Contador não encontrado."));
          return false;
          }
          //Check contract number
          $sql = "SELECT contractid \n"
          . "FROM contract \n"
          . "WHERE companyid = '" . $companyid . "' AND contractid = '" . $contractid . "'; ";
          $checkContract = $this->query($sql);
          if ($checkContract->num_rows <= 0) {
          mysqli_free_result($checkContract);
          echo json_encode(array("status" => 0, "msg" => "Contrato não encontrado."));
          return false;
          }
          //Check customer number
          $sql = "SELECT customerid \n"
          . "FROM customer \n"
          . "WHERE companyid = '" . $companyid . "' AND customerid = '" . $customerid . "'; ";
          $checkCcustomer = $this->query($sql);
          if ($checkCcustomer->num_rows <= 0) {
          mysqli_free_result($checkCcustomer);
          echo json_encode(array("status" => 0, "msg" => "Cliente não encontrado."));
          return false;
          }
         * */

//Check record number
        $sql = "SELECT id \n"
                . "FROM hydrometerrecord \n"
                . "WHERE companyid = '" . $companyid . "' AND hydrometerid = '" . $hydrometerid . "' AND "
                . "contractid = '" . $contractid . "' AND customerid = " . $customerid . " AND "
                . "recorddate = '" . $recorddate . "'; ";
        $checkRecord = $this->query($sql);
        if ($checkRecord->num_rows > 0) {
            mysqli_free_result($checkRecord);
            echo json_encode(array("status" => 0, "msg" => "Leitura já foi cadastrado."));
            return false;
        }

        //Save record
        $sql = "INSERT INTO hydrometerrecord (\n"
                . "companyid, dependencyid, hydrometerid, contractid, \n"
                . "customerid, recordstatus, entryuser, statususer, registernumber) \n"
                . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                . "'" . $hydrometerid . "', '" . $contractid . "', \n"
                . "" . $customerid . ", '1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "'  \n"
                . "FROM insert_table; \n";
        $hydrometerrecordid = "(SELECT id FROM hydrometerrecord WHERE registernumber = '" . $registerNumber . "')";


        $sql .= "UPDATE hydrometerrecord SET \n"
                . "recorddate = '" . $recorddate . "', "
                . "recordvalue = '" . $recordvalue . "', "
                . "amount = '" . $amount . "', "
                . "beforerecorddate = '" . $beforerecorddate . "', "
                . "beforerecordvalue = '" . $beforerecordvalue . "', "
                . "days = '" . $days . "', "
                . "cmd = '" . $cmd . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $hydrometerrecordid . "; \n";

        $currentConsuption = 0;
        //Save consumption
        for ($index = 1; $index <= $days; $index++) {
            $currentConsuption += $cmd;
            $currYear = (int) date("Y", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currMonth = (int) date("n", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currDay = (int) date("d", strtotime($beforerecorddate . "+ " . $index . " days"));
            $currQuarter = monthQuarter($currDay);

            $nextYear = (int) date("Y", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextMonth = (int) date("n", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextDay = (int) date("d", strtotime($beforerecorddate . "+ " . ($index + 1) . " days"));
            $nextQuarter = monthQuarter($nextDay);

            $entryData = false;
            if ($index == $days) {
                $entryData = true;
            } else {
                if ($currYear != $nextYear) {
                    $entryData = true;
                } else {
                    if ($currMonth != $nextMonth) {
                        $entryData = true;
                    } else {
                        if ($currQuarter != $nextQuarter) {
                            $entryData = true;
                        }
                    }
                }
            }

            if ($entryData) {
                //Most important is company, contract and consumption date
                //Insert if new
                $consumptiondate = date("Y-m-d", strtotime($currYear . "-" . $currMonth . "-01"));
                $sql .= "INSERT INTO consumption (\n"
                        . "companyid, dependencyid, hydrometerid, contractid, "
                        . "customerid, hydrometerrecordid, consumptiondate, monthquater, cmd, \n"
                        . "consumptionstatus, statususer, entryuser, registernumber) \n"
                        . "SELECT '" . $companyid . "', '" . $dependencyid . "', '" . $hydrometerid . "', '" . $contractid . "', \n"
                        . "" . $customerid . ", " . $hydrometerrecordid . ", "
                        . "'" . $consumptiondate . "', '" . $currQuarter . "', '" . $currentConsuption . "', \n"
                        . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "' \n"
                        . "FROM insert_table \n"
                        . "WHERE NOT EXISTS (SELECT id FROM consumption WHERE companyid = '" . $companyid . "' AND  "
                        . "contractid = '" . $contractid . "' AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "'); \n";
                //Update if exists
                $sql .= "UPDATE consumption SET \n"
                        . "hydrometerid = '" . $hydrometerid . "', "
                        . "hydrometerrecordid = " . $hydrometerrecordid . ", "
                        . "cmd = '" . $currentConsuption . "', "
                        . "statususer = '" . $statususer . "', "
                        . "statusdate = NOW() \n"
                        . "WHERE companyid = '" . $companyid . "' AND   "
                        . "contractid = '" . $contractid . "' AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "'; \n";

                $currentConsuption = 0;
            }
        }

        $sql .= "SELECT id \n"
                . "FROM hydrometerrecord \n"
                . "WHERE id = " . $hydrometerrecordid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a leitura."));
        }
        return json_encode(array("status" => 1, "msg" => "Leitura guardada com sucesso"));
    }

    public function hydrometerRecordGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND HR.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND HR.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  ( CAST(HR.recorddate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = " LIMIT " . ($startIdx ) . ", " . ($endIdx - $startIdx) . " ";
        }
//. "(COALESCE((SELECT HR.recorddate FROM hydrometerrecord AS HR WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = H.contractid ORDER BY HR.id DESC LIMIT 1), H.installationdate)) AS lastrecorddate, \n"

        $field = "HR.id, HR.recorddate, HR.recordvalue, HR.amount, HR.days, HR.cmd, "
                . "HR.hydrometerid, HR.recordstatus, HR.statusdate, HR.statususer, \n"
                . "CT.contractid, CT.cil, CT.consumertype, C.companyname, CT.customerid, \n"
                . "CT.billingneiborhood, C.telephone1, C.telephone2, C.telephone3, "
                . "CT.contractstatus, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = HR.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality \n";

        if ($onlynumber == 1) {
            $field = " COUNT(HR.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $field
                . "FROM hydrometerrecord AS HR, contract AS CT, customer AS C \n"
                . "WHERE HR.contractid = CT.contractid AND HR.customerid = C.customerid AND CT.customerid = C.customerid AND "
                . "HR.companyid = '" . $companyId . "'   " . $where
                . $limit
                . "; "; //ORDER BY  C.companyname, HR.recorddate

        $result = $this->query($sql);
        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function hydrometerRecordGetDetails($companyId, $recordId) {

        $companyId = $this->real_escape_string($companyId);
        $recordId = $this->real_escape_string($recordId);


        $sql = "SELECT HR.id, HR.recorddate, HR.recordvalue, HR.amount, HR.beforerecorddate, "
                . "HR.beforerecordvalue, HR.days, HR.cmd, HR.recordstatus, "
                . "H.hydrometerid, H.functionalstatus, \n"
                . "CT.contractid, CT.cil, CT.customerid, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "HR.entryuser, HR.entrydate, HR.statususer, HR.statusdate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = HR.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = HR.statususer LIMIT 1) AS operatorName \n"
                . "FROM hydrometerrecord AS HR, hydrometer AS H, contract AS CT, customer AS C \n"
                . "WHERE HR.hydrometerid = H.hydrometerid AND HR.contractid = CT.contractid AND HR.customerid = C.customerid AND "
                . "CT.customerid = C.customerid AND HR.companyid = '" . $companyId . "' AND HR.id = '" . $recordId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function hydrometerRecordDelete($recordId, $userId) {

        $recordId = $this->real_escape_string($recordId);
        $userId = $this->real_escape_string($userId);


        $sql = "DELETE FROM hydrometerrecord  \n"
                . "WHERE id = '" . $recordId . "'; \n";

        $sql .= "DELETE FROM consumption \n"
                . "WHERE hydrometerrecordid = '" . $recordId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometerrecord \n"
                . "WHERE id = '" . $recordId . "'; \n";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível apagar esta leitura."));
        }
        return json_encode(array("status" => 1, "msg" => "Leitura apagada com sucesso."));
    }

    public function hydrometerRecordHistoricList($companyId, $contractid) {

        $companyId = $this->real_escape_string($companyId);
        $contractid = $this->real_escape_string($contractid);

        $sql = "SELECT * \n"
                . "FROM hydrometerrecord AS HR  \n"
                . "WHERE HR.companyid = '" . $companyId . "' AND HR.contractid = '" . $contractid . "'   \n"
                . "ORDER BY recorddate DESC, recordvalue DESC, beforerecorddate DESC \n"
                . "LIMIT 14;";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function settingHydrometerBrandList($sessionId) {

        $sessionId = $this->real_escape_string($sessionId);

        if (!$this->validateSession($sessionId)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }

        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = HB.statususer LIMIT 1) AS operatorName \n"
                . "FROM hydrometerbrand AS HB \n"
                . "ORDER BY  brand; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function settingHydrometerBrandSave($arrayBrandInfo) {

        $brandId = $this->real_escape_string($arrayBrandInfo['brandId']);
        $brand = $this->real_escape_string($arrayBrandInfo['brand']);
        $statususer = $this->real_escape_string($arrayBrandInfo['statususer']);
        $sessionid = $this->real_escape_string($arrayBrandInfo['sessionid']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if (!$this->validateSession($sessionid)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }

        if ($this->validateDuplicateElement(-1, $brandId, "id",
                        $brand, "hydrometerbrand", "brand") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Marca de contador já cadastrado'));
        }

        $sql = "";
        if (!is_numeric($brandId)) {
            $sql .= "INSERT INTO hydrometerbrand (\n"
                    . "brand, entryuser, statususer, registernumber, sessionid) \n"
                    . "SELECT '" . $brand . "',  '" . $statususer . "', '" . $statususer . "', "
                    . "'" . $registerNumber . "', '" . $sessionid . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS "
                    . "(SELECT id FROM hydrometerbrand WHERE brand = '" . $brand . "');  \n";
            $brandId = "(SELECT id FROM hydrometerbrand WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $brandId = "'" . $brandId . "'";
        }

        $sql .= "UPDATE hydrometerbrand SET \n"
                . "brand = '" . $brand . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $brandId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometerbrand \n"
                . "WHERE id = " . $brandId . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a marca de contador."));
        }
        return json_encode(array("status" => 1, "msg" => "Marca de contador guardada com sucesso."));
    }

    public function settingHydrometerBrandDelete($arrayBrandInfo) {

        $brandId = $this->real_escape_string($arrayBrandInfo['brandId']);
        $brand = $this->real_escape_string($arrayBrandInfo['brand']);
        $statususer = $this->real_escape_string($arrayBrandInfo['statususer']);
        $sessionid = $this->real_escape_string($arrayBrandInfo['sessionid']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if (!$this->validateSession($sessionid)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }

        $sql = "DELETE FROM hydrometerbrand \n"
                . "WHERE id = '" . $brandId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM hydrometerbrand \n"
                . "WHERE id = '" . $brandId . "'; \n";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível eliminar a marca de contador."));
        }
        return json_encode(array("status" => 1, "msg" => "Marca de contador eliminada com sucesso."));
    }

    public function settingNeiborHoodList($sessionId) {

        $sessionId = $this->real_escape_string($sessionId);

        if (!$this->validateSession($sessionId)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }

        $sql = "SELECT *, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = N.statususer LIMIT 1) AS operatorName \n"
                . "FROM neiborhood AS N \n"
                . "ORDER BY  neiborhood; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function settingNeiborhoodSave($arrayNeiborInfo) {

        $neiborhoodId = $this->real_escape_string($arrayNeiborInfo['neiborhoodId']);
        $neiborhood = $this->real_escape_string($arrayNeiborInfo['neiborhood']);
        $serie = $this->real_escape_string($arrayNeiborInfo['serie']);
        $statususer = $this->real_escape_string($arrayNeiborInfo['statususer']);
        $sessionid = $this->real_escape_string($arrayNeiborInfo['sessionid']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if (!$this->validateSession($sessionid)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }

        if ($this->validateDuplicateElement(-1, $neiborhoodId, "id",
                        $neiborhood, "neiborhood", "neiborhood") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Bairro já cadastrado'));
        }

        if ($this->validateDuplicateElement(-1, $neiborhoodId, "id",
                        $serie, "neiborhood", "serie") == 1) {
            return json_encode(array("status" => 0, "msg" => 'Série do CIL já cadastrada'));
        }

        $sql = "";
        if (!is_numeric($neiborhoodId)) {
            $sql .= "INSERT INTO neiborhood (\n"
                    . "neiborhood, serie, entryuser, statususer, "
                    . "registernumber, sessionid) \n"
                    . "SELECT '" . $neiborhood . "',  '" . $serie . "','" . $statususer . "', '" . $statususer . "', "
                    . "'" . $registerNumber . "', '" . $sessionid . "'  \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS "
                    . "(SELECT id FROM neiborhood WHERE neiborhood = '" . $neiborhood . "');  \n";
            $neiborhoodId = "(SELECT id FROM neiborhood WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $neiborhoodId = "'" . $neiborhoodId . "'";
        }

        $sql .= "UPDATE neiborhood SET \n"
                . "neiborhood = '" . $neiborhood . "', "
                . "serie = '" . $serie . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $neiborhoodId . "; \n";

        $sql .= "SELECT id \n"
                . "FROM neiborhood \n"
                . "WHERE id = " . $neiborhoodId . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar este bairro."));
        }
        return json_encode(array("status" => 1, "msg" => "Bairro de contador guardado com sucesso."));
    }

    public function settingNeiborhoodDelete($arrayNeiborInfo) {

        $neiborhoodId = $this->real_escape_string($arrayNeiborInfo['neiborhoodId']);
        $statususer = $this->real_escape_string($arrayNeiborInfo['statususer']);
        $sessionid = $this->real_escape_string($arrayNeiborInfo['sessionid']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        if (!$this->validateSession($sessionid)) {
            return json_encode(array("status" => 0, "msg" => "Sessão expirada."));
        }


        $sql = "DELETE FROM neiborhood  \n"
                . "WHERE id = '" . $neiborhoodId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM neiborhood \n"
                . "WHERE id = '" . $neiborhoodId . "'; \n";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível eliminar este bairro."));
        }
        return json_encode(array("status" => 1, "msg" => "Bairro eliminado com sucesso."));
    }

    public function consumptionGetContractsNumber($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $estimateddate1 = $this->real_escape_string($arrayFilterInfo['estimateddate1']);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }

        $sql = "SELECT * \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.companyid = '" . $companyId . "' AND "
                . "CT.contractstatus = 2 AND CT.waterlinkstatus = 1 " . $where . " AND "
                . "(CAST(contractdate AS DATE) <= CAST('" . $estimateddate1 . "' AS DATE));";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function consumptionGetCMDbyConsumerType($arrayFilterInfo) {

        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);


        $sql = "SELECT consumptiondiary \n"
                . "FROM watertax  \n"
                . "WHERE consumertype = '" . $consumertype . "';";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            return $row['consumptiondiary'];
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function consumptionEstimatedGroupSave($arrayConsumptionEstimatedGroupInfo) {

        $companyid = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['companyid']);
        $dependencyId = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['dependencyId']);
        $days = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['days']);
        $estimateddate1 = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['estimateddate1']);
        $estimateddate2 = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['estimateddate2']);
        $statususer = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['statususer']);
        $consumertype = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayConsumptionEstimatedGroupInfo['neiborhood']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }

        $cmd = "(SELECT AVG(CO.cmd) FROM consumption AS CO "
                . "WHERE CO.companyid = CT.companyid AND CO.contractid = CT.contractid AND "
                . "(CAST(consumptiondate AS DATE) BETWEEN (DATE_ADD('" . $estimateddate1 . "', INTERVAL -90 DAY)) AND CAST('" . $estimateddate1 . "' AS DATE)) )";
        $lawCMD = "(SELECT WT.consumptiondiary FROM watertax AS WT WHERE WT.consumertype = '" . $consumertype . "' LIMIT 1)";
        /* $lawCMD = "'700'";
          /*   if (($consumertype == 2) || ($consumertype == 3)) {//Commerce and services
          $lawCMD = "'1239.58333'";
          } elseif ($consumertype == 4) {//Industry
          $lawCMD = "'6916.05839'";
          } elseif ($consumertype == 5) {//Chafariz
          $lawCMD = "'2164.50216'";
          } elseif ($consumertype == 6) {//Girafa
          $lawCMD = "'9999'";
          } */
        //For now, dont use last 90 days
        $cmd = $lawCMD; // "(COALESCE(" . $cmd . ", " . $lawCMD . ", 700))";
        //Save estimated without amount
        $sql = "INSERT INTO consumptionestimated (\n"
                . "companyid, dependencyid, contractid, customerid, \n"
                . "estimateddate1, estimateddate2, days, cmd, \n"
                . "estimatedstatus, entryuser, statususer, registernumber) \n"
                . "SELECT CT.companyid, CT.dependencyid, CT.contractid, CT.customerid, "
                . "'" . $estimateddate1 . "', '" . $estimateddate2 . "', '" . $days . "', " . $cmd . ", \n"
                . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "'  \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.companyid = '" . $companyid . "' AND CT.contractstatus = 2 AND CT.waterlinkstatus = 1 " . $where . " AND "
                . "NOT EXISTS(SELECT id FROM consumptionestimated AS CE WHERE CE.companyid = CT.companyid AND "
                . "CE.contractid = CT.contractid AND CAST(CE.estimateddate1 AS DATE) = CAST('" . $estimateddate1 . "' AS DATE) ); \n";

        $consumptionestimatedid = "(SELECT CE.id FROM consumptionestimated AS CE "
                . "WHERE CE.companyid = CT.companyid AND CE.contractid = CT.contractid AND CE.registernumber = '" . $registerNumber . "' LIMIT 1)";

        //Save consumption
        for ($index = 0; $index < $days; $index++) {
            $consumptiondate = "DATE_ADD('" . $estimateddate1 . "', INTERVAL " . $index . " DAY)";
            //Most important is company, contract and consumption date
            //Insert where not exists
            $sql .= "INSERT INTO consumption (\n"
                    . "companyid, dependencyid, contractid, customerid, \n"
                    . "consumptionestimatedid, consumptiondate, cmd, \n"
                    . "consumptionstatus, statususer, entryuser, registernumber) \n"
                    . "SELECT CT.companyid, CT.dependencyid, CT.contractid, CT.customerid, \n"
                    . $consumptionestimatedid . ", " . $consumptiondate . ", \n"
                    . "(SELECT CE.cmd FROM consumptionestimated AS CE WHERE CE.id = " . $consumptionestimatedid . " LIMIT 1), \n"
                    . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "' \n"
                    . "FROM contract AS CT \n"
                    . "WHERE CT.companyid = '" . $companyid . "' AND CT.contractid IN (SELECT CE.contractid FROM consumptionestimated AS CE WHERE CE.registernumber = '" . $registerNumber . "') AND \n"
                    . "(CAST(CT.contractdate AS DATE) <= " . $consumptiondate . ")  AND \n"
                    . "NOT EXISTS (SELECT CO.id FROM consumption AS CO WHERE CO.companyid = CT.companyid AND   "
                    . "CO.contractid = CT.contractid AND consumptiondate = " . $consumptiondate . "); \n";
        }
        //Update amount and days of estimated
        $sql .= "UPDATE consumptionestimated AS CE SET \n"
                . "amount = ((COALESCE((SELECT SUM(CO.cmd) FROM consumption AS CO WHERE CO.consumptionestimatedid = CE.id), 0))/1000), "
                . "days = (SELECT COUNT(CO.cmd) FROM consumption AS CO WHERE CO.consumptionestimatedid = CE.id), "
                . "statusdate = NOW() \n"
                . "WHERE registernumber = '" . $registerNumber . "'; \n";
        //Confirm operation
        $sql .= "SELECT id \n"
                . "FROM consumptionestimated \n"
                . "WHERE registernumber = '" . $registerNumber . "' \n"
                . "LIMIT 1; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a estimativa de consumo."));
        }
        return json_encode(array("status" => 1, "msg" => "Estimativa de consumo guardada com sucesso"));
    }

    public function consumptionEstimatedGetList($arrayFilter) {

        $companyId = $this->real_escape_string($arrayFilter['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilter['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilter['filterByDate']);
        $estimatedDate = $this->real_escape_string($arrayFilter['estimatedDate']);
        $consumertype = $this->real_escape_string($arrayFilter['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilter['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilter['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilter['customerid']);
        $userId = $this->real_escape_string($arrayFilter['userId']);
        $onlynumber = $this->real_escape_string($arrayFilter['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilter['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilter['endIdx']);

        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND CE.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND CE.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  (CAST(CE.estimateddate1 AS DATE) = CAST('" . $estimatedDate . "' AS DATE)) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = " LIMIT " . ($startIdx ) . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "CE.id, CE.estimateddate1, CE.amount, CE.days, CE.cmd, "
                . "CE.estimatedstatus, CE.statusdate, CE.statususer, \n"
                . "CT.contractid, CT.cil, CT.consumertype, C.companyname, CT.customerid, \n"
                . "CT.billingneiborhood, C.telephone1, C.telephone2, C.telephone3, "
                . "CT.contractstatus, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CE.statususer LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality ";
        if ($onlynumber == 1) {
            $fields = " COUNT(CE.id)  ";
            $limit = "";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM consumptionestimated AS CE, contract AS CT, customer AS C \n"
                . "WHERE CE.contractid = CT.contractid AND CE.customerid = C.customerid AND CT.customerid = C.customerid AND "
                . "CE.companyid = '" . $companyId . "'   " . $where
                . $limit
                . "; "; //ORDER BY  CE.estimateddate1 DESC, C.companyname

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function consumptionEstimatedGetDetails($companyId, $estimatedId) {

        $companyId = $this->real_escape_string($companyId);
        $estimatedId = $this->real_escape_string($estimatedId);


        $sql = "SELECT CE.id, CE.estimateddate1, CE.amount, CE.days, CE.cmd, CE.estimatedstatus, "
                . "CT.contractid, CT.cil, CT.customerid, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "CE.entryuser, CE.entrydate, CE.statususer, CE.statusdate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CE.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CE.statususer LIMIT 1) AS operatorName \n"
                . "FROM consumptionestimated AS CE, contract AS CT, customer AS C \n"
                . "WHERE CE.contractid = CT.contractid AND CE.customerid = C.customerid AND "
                . "CT.customerid = C.customerid AND CE.companyid = '" . $companyId . "' AND CE.id = '" . $estimatedId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function consumptionEstimatedHistoricList($companyId, $contractid) {

        $companyId = $this->real_escape_string($companyId);
        $contractid = $this->real_escape_string($contractid);

        $sql = "SELECT * \n"
                . "FROM consumptionestimated AS CE  \n"
                . "WHERE CE.companyid = '" . $companyId . "' AND CE.contractid = '" . $contractid . "'   \n"
                . "ORDER BY estimateddate1 DESC, amount DESC  \n"
                . "LIMIT 14;";
        $result = $this->query($sql);

        $arrayCustomer = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayCustomer, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayCustomer;
    }

    public function consumptionEstimatedSave($arrayConsumptionEstimatedInfo) {

        $companyid = $this->real_escape_string($arrayConsumptionEstimatedInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayConsumptionEstimatedInfo['dependencyid']);
        $estimatedid = $this->real_escape_string($arrayConsumptionEstimatedInfo['estimatedid']);
        $contractid = $this->real_escape_string($arrayConsumptionEstimatedInfo['contractid']);
        $customerid = $this->real_escape_string($arrayConsumptionEstimatedInfo['customerid']);
        $estimateddate1 = $this->real_escape_string($arrayConsumptionEstimatedInfo['estimateddate1']);
        $estimateddate2 = $this->real_escape_string($arrayConsumptionEstimatedInfo['estimateddate2']);
        $amount = $this->real_escape_string($arrayConsumptionEstimatedInfo['amount']);
        $days = $this->real_escape_string($arrayConsumptionEstimatedInfo['days']);
        $cmd = $this->real_escape_string($arrayConsumptionEstimatedInfo['cmd']);
        $statususer = $this->real_escape_string($arrayConsumptionEstimatedInfo['statususer']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $sql = "";
        //Save estimated without amount
        if (!is_numeric($estimatedid)) {
            $sql .= "INSERT INTO consumptionestimated (\n"
                    . "companyid, dependencyid, contractid, customerid, \n"
                    . "estimateddate1, estimateddate2, amount, days, cmd, \n"
                    . "estimatedstatus, entryuser, statususer, registernumber) \n"
                    . "SELECT CT.companyid, CT.dependencyid, CT.contractid, CT.customerid, "
                    . "'" . $estimateddate1 . "', '" . $estimateddate2 . "', '" . $amount . "', '" . $days . "', " . $cmd . ", \n"
                    . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "'  \n"
                    . "FROM contract AS CT \n"
                    . "WHERE CT.companyid = '" . $companyid . "' AND CT.contractid = '" . $contractid . "' AND "
                    . "NOT EXISTS(SELECT id FROM consumptionestimated AS CE WHERE CE.companyid = CT.companyid AND "
                    . "CE.contractid = CT.contractid AND CAST(CE.estimateddate1 AS DATE) = CAST('" . $estimateddate1 . "' AS DATE) ) \n"
                    . "LIMIT 1; \n";

            $estimatedid = "(SELECT CE.id FROM consumptionestimated AS CE "
                    . "WHERE CE.registernumber = '" . $registerNumber . "' LIMIT 1)";
        } else {
            $estimatedid = "'" . $estimatedid . "'";
        }

        //Save consumption
        $currentConsuption = 0;
        for ($index = 0; $index < $days; $index++) {
            $currentConsuption += $cmd;
            $currYear = (int) date("Y", strtotime($estimateddate1 . "+ " . $index . " days"));
            $currMonth = (int) date("n", strtotime($estimateddate1 . "+ " . $index . " days"));
            $currDay = (int) date("d", strtotime($estimateddate1 . "+ " . $index . " days"));
            $currQuarter = monthQuarter($currDay);

            $nextYear = (int) date("Y", strtotime($estimateddate1 . "+ " . ($index + 1) . " days"));
            $nextMonth = (int) date("n", strtotime($estimateddate1 . "+ " . ($index + 1) . " days"));
            $nextDay = (int) date("d", strtotime($estimateddate1 . "+ " . ($index + 1) . " days"));
            $nextQuarter = monthQuarter($nextDay);

            $entryData = false;
            if ($index == $days) {
                $entryData = true;
            } else {
                if ($currYear != $nextYear) {
                    $entryData = true;
                } else {
                    if ($currMonth != $nextMonth) {
                        $entryData = true;
                    } else {
                        if ($currQuarter != $nextQuarter) {
                            $entryData = true;
                        }
                    }
                }
            }

            if ($entryData) {
                //Most important is company, contract and consumption date
                //Insert if new
                $consumptiondate = date("Y-m-d", strtotime($currYear . "-" . $currMonth . "-01"));
                $sql .= "INSERT INTO consumption (\n"
                        . "companyid, dependencyid, contractid, customerid, \n"
                        . "consumptionestimatedid, consumptiondate, monthquater, cmd, \n"
                        . "consumptionstatus, statususer, entryuser, registernumber) \n"
                        . "SELECT CT.companyid, CT.dependencyid, CT.contractid, CT.customerid, \n"
                        . $estimatedid . ", '" . $consumptiondate . "', '" . $currQuarter . "', '" . $currentConsuption . "', \n"
                        . "'1', '" . $statususer . "', '" . $statususer . "', '" . $registerNumber . "' \n"
                        . "FROM contract AS CT \n"
                        . "WHERE CT.companyid = '" . $companyid . "' AND CT.contractid = '" . $contractid . "' AND \n"
                        . "(CAST(CT.contractdate AS DATE) <= CAST('" . $consumptiondate . "' AS DATE))  AND \n"
                        . "NOT EXISTS (SELECT CO.id FROM consumption AS CO WHERE CO.companyid = CT.companyid AND   "
                        . "CO.contractid = CT.contractid AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "'); \n";

                //Update if exists
                $sql .= "UPDATE consumption SET \n"
                        . "consumptionestimatedid = " . $estimatedid . ", "
                        . "cmd = '" . $currentConsuption . "', "
                        . "statususer = '" . $statususer . "', "
                        . "statusdate = NOW() \n"
                        . "WHERE companyid = '" . $companyid . "' AND   "
                        . "contractid = '" . $contractid . "' AND consumptiondate = '" . $consumptiondate . "' AND monthquater = '" . $currQuarter . "' AND "
                        . "hydrometerid = ''; \n";

                /*                $sql .= "UPDATE consumption SET \n"
                  . "cmd = '" . $cmd . "', "
                  . "statususer = '" . $statususer . "', "
                  . "statusdate = NOW() \n"
                  . "WHERE consumptionestimatedid = " . $estimatedid . "; "; */

                $currentConsuption = 0;
            }
        }

        //Update amount and days of estimated
        $sql .= "UPDATE consumptionestimated AS CE SET \n"
                . "amount = COALESCE((SELECT SUM(cmd)/1000 FROM consumption WHERE consumptionestimatedid = " . $estimatedid . "), 0), "
                . "cmd = '" . $cmd . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $estimatedid . "; \n";

        //Confirm operation
        $sql .= "SELECT id \n"
                . "FROM consumptionestimated \n"
                . "WHERE id = " . $estimatedid . " \n"
                . "LIMIT 1; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a estimativa de consumo."));
        }
        return json_encode(array("status" => 1, "msg" => "Estimativa de consumo guardada com sucesso"));
    }

    public function consumptionEstimatedCancel($estimatedId, $userId) {

        $estimatedId = $this->real_escape_string($estimatedId);
        $userId = $this->real_escape_string($userId);


        $sql = "DELETE FROM consumptionestimated  \n"
                . "WHERE id = '" . $estimatedId . "'; \n";

        $sql .= "DELETE FROM consumption \n"
                . "WHERE consumptionestimatedid = '" . $estimatedId . "'; \n";

        $sql .= "SELECT id \n"
                . "FROM consumptionestimated \n"
                . "WHERE id = '" . $estimatedId . "'; \n";

        $saved = true;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = false;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível apagar esta estimativa."));
        }
        return json_encode(array("status" => 1, "msg" => "Estimativa apagada com sucesso."));
    }

    public function consumptionEstimatedDefaultList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);

        $where = "";

        if ($dependencyId != -1) {
            $sql = "INSERT INTO consumptionestimateddefault (\n"
                    . "companyid, dependencyid, consumertype) \n"
                    . "SELECT DISTINCT '" . $companyId . "', '" . $dependencyId . "', consumertype \n"
                    . "FROM watertax AS W \n"
                    . "WHERE NOT EXISTS(SELECT id FROM consumptionestimateddefault AS CED WHERE \n"
                    . "companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "' AND CED.consumertype = W.consumertype); \n";

            $this->query($sql);

            $where = " AND CED.dependencyid = '" . $dependencyId . "' ";
        }

        $sql = "SELECT CED.id, CED.companyid, CED.dependencyid, CD.designation, CED.consumertype, CED.consumption \n"
                . "FROM consumptionestimateddefault AS CED, companydependency AS CD \n"
                . "WHERE CED.companyid = '" . $companyId . "' " . $where . " AND \n"
                . "CED.dependencyid = CD.id \n"
                . "ORDER BY dependencyid, consumertype; \n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function consumptionEstimatedDefaultSave($arConsumotionDefaults) {

        $registerNumber = $this->getRandomString(10) . round(microtime(true) * 1000);
        $sql = "";
        foreach ($arConsumotionDefaults as $key => $estimated) {
            $companyid = $this->real_escape_string($estimated['companyid']);
            $dependencyid = $this->real_escape_string($estimated['dependencyid']);
            $consumertype = $this->real_escape_string($estimated['consumertype']);
            $consumption = $this->real_escape_string($estimated['consumption']);

            $sql .= "UPDATE consumptionestimateddefault SET \n"
                    . "consumption = '" . $consumption . "', "
                    . "registernumber = '" . $registerNumber . "' \n"
                    . "WHERE companyid = '" . $companyid . "' AND dependencyid = '" . $dependencyid . "' AND consumertype = '" . $consumertype . "'; \n";
        }

        $sql .= "SELECT registernumber \n"
                . "FROM consumptionestimateddefault \n"
                . "WHERE registernumber = '" . $registerNumber . "'; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar alteração."));
        }
        return json_encode(array("status" => 1, "msg" => "Alteração guardada com sucesso"));
    }

    public function saleConsumptionGetContractsNumber($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $contractid = $this->real_escape_string($arrayFilterInfo['contractid']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $estimateddate1 = $this->real_escape_string($arrayFilterInfo['estimateddate1']);
        $estimateddate2 = $this->real_escape_string($arrayFilterInfo['estimateddate2']);

        $where = "";
        if ($contractid != -1) {
            $where .= " AND CT.contractid = '" . $contractid . "' ";
        } else {
            if ($dependencyId != -1) {
                $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
            }
            if ($consumertype != -1) {
                $where .= " AND CT.consumertype = '" . $consumertype . "' ";
            }
            if ($municipalityId != -1) {
                $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
            }
            if ($neiborhood != "") {
                $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
            }
        }


        $sql = "SELECT COUNT(CT.id) AS contracts \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.companyid = '" . $companyId . "' " . $where . " AND CT.contractstatus = 2 AND \n"
                . "CAST(CT.contractdate AS DATE) < CAST('" . $estimateddate2 . "' AS DATE) "
                . "AND NOT EXISTS (SELECT id FROM invoice AS I "
                . "WHERE I.companyid = CT.companyid AND I.contractid = CT.contractid AND I.consumptioninvoice = 1 AND CAST(I.billingstartdate AS DATE) = CAST('" . $estimateddate1 . "' AS DATE) AND UPPER(I.invoicestatus) != 'A') ;";

        $result = $this->query($sql);
        $contracts = 0;
        while ($row = mysqli_fetch_array($result)) {
            $contracts = $row['contracts'];
        }


        $sql = "SELECT COUNT(CT.id) AS contracts \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.companyid = '" . $companyId . "' " . $where . " AND CT.contractstatus = 2 AND \n"
                . "CAST(CT.contractdate AS DATE) < CAST('" . $estimateddate2 . "' AS DATE) "
                . "AND NOT EXISTS (SELECT id FROM invoice AS I "
                . "WHERE I.companyid = CT.companyid AND I.contractid = CT.contractid AND I.consumptioninvoice = 1 AND CAST(I.billingstartdate AS DATE) = CAST('" . $estimateddate1 . "' AS DATE) AND UPPER(I.invoicestatus) != 'A') AND "
                . "CT.contractid IN "
                . "(SELECT DISTINCT CO.contractid "
                . "FROM consumption AS CO "
                . "WHERE CAST(CO.consumptiondate AS DATE) BETWEEN CAST('" . $estimateddate1 . "' AS DATE) AND CAST('" . $estimateddate2 . "' AS DATE) );";

        $resultCO = $this->query($sql);
        $contractsCO = 0;
        while ($row = mysqli_fetch_array($resultCO)) {
            $contractsCO = $row['contracts'];
        }
        mysqli_free_result($result);
        mysqli_free_result($resultCO);

        return json_encode(array("co" => $contractsCO, "ct" => $contracts));
    }

    public function saleConsumptionContractList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $contractid = $this->real_escape_string($arrayFilterInfo['contractid']);
        $consumertype = $this->real_escape_string($arrayFilterInfo['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilterInfo['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilterInfo['neiborhood']);
        $billingdate1 = $this->real_escape_string($arrayFilterInfo['billingdate1']);
        $billingdate2 = $this->real_escape_string($arrayFilterInfo['billingdate2']);

        //contracts and consumptions amount
        $where = "";
        if ($contractid != -1) {
            $where .= " AND CT.contractid = '" . $contractid . "' ";
        } else {
            if ($dependencyId != -1) {
                $where .= " AND CT.dependencyid = '" . $dependencyId . "' ";
            }
            if ($consumertype != -1) {
                $where .= " AND CT.consumertype = '" . $consumertype . "' ";
            }
            if ($municipalityId != -1) {
                $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
            }
            if ($neiborhood != "") {
                $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
            }
        }
        $fldHydrometer = "(SELECT H.hydrometerid FROM hydrometer AS H WHERE H.contractid = CT.contractid AND H.companyid = CT.companyid AND H.hydrometerstatus = 1 ORDER BY H.id DESC LIMIT 1) \n";
        $sql = "SELECT CT.dependencyid, CT.contractid, CT.cil, CT.customerid, CT.consumertype, \n"
                . "C.customertype, C.companyname, C.customertaxid, \n"
                . "CT.billingmunicipality, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, \n"
                . "CT.billingblock, CT.billingbuildingnumber, CT.billingpostalcode, \n"
                . "CT.telephone1, CT.telephone2, CT.telephone3, CT.email, \n"
                . "CT.entryuser, CT.entrydate, CT.statususer, CT.statusdate, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS municipality, \n"
                . "(COALESCE(" . $fldHydrometer . ", '')) AS hydrometerid, \n"
                . "(COALESCE((SELECT SUM(CO.cmd) FROM consumption AS CO WHERE CO.companyid = CT.companyid AND CO.contractid = CT.contractid AND (CAST(CO.consumptiondate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) ) ), 0)/1000) AS consumptionamount \n"
                . "FROM contract AS CT, customer AS C \n"
                . "WHERE CT.customerid = C.customerid AND CT.companyid = '" . $companyId . "' AND CT.contractstatus = 2 " . $where
                . "AND NOT EXISTS (SELECT id FROM invoice AS I WHERE I.companyid = CT.companyid AND I.contractid = CT.contractid AND I.consumptioninvoice = 1 AND CAST(I.billingstartdate AS DATE) = CAST('" . $billingdate1 . "' AS DATE) AND UPPER(I.invoicestatus) != 'A') \n"
                . "AND CAST(CT.contractdate AS DATE) < CAST('" . $billingdate2 . "' AS DATE)   \n"
                . "ORDER BY C.companyname, CT.contractid; \n";
        /* AND  "
          . "CT.contractid IN "
          . "(SELECT DISTINCT CO.contractid "
          . "FROM consumption AS CO "
          . "WHERE CAST(CO.consumptiondate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) ) */

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function saleConsumptionLastRecords($companyId, $contractid, $billingdate1) {
        $companyId = $this->real_escape_string($companyId);
        $contractid = $this->real_escape_string($contractid);
        $billingdate1 = $this->real_escape_string($billingdate1);
        //DATE_ADD('" . $billingdate1 . "', INTERVAL " . $index . " DAY)
        $sql = "SELECT HR.recorddate, HR.recordvalue, \n"
                . "(DATE_ADD('" . $billingdate1 . "', INTERVAL -1 DAY)) AS beforedate, \n"
                . "(COALESCE((SELECT SUM(CO.cmd) FROM consumption AS CO WHERE CO.companyid = HR.companyid AND CO.contractid = HR.contractid AND CAST(CO.consumptiondate AS DATE) BETWEEN DATE_ADD(HR.recorddate, INTERVAL 1 DAY) AND DATE_ADD('" . $billingdate1 . "', INTERVAL -1 DAY) ), 0)/1000) AS lastconsumption \n"
                . "FROM hydrometerrecord AS HR \n"
                . "WHERE HR.companyid = '" . $companyId . "' AND HR.contractid = '" . $contractid . "' AND  \n"
                . "CAST(HR.recorddate AS DATE) < CAST('" . $billingdate1 . "' AS DATE) \n"
                . "ORDER BY recorddate DESC \n"
                . "LIMIT 1";

        $result = $this->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            return $row;
        }
        mysqli_free_result($result);
        return array("recorddate" => "", "recordvalue" => 0, "lastconsumption" => 0);
    }

    public function saleConsumptionWaterTax($consumerType, $consumptionAmount) {
        $where = "";
        if ($consumerType <= 1) {
            $where = " AND ('" . $consumptionAmount . "' BETWEEN consumptionminimum AND consumptionmaximum ) ";
        }

        $sql = "SELECT * \n"
                . "FROM watertax \n"
                . "WHERE consumertype = '" . $consumerType . "' " . $where
                . "ORDER BY consumertype, taxlevel ; ";
        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
    }

    public function saleConsumptionProcessInvoice($arrayContractInfo, $arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $billingdate1 = $this->real_escape_string($arrayFilterInfo['billingdate1']);
        $billingdate2 = $this->real_escape_string($arrayFilterInfo['billingdate2']);
        $userId = $this->real_escape_string($arrayFilterInfo['statususer']);
        $invoiceSerie = $this->real_escape_string($arrayFilterInfo['invoiceSerie']);
        $taxGroup = $this->real_escape_string($arrayFilterInfo['taxGroup']);
        $year = $this->real_escape_string($arrayFilterInfo['year']);
        $month = $this->real_escape_string($arrayFilterInfo['month']);

        $customerid = $this->real_escape_string($arrayContractInfo['customerid']);
        $contractid = $this->real_escape_string($arrayContractInfo['contractid']);
        $cil = $this->real_escape_string($arrayContractInfo['cil']);

        $companyname = $this->real_escape_string($arrayContractInfo['companyname']);
        $customertaxid = $this->real_escape_string($arrayContractInfo['customertaxid']);
        $municipality = $this->real_escape_string($arrayContractInfo['municipality']);
        $billingcomuna = $this->real_escape_string($arrayContractInfo['billingcomuna']);
        $billingneiborhood = $this->real_escape_string($arrayContractInfo['billingneiborhood']);
        $billingstreetname = $this->real_escape_string($arrayContractInfo['billingstreetname']);
        $billingblock = $this->real_escape_string($arrayContractInfo['billingblock']);
        $billingbuildingnumber = $this->real_escape_string($arrayContractInfo['billingbuildingnumber']);
        $telephone1 = $this->real_escape_string($arrayContractInfo['telephone1']);
        $telephone2 = $this->real_escape_string($arrayContractInfo['telephone2']);
        $telephone3 = $this->real_escape_string($arrayContractInfo['telephone3']);
        $address = "";
        if ($billingstreetname != "") {
            $address .= "Rua " . $billingstreetname;
        }
        if ($billingbuildingnumber != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= $billingbuildingnumber;
        }
        if ($address != "") {
            $address .= ", ";
        }
        $address .= "Bairro " . $billingneiborhood;
        if ($billingblock != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= $billingblock;
        }
        if ($billingcomuna != "") {
            if ($address != "") {
                $address .= ", ";
            }
            $address .= "Comuna " . $billingcomuna;
        }
        $customerphone = $telephone1;
        if ($telephone2 != "") {
            if ($customerphone != "") {
                $customerphone .= ", ";
            }
            $customerphone .= $telephone2;
        }
        if ($telephone3 != "") {
            if ($customerphone != "") {
                $customerphone .= ", ";
            }
            $customerphone .= $telephone3;
        }

        $hydrometerid = $this->real_escape_string($arrayContractInfo['hydrometerid']);
        $consumertype = $this->real_escape_string($arrayContractInfo['consumertype']);
        $consumptionamount = ceil($this->real_escape_string($arrayContractInfo['consumptionamount']));

        $hydrometerrecorddate = $billingdate2;
        $hydrometerrecordvalue = "";
        $hydrometerbeforerecorddate = $billingdate1;
        $hydrometerbeforerecordvalue = "";
        if ($hydrometerid != "") {
            $lastRecord = $this->saleConsumptionLastRecords($companyId, $contractid, $billingdate1);
            if ($lastRecord['recorddate'] != "") {
                $hydrometerbeforerecorddate = $lastRecord['beforedate'];
                $hydrometerbeforerecordvalue = $lastRecord['recordvalue'] + $lastRecord['lastconsumption'];
                $hydrometerrecorddate = $billingdate2;
                $hydrometerrecordvalue = $hydrometerbeforerecordvalue + $consumptionamount;
            }
        }

        $arrayTaxs = $this->saleConsumptionWaterTax($consumertype, $consumptionamount);
        $iva = $arrayTaxs['iva'];
        if ($taxGroup <= 1) {
            $iva = 0;
        }
        $taxfixed = number_format($arrayTaxs['taxfixed'], 2, '.', '');
        $taxfixedIva = $taxfixed * ($iva / 100);
        $taxvariable = $arrayTaxs['taxvariable'];
        $taxvariableTotal = number_format($taxvariable * $consumptionamount, 2, '.', '');
        $taxvariableIVA = $taxvariableTotal * ($iva / 100);
        if (($taxfixed <= 0) && ($taxvariableTotal <= 0)) {
            return json_encode(array("status" => 1, "pn" => "", "in" => "Sem consumo", "co" => $consumptionamount,
                "ftax" => $taxfixed, "vtax" => $taxvariableTotal, "iva" => $taxfixed + $taxvariableTotal));
        }

        //invoice
        $invoiceType = "FT";
        $shelflifedays = $this->real_escape_string($arrayFilterInfo['shelflife']);
        $shelflife = Date('Y-m-d', strtotime('+' . $shelflifedays . ' days'));

        $totalItems = 2;
        $subtotalUnitPrice = number_format($taxfixed + $taxvariableTotal, 2, '.', '');
        $subtotalIva = number_format($taxfixedIva + $taxvariableIVA, 2, '.', '');
        $subtotalIvaWithoutDescount = $subtotalIva;
        $totalInvoice = $subtotalUnitPrice + $subtotalIva;
        $grosstotal = number_format($totalInvoice, 2, '.', '');

        $sellerUser = $userId;
        $invoiceEntryDate = date_create(date("Y-m-d H:i:s"));
        $printnumber = $invoiceType . $this->getRandomString(10) . round(microtime(true) * 1000);


        $invoiceTable = "invoice";
        $invoiceLineTable = "invoiceline";

        $arrayHash = $this->invoiceSetHash($invoiceType, $invoiceSerie, $invoiceEntryDate, $grosstotal, $invoiceTable);
        $invoiceSequence = $arrayHash['invoiceSequence'];
        $invoiceNumber = $arrayHash['invoiceNumber'];
        $hash = $arrayHash['hash'];
        $hashControl = $arrayHash['hashControl'];
        $invoiceEntryDate = date_format($invoiceEntryDate, "Y-m-d\TH:i:s");

        $fieldsShipTo = ", 'Domicílio', '" . $invoiceEntryDate . "', CT.billingcountry, CT.billingprovince, CT.billingmunicipality, "
                . "CT.billingcity, CT.billingdistrict, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, CT.billingbuildingnumber, "
                . "CT.billingpostalcode \n";
        $fieldShipFrom = ", 'Loja', '" . $invoiceEntryDate . "', CY.billingcountry, CY.billingprovince, CY.billingmunicipality, "
                . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                . "CY.billingpostalcode \n";

        $sql = "INSERT INTO " . $invoiceTable . " (companyid, dependencyid, invoicetype, invoiceserie, "
                . "invoicesequence, invoicenumber, invoicestatus, invoiceStatusDate, Sourceidstatus, "
                . "Sourcebilling, hash, hashcontrol, invoicedate, sourceid, systementrydate, "
                . "customerid, contractid, grosstotal, printnumber, \n"
                . "deliveryIdShipto, deliverydateshipto, countryshipto, provinceshipto, municipalityshipto, "
                . "cityshipto, districtshipto, comunashipto, neiborhoodshipto, streetnameshipto, buildingnumbershipto, "
                . "postalcodeshipto,  \n"
                . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                . "postalCodeshipfrom, \n"
                . "customername, customertaxid, customercountry, customercity, "
                . "customerfulladdress, customerphone, customerpostalcode) \n"
                . "SELECT '" . $companyId . "', CT.dependencyid, '" . $invoiceType . "', '" . $invoiceSerie . "', \n"
                . "'" . $invoiceSequence . "', '" . $invoiceNumber . "', 'N', '" . $invoiceEntryDate . "', '" . $userId . "', \n"
                . "'P', '" . $hash . "', '" . $hashControl . "', '" . $invoiceEntryDate . "', '" . $userId . "', '" . $invoiceEntryDate . "', \n"
                . "'" . $customerid . "', '" . $contractid . "', '" . $grosstotal . "', '" . $printnumber . "' \n"
                . $fieldsShipTo . $fieldShipFrom
                . ", '" . $companyname . "', '" . $customertaxid . "', 1, '" . $municipality . "', "
                . "'" . $address . "', '" . $customerphone . "', CT.billingpostalcode  \n"
                . "FROM company AS CY , contract AS CT  \n"
                . "WHERE CY.companyid = '" . $companyId . "' AND CT.companyid = '" . $companyId . "' AND CT.contractid = '" . $contractid . "' \n"
                . "LIMIT 1; \n";

        $sql .= "UPDATE " . $invoiceTable . " SET \n"
                . "consumptioninvoice = 1, "
                . "cil = '" . $cil . "', "
                . "hydrometerid = '" . $hydrometerid . "', "
                . "watertaxlevel = '" . $arrayTaxs['taxlevel'] . "', "
                . "watertaxdescription = '" . $arrayTaxs['taxdesignation'] . "', "
                . "billingstartdate = '" . $billingdate1 . "', "
                . "billingenddate = '" . $billingdate2 . "', "
                . "hydrometerrecorddate = '" . $hydrometerrecorddate . "', "
                . "hydrometerrecordvalue = '" . $hydrometerrecordvalue . "', "
                . "consumptionamount = '" . $consumptionamount . "', "
                . "hydrometerbeforerecorddate = '" . $hydrometerbeforerecorddate . "', "
                . "hydrometerbeforerecordvalue = '" . $hydrometerbeforerecordvalue . "', "
                . "shelflife = '" . $shelflife . "', "
                . "totalItems = '" . $totalItems . "', "
                . "subtotalUnitPrice = '" . $subtotalUnitPrice . "', "
                . "subtotalIva = '" . $subtotalIva . "', "
                . "subtotalIvaWithoutDescount = '" . $subtotalIvaWithoutDescount . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "taxGroup = '" . $taxGroup . "', "
                . "sellerUser = '" . $sellerUser . "'  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        $sql .= "INSERT INTO customeraccount ("
                . "companyid, customerid, contractid, source, reason, amount) \n"
                . "SELECT '" . $companyId . "', '" . $customerid . "', '" . $contractid . "', \n"
                . "'" . $invoiceNumber . "', 'Factura', '-" . $totalInvoice . "' \n"
                . "FROM insert_table; \n";

        /*   LINES */
        for ($i = 0; $i < 2; $i++) {
            $productID = $companyId . $year . $month . "01";
            $productType = "S";
            $productDescription = $arrayTaxs['taxdesignation'] . " (" . monthName($month) . "/" . $year . ")";
            $productBarCode = "";
            $productUnit = "M3";
            $productSection = 1;
            $productIvaCategory = 18;
            $productStock = 0;
            $productWeight = 0;
            $productNetWeight = 0;
            $devolutionDate = Date('Y-m-d', strtotime('+5 days'));
            $productStockActual = 0;

            $periodicService = 0;
            $periodicServiceDateBefore = Date('Y-m-d');
            $periodicServiceDate = Date('Y-m-d');

            $quant = $consumptionamount;
            $warehousePrice = $taxvariable;
            $indirectCost = 0;
            $commercialCost = 0;
            $estimatedProfit = 0;
            $sellerComission = 0;
            $managerComission = 0;
            $fundInvestment = 0;
            $fundReserve = 0;
            $fundSocialaction = 0;

            $priceWithComission = $warehousePrice;
            $creditAmount = number_format($priceWithComission * $quant, 2, '.', '');
            $descount = 0;
            //$iva = $iva;
            $ivaValue = $taxvariableIVA;
            $ivaWithoutDescountValue = $ivaValue;
            $subtotalLine = $taxvariableTotal + $taxvariableIVA;
            $exemptionCode = "";
            $exemptionReason = "";
            $note = 0;
            $gmelicense = 0;

            if ($i == 1) {
                $productID .= "1";
                $productDescription = "Quota fixa do serviço (" . monthName($month) . "/" . $year . ")";
                $productUnit = "UN";

                $quant = 1;
                $warehousePrice = $taxfixed;
                $priceWithComission = $warehousePrice;
                $creditAmount = number_format($priceWithComission * $quant, 2, '.', '');

                $ivaValue = $taxfixedIva;
                $ivaWithoutDescountValue = $ivaValue;
                $subtotalLine = $taxfixed + $taxfixedIva;
            }

            if ($iva == 0) {
                $exemptionCode = "M04";
                $exemptionReason = "IVA – Regime de exclusão";
            }

            $strInsertLine = "INSERT INTO " . $invoiceLineTable . " (invoiceNumber, customerid, contractid, productCode, productDescription, quantity, "
                    . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                    . "taxPointDate, description, creditAmount, taxPercentage, \n"
                    . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                    . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                    . "productWeight, productNetWeight, devolution, \n"
                    . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                    . "sellerComission, managerComission, \n"
                    . "fundInvestment, fundReserve, fundSocialaction, \n"
                    . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note, gmelicense) \n"
                    . "SELECT '" . $invoiceNumber . "', '" . $customerid . "', '" . $contractid . "', '" . $productID . "', '" . $productDescription . "', '" . $quant . "', \n"
                    . "'" . $productUnit . "', '" . $warehousePrice . "', '" . $indirectCost . "', '" . $commercialCost . "', '" . $estimatedProfit . "',  \n"
                    . "'" . $invoiceEntryDate . "', '" . $productDescription . "', '" . $creditAmount . "', '" . $iva . "', \n"
                    . "'" . $exemptionReason . "', '" . $exemptionCode . "', '" . $descount . "', \n"
                    . "'" . $productID . "', '" . $productType . "', '" . $productBarCode . "', '" . $productSection . "', '" . $productIvaCategory . "', '" . $productStock . "', \n"
                    . "'" . $productWeight . "', '" . $productNetWeight . "', '" . $devolutionDate . "', \n"
                    . "'" . $periodicService . "', '" . $periodicServiceDateBefore . "', '" . $periodicServiceDate . "', \n"
                    . "'" . $sellerComission . "', '" . $managerComission . "', \n"
                    . "'" . $fundInvestment . "', '" . $fundReserve . "', '" . $fundSocialaction . "', \n"
                    . "'" . $priceWithComission . "', '" . $ivaValue . "', '" . $ivaWithoutDescountValue . "', '" . $subtotalLine . "', '" . $note . "', '" . $gmelicense . "'  \n"
                    . "FROM insert_table \n";
            if (!$note) {
                $strInsertLine .= "WHERE NOT EXISTS (SELECT id FROM " . $invoiceLineTable . " WHERE invoiceNumber = '" . $invoiceNumber . "' AND productId = '" . $productID . "')";
            }
            $strInsertLine .= "; \n";

            $sql .= $strInsertLine;
        }

        /* PAYMENTS SCHEDULE  */
        $installment = $invoiceNumber;
        $installmentDate = $shelflife;
        $paymentAmount = $totalInvoice;

        $sql .= "INSERT INTO invoicepaymentschedule ("
                . "companyid, invoicenumber, customerid, contractid, installment, installmentdate, "
                . "paymentamount, installmentstatus)  \n"
                . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', "
                . "'" . $customerid . "', '" . $contractid . "', '" . $installment . "', '" . $installmentDate . "', "
                . "'" . $paymentAmount . "', 'AP'  \n"
                . "FROM insert_table ; \n";

        //update consumptions
        $sql .= "UPDATE consumption SET \n"
                . "consumptionstatus = 2, "
                . "statususer = '" . $userId . "', "
                . "statusdate = NOW() \n"
                . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                . "(CAST(consumptiondate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) ); ";
        //update hydrometer records
        $sql .= "UPDATE hydrometerrecord SET \n"
                . "recordstatus = 2, "
                . "statususer = '" . $userId . "', "
                . "statusdate = NOW() \n"
                . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                . "(CAST(recorddate AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) );";
        //update consumption estimated
        $sql .= "UPDATE consumptionestimated SET \n"
                . "estimatedstatus = 2, "
                . "statususer = '" . $userId . "', "
                . "statusdate = NOW() \n"
                . "WHERE companyid = '" . $companyId . "' AND contractid = '" . $contractid . "' AND "
                . "(CAST(estimateddate1 AS DATE) BETWEEN CAST('" . $billingdate1 . "' AS DATE) AND CAST('" . $billingdate2 . "' AS DATE) );";
        // confirm operations
        $sql .= "SELECT * \n"
                . "FROM " . $invoiceTable . "  \n"
                . "WHERE printnumber = '" . $printnumber . "';";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "in" => "Não foi processada."));
        }

        return json_encode(array("status" => 1, "pn" => $printnumber, "in" => $invoiceNumber, "co" => $consumptionamount,
            "ftax" => $taxfixed, "vtax" => $taxvariableTotal, "iva" => $subtotalIva));
    }

    public function saleConsumptionGetList($arrayFilter) {

        $companyId = $this->real_escape_string($arrayFilter['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilter['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilter['filterByDate']);
        $billingDate = $this->real_escape_string($arrayFilter['billingDate']);
        $consumertype = $this->real_escape_string($arrayFilter['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilter['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilter['neiborhood']);
        $customerid = $this->real_escape_string($arrayFilter['customerid']);
        $userId = $this->real_escape_string($arrayFilter['userId']);
        $onlynumber = $this->real_escape_string($arrayFilter['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilter['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilter['endIdx']);

        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $where .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $where .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $where .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($userId != -1) {
            $where .= " AND I.sourceid = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  (CAST(I.billingstartdate AS DATE) = CAST('" . $billingDate . "' AS DATE)) ";
        }
        if ($customerid != -1) {
            $where = " AND CT.customerid = '" . $customerid . "' ";
        } else {
            $limit = " LIMIT " . ($startIdx ) . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "I.id, I.billingstartdate, I.invoicenumber, I.printnumber, I.totalInvoice, \n"
                . "I.contractid, I.cil, CT.consumertype, C.companyname, CT.customerid, \n"
                . "CT.billingneiborhood, C.customertaxid, "
                . "C.telephone1, C.telephone2, C.telephone3, C.email, \n"
                . "I.invoicestatus, I.Sourceidstatus, I.invoiceStatusDate, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = I.Sourceidstatus LIMIT 1) AS operatorName, \n"
                . "(SELECT M.municipality FROM municipality AS M WHERE M.id = CT.billingmunicipality LIMIT 1) AS billingMunicipality \n";

        if ($onlynumber == 1) {
            $fields = " COUNT(I.id) ";
            $limit = "";
        }

        $sql = "SELECT " . $fields
                . "FROM invoice AS I, contract AS CT, customer AS C \n"
                . "WHERE I.contractid = CT.contractid AND I.customerid = C.customerid AND CT.customerid = C.customerid AND "
                . "I.consumptioninvoice = 1 AND I.companyid = '" . $companyId . "'   " . $where
                . $limit
                . "; ";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleConsumptionGroupHistoric($arrayFilter) {

        $contractid = $this->real_escape_string($arrayFilter['contractid']);

        $fields = "";

        $sql = "SELECT I.invoicenumber, I.billingstartdate, I.billingenddate, I.consumptionamount, I.totalInvoice, \n"
                . "I.invoicestatus, I.Sourceidstatus, I.invoiceStatusDate \n" . $fields
                . "FROM invoice AS I \n"
                . "WHERE I.contractid = '" . $contractid . "' AND consumptioninvoice = 1   \n"
                . "ORDER BY billingstartdate DESC, billingenddate DESC \n"
                . "LIMIT 14; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function saleInvoiceImportLote($arrayInvoiceInfo) {

        $companyId = $this->real_escape_string($arrayInvoiceInfo['companyid']);
        $userId = $this->real_escape_string($arrayInvoiceInfo['userId']);
        $customerid = $this->real_escape_string($arrayInvoiceInfo['customertid']);
        $companyname = $this->real_escape_string($arrayInvoiceInfo['companyname']);
        $invoiceNumber = $this->real_escape_string($arrayInvoiceInfo['invoiceNumber']);
        $invoicedate = $this->real_escape_string($arrayInvoiceInfo['invoicedate']);
        $totalInvoice = $this->real_escape_string($arrayInvoiceInfo['totalinvoice']);


        //invoice
        $invoiceType = "FT";
        $shelflifedays = $this->real_escape_string(0);
        $shelflife = Date('Y-m-d', strtotime('+' . $shelflifedays . ' days'));

        $totalItems = 1;
        $subtotalUnitPrice = $totalInvoice;
        $subtotalIva = 0;
        $subtotalIvaWithoutDescount = 0;
        $grosstotal = $totalInvoice;

        $sellerUser = $userId;
        $printnumber = $invoiceType . $this->getRandomString(10) . round(microtime(true) * 1000);


        $invoiceTable = "invoice";
        $invoiceLineTable = "invoiceline";

        $invoiceNumber = $invoiceType . " " . $invoiceNumber;
        $invoiceEntryDate = date_format(date_create(date("Y-m-d H:i:s")), "Y-m-d\TH:i:s");

        //Check invoice number
        $sql = "SELECT invoicenumber \n"
                . "FROM invoice \n"
                . "WHERE companyid = '" . $companyId . "' AND invoicenumber = '" . $invoiceNumber . "'; ";
        $checkInvoice = $this->query($sql);
        if ($checkInvoice->num_rows > 0) {
            mysqli_free_result($checkInvoice);
            echo json_encode(array("status" => 0, "msg" => "Factura já foi cadastrado."));
            return false;
        }

        //Check customer number
        $sql = "SELECT customerid \n"
                . "FROM customer \n"
                . "WHERE companyid = '" . $companyId . "' AND customerid = '" . $customerid . "'; ";
        $checkCustomer = $this->query($sql);
        if ($checkCustomer->num_rows <= 0) {
            mysqli_free_result($checkCustomer);
            echo json_encode(array("status" => 0, "msg" => "Cliente não encontrado."));
            return false;
        }

        //Check contract number
        $sql = "SELECT contractid \n"
                . "FROM contract \n"
                . "WHERE companyid = '" . $companyId . "' AND customerid = '" . $customerid . "'; ";
        $checkContract = $this->query($sql);
        if ($checkContract->num_rows <= 0) {
            mysqli_free_result($checkContract);
            echo json_encode(array("status" => 0, "msg" => "Contracto não encontrado."));
            return false;
        }


        $fieldsShipTo = ", 'Domicílio', '" . $invoiceEntryDate . "', CT.billingcountry, CT.billingprovince, CT.billingmunicipality, "
                . "CT.billingcity, CT.billingdistrict, CT.billingcomuna, CT.billingneiborhood, CT.billingstreetname, CT.billingbuildingnumber, "
                . "CT.billingpostalcode \n";
        $fieldShipFrom = ", 'Loja', '" . $invoiceEntryDate . "', CY.billingcountry, CY.billingprovince, CY.billingmunicipality, "
                . "CY.billingcity, CY.billingdistrict, CY.billingcomuna, CY.billingneiborhood, CY.billingstreetname, CY.billingbuildingnumber, "
                . "CY.billingpostalcode \n";

        $sql = "INSERT INTO " . $invoiceTable . " (companyid, dependencyid, invoicetype, "
                . "invoicenumber, invoicestatus, invoiceStatusDate, Sourceidstatus, "
                . "Sourcebilling, invoicedate, systementrydate, "
                . "customerid, contractid, grosstotal, printnumber, \n"
                . "deliveryIdShipto, deliverydateshipto, countryshipto, provinceshipto, municipalityshipto, "
                . "cityshipto, districtshipto, comunashipto, neiborhoodshipto, streetnameshipto, buildingnumbershipto, "
                . "postalcodeshipto,  \n"
                . "deliveryidshipfrom, deliverydateshipfrom, countryshipfrom, provinceshipfrom, municipalityshipfrom, "
                . "cityshipfrom, districtshipfrom, comunashipfrom, neiborhoodshipfrom, streetNameshipfrom, buildingnumbeshipfrom, "
                . "postalCodeshipfrom, \n"
                . "customername, customertaxid, customercountry, customercity, "
                . "customerfulladdress, customerphone, customerpostalcode) \n"
                . "SELECT '" . $companyId . "', CT.dependencyid, '" . $invoiceType . "', \n"
                . "'" . $invoiceNumber . "', 'N', '" . $invoiceEntryDate . "', '" . $userId . "', \n"
                . "'I', '" . $invoicedate . "', '" . $invoiceEntryDate . "', \n"
                . "'" . $customerid . "', CT.contractid, '" . $grosstotal . "', '" . $printnumber . "' \n"
                . $fieldsShipTo . $fieldShipFrom
                . ", '" . $companyname . "', C.customertaxid, 1, (COALESCE((SELECT M.municipality FROM municipality AS M WHERE M.id = C.billingmunicipality LIMIT 1), '')), "
                . "CT.billingneiborhood, C.telephone1, CT.billingpostalcode  \n"
                . "FROM company AS CY , contract AS CT, customer AS C  \n"
                . "WHERE CY.companyid = '" . $companyId . "' AND CT.companyid = '" . $companyId . "' AND C.customerid = '" . $customerid . "' AND \n"
                . "CT.customerid = C.customerid \n"
                . "LIMIT 1; \n";


        $sql .= "UPDATE " . $invoiceTable . " SET \n"
                . "shelflife = '" . $shelflife . "', "
                . "totalItems = '" . $totalItems . "', "
                . "subtotalUnitPrice = '" . $subtotalUnitPrice . "', "
                . "subtotalIva = '" . $subtotalIva . "', "
                . "subtotalIvaWithoutDescount = '" . $subtotalIvaWithoutDescount . "', "
                . "totalInvoice = '" . $totalInvoice . "', "
                . "taxGroup = '1', "
                . "sellerUser = '" . $sellerUser . "'  \n"
                . "WHERE invoicenumber = '" . $invoiceNumber . "'; \n";

        $sql .= "INSERT INTO customeraccount ("
                . "companyid, customerid, contractid, source, reason, amount) \n"
                . "SELECT '" . $companyId . "', '" . $customerid . "', CT.contractid, \n"
                . "'" . $invoiceNumber . "', 'Factura', '-" . $totalInvoice . "' \n"
                . "FROM contract AS CT  \n"
                . "WHERE CT.customerid = '" . $customerid . "' \n"
                . "LIMIT 1; \n";

        /*   LINES */
        $productID = $companyId . "01";
        $productType = "S";
        $productDescription = $invoiceNumber . " (Documento importado)";
        $productBarCode = "";
        $productUnit = "UN";
        $productSection = 1;
        $productIvaCategory = 18;
        $productStock = 0;
        $productWeight = 0;
        $productNetWeight = 0;
        $devolutionDate = Date('Y-m-d', strtotime('+5 days'));
        $productStockActual = 0;

        $periodicService = 0;
        $periodicServiceDateBefore = Date('Y-m-d');
        $periodicServiceDate = Date('Y-m-d');

        $quant = 1;
        $warehousePrice = $totalInvoice;
        $indirectCost = 0;
        $commercialCost = 0;
        $estimatedProfit = 0;
        $sellerComission = 0;
        $managerComission = 0;
        $fundInvestment = 0;
        $fundReserve = 0;
        $fundSocialaction = 0;

        $priceWithComission = $warehousePrice;
        $descount = 0;
        $iva = 0;
        $ivaValue = 0;
        $ivaWithoutDescountValue = $ivaValue;
        $subtotalLine = $totalInvoice;
        $exemptionCode = "";
        $exemptionReason = "";
        $note = 0;
        $gmelicense = 0;

        $strInsertLine = "INSERT INTO " . $invoiceLineTable . " (invoiceNumber, customerid, contractid, productCode, productDescription, quantity, "
                . "unitOfMeasure, warehousePrice, indirectCost, commercialCost, estimatedProfit, \n"
                . "taxPointDate, description, creditAmount, taxPercentage, \n"
                . "taxExemptionReason, taxExemptionCode, settlementAmount, \n"
                . "productId, productType, productBarCode, productSection, productIvaCategory, productStock, \n"
                . "productWeight, productNetWeight, devolution, \n"
                . "periodicService, periodicServiceDateBefore, periodicServiceDate, \n"
                . "sellerComission, managerComission, \n"
                . "fundInvestment, fundReserve, fundSocialaction, \n"
                . "priceWithComission, ivaValue, ivaWithoutDescountValue, subtotalLine, note, gmelicense) \n"
                . "SELECT '" . $invoiceNumber . "', '" . $customerid . "', '0', '" . $productID . "', '" . $productDescription . "', '" . $quant . "', \n"
                . "'" . $productUnit . "', '" . $warehousePrice . "', '" . $indirectCost . "', '" . $commercialCost . "', '" . $estimatedProfit . "',  \n"
                . "'" . $invoiceEntryDate . "', '" . $productDescription . "', '" . $priceWithComission . "', '" . $iva . "', \n"
                . "'" . $exemptionReason . "', '" . $exemptionCode . "', '" . $descount . "', \n"
                . "'" . $productID . "', '" . $productType . "', '" . $productBarCode . "', '" . $productSection . "', '" . $productIvaCategory . "', '" . $productStock . "', \n"
                . "'" . $productWeight . "', '" . $productNetWeight . "', '" . $devolutionDate . "', \n"
                . "'" . $periodicService . "', '" . $periodicServiceDateBefore . "', '" . $periodicServiceDate . "', \n"
                . "'" . $sellerComission . "', '" . $managerComission . "', \n"
                . "'" . $fundInvestment . "', '" . $fundReserve . "', '" . $fundSocialaction . "', \n"
                . "'" . $priceWithComission . "', '" . $ivaValue . "', '" . $ivaWithoutDescountValue . "', '" . $subtotalLine . "', '" . $note . "', '" . $gmelicense . "'  \n"
                . "FROM insert_table; \n";

        $sql .= $strInsertLine;


        /* PAYMENTS SCHEDULE  */
        $installment = $invoiceNumber;
        $installmentDate = $shelflife;
        $paymentAmount = $totalInvoice;

        $sql .= "INSERT INTO invoicepaymentschedule ("
                . "companyid, invoicenumber, customerid, contractid, installment, installmentdate, "
                . "paymentamount, installmentstatus)  \n"
                . "SELECT '" . $companyId . "', '" . $invoiceNumber . "', "
                . "'" . $customerid . "', CT.contractid, '" . $installment . "', '" . $installmentDate . "', "
                . "'" . $paymentAmount . "', 'AP'  \n"
                . "FROM contract AS CT \n"
                . "WHERE CT.customerid = '" . $customerid . "' \n"
                . "LIMIT 1; \n";

        // confirm operations
        $sql .= "SELECT * \n"
                . "FROM " . $invoiceTable . "  \n"
                . "WHERE printnumber = '" . $printnumber . "';";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }
        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi processada."));
        }

        return json_encode(array("status" => 1, "msg" => "Factura processada com sucesso"));
    }

    public function saleConsumptionReport($arrayFilter) {

        $companyId = $this->real_escape_string($arrayFilter['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilter['dependencyId']);
        //$filterByDate = $this->real_escape_string($arrayFilter['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilter['initialDate']);
        $endDate = $this->real_escape_string($arrayFilter['endDate']);
        $consumertype = $this->real_escape_string($arrayFilter['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilter['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilter['neiborhood']);
        $fullReport = $this->real_escape_string($arrayFilter['fullReport']);

        $where = "";
        $whereHR = "";
        $whereCT = "";
        $wherePay = "";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
            $whereHR .= " AND HR.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $whereCT .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $whereCT .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $whereCT .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        if ($fullReport == 0) {
            $wherePay .= " AND (I.paymentamount < I.totalInvoice) ";
        }
        // if ($filterByDate == 1) {
        $where .= " AND  (CAST(I.invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
        $whereHR .= " AND  (CAST(HR.entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
        // }
        // . "(SELECT SUM(HR.amount) FROM hydrometerrecord AS HR WHERE HR.companyid = '" . $companyId . "' " . $whereHR . str_replace("xxxxxx", "HR", $whereCT) . " ) AS records, \n"
        //  . "(SELECT SUM(HR.amount) FROM consumptionestimated AS HR WHERE HR.companyid = '" . $companyId . "' " . $whereHR . str_replace("xxxxxx", "HR", $whereCT) . " ) AS estimateds, \n"

        $whereCT = " AND EXISTS (SELECT CT.id FROM contract AS CT WHERE CT.contractid = xxxxxx.contractid " . $whereCT . ") \n";

        $partSql = "SELECT 'yyyyyy' AS billingstartdate, "
                . "(COUNT(I.id)) AS nInvoices, \n"
                . "(SUM(I.consumptionamount)) AS consumptionamount, \n"
                . "(SUM(I.totalInvoice)) AS totalInvoice, \n"
                . "(SUM(I.paymentamount)) AS paymentamount \n"
                . "FROM invoice AS I  \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.consumptioninvoice = 1 AND UPPER(I.invoicestatus) != 'A' "
                . " AND CAST(billingstartdate AS DATE) = CAST('yyyyyy' AS DATE)  "
                . $where . $wherePay
                . str_replace("xxxxxx", "I", $whereCT)
                . " \n";

        $sql = "SELECT DISTINCT billingstartdate \n"
                . "FROM invoice AS I \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.consumptioninvoice = 1 AND UPPER(I.invoicestatus) != 'A'   " . $where
                . str_replace("xxxxxx", "I", $whereCT) . $wherePay
                . "ORDER BY billingstartdate DESC; ";

        $result = $this->query($sql);
        $n = 0;
        $sql = "";
        while ($row = mysqli_fetch_array($result)) {
            if ($n != 0) {
                $sql .= " UNION \n";
            }
            $sql .= str_replace("yyyyyy", $row['billingstartdate'], $partSql);
            $n += 1;
        }

        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    
    public function treasuryCashFlowListGet($companyId, $dependencyId,
            $initialDate, $endDate) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $initialDate = $this->real_escape_string($initialDate);
        $endDate = $this->real_escape_string($endDate);

        //RECEIPTS
        $where = " AND ( CAST(I.aaaaaa AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) ";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        //aaaaaa = invoicedate / transactiondate
        //bbbbbb = invoice / payment
        //cccccc = invoicestatus / paymentstatus
        $listOf = " (SELECT I.invoicenumber FROM bbbbbb AS I WHERE I.companyid = '" . $companyId . "' " . $where . " AND UPPER(I.cccccc) != 'A' ) ";
        $listOfI = str_replace('aaaaaa', "invoicedate", str_replace("bbbbbb", "invoice", str_replace("cccccc", "invoicestatus", $listOf)));
        $listOfP = str_replace('aaaaaa', "transactiondate", str_replace("bbbbbb", "payment", str_replace("cccccc", "paymentstatus", $listOf)));

        //dddddd = AND IP.PaymentMechanism IN (1, 100) / AND IP.accountBankTarget = BA.id
        $payAmount = " (COALESCE((SELECT SUM(IP.PaymentAmount) FROM invoicepayment AS IP WHERE  "
                . "(IP.invoicenumber IN (" . $listOfI . ") OR IP.invoicenumber IN (" . $listOfP . ")) "
                . " dddddd ), 0)) AS amount \n";

        $sql = "SELECT 1 AS forOrder, 'Numerário' AS rubric, \n"
                . str_replace("dddddd", " AND IP.PaymentMechanism IN (1, 100) ", $payAmount)
                . "FROM insert_table AS BA \n";
        $sql .= " UNION ";
        $sql .= "SELECT 1 AS forOrder, \n"
                . "(CONCAT((SELECT NB.initials FROM nationalbank AS NB WHERE NB.id = BA.nationalbankid LIMIT 1), ' - ', BA.accountnumber)) AS rubric, \n"
                . str_replace("dddddd", " AND IP.accountBankTarget = BA.id ", $payAmount)
                . "FROM bankaccount AS BA \n"
                . "WHERE BA.companyid = '" . $companyId . "' \n";
        $sql .= " UNION ";
        $sql .= "SELECT 1 AS forOrder, 'Outro' AS rubric, \n"
                . str_replace("dddddd", " AND IP.PaymentMechanism NOT IN (1, 100) AND IP.accountBankTarget <= 0 ", $payAmount)
                . "FROM insert_table AS BA \n";

        //PAYMENTS
        $where = " AND (CAST(TCO.cashoutdate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE) ) ";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }
        $listOfCO = "(SELECT TCO.id FROM treasurycashout AS TCO WHERE TCO.companyid = '" . $companyId . "' " . $where . " )";
        $cashOutAmount = "(COALESCE((SELECT SUM(TCOD.invoiceamount) FROM treasurycashoutdetail AS TCOD WHERE TCOD.rubricid = TR.id AND "
                . "TCOD.cashoutid IN " . $listOfCO . "), 0)) AS amount \n";
        //supplier
        $sql .= " UNION ";
        $sql .= "SELECT 4 AS forOrder, TR.description AS rubric, \n"
                . $cashOutAmount
                . "FROM treasuryrubric AS TR \n"
                . "WHERE TR.companyid = '" . $companyId . "' AND TR.costsubtype = 2 ";
        //Operational and commercial cost
        $sql .= " UNION ";
        $sql .= "SELECT 4 AS forOrder, TR.description AS rubric, \n"
                . $cashOutAmount
                . "FROM treasuryrubric AS TR \n"
                . "WHERE TR.companyid = '" . $companyId . "' AND TR.costtype IN (1, 2) AND TR.costsubtype != 1 ";
        //Human resource
        $sql .= " UNION ";
        $sql .= "SELECT 5 AS forOrder, TR.description AS rubric, \n"
                . $cashOutAmount
                . "FROM treasuryrubric AS TR \n"
                . "WHERE TR.companyid = '" . $companyId . "' AND TR.costsubtype IN(1, 4) ";
        //Taxs
        $sql .= " UNION ";
        $sql .= "SELECT 6 AS forOrder, TR.description AS rubric, \n"
                . $cashOutAmount
                . "FROM treasuryrubric AS TR \n"
                . "WHERE TR.companyid = '" . $companyId . "' AND TR.costsubtype = 3 ";
        //Funds
        $sql .= " UNION ";
        $sql .= "SELECT 7 AS forOrder, TR.description AS rubric, \n"
                . $cashOutAmount
                . "FROM treasuryrubric AS TR \n"
                . "WHERE TR.companyid = '" . $companyId . "' AND TR.costsubtype IN (5, 6, 7) ";

        $sql .= "ORDER BY forOrder, rubric; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function treasuryBillToReceiveList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $cunsumerType = $this->real_escape_string($arrayFilterInfo['cunsumerType']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = " LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }
        if ($cunsumerType != -1) {
            $where .= " AND I.watertaxlevel = '" . $cunsumerType . "' ";
        }
        $where .= " AND (CAST(I.invoicedate AS DATE) BETWEEN CAST('$initialDate' AS DATE) AND CAST('$endDate' AS DATE)) \n";


        $groupBy = "GROUP BY customerid, customertaxid, watertaxlevel, companyname \n";
        $orderBy = " companyname ";

        $fldAmount = " (I.paymentamount - I.realpaymentamount) ";
        $fldAmount = "(COALESCE((SELECT SUM(IPS.paymentamount - IPS.realpaymentamount) FROM invoicepaymentschedule AS IPS WHERE IPS.invoicenumber = I.invoicenumber   "
                . "AND ((IPS.paymentamount - IPS.realpaymentamount) > 0.01)), 0)) ";
        $fields = "I.customerid, I.customertaxid, I.watertaxlevel, \n"
                . "(SELECT C.companyname FROM customer AS C WHERE C.customerid = I.customerid LIMIT 1) AS companyname, \n"
                . "COALESCE(SUM($fldAmount), 0) AS totalamount ";

        if ($onlynumber == 1) {
            $fields = "  COUNT(DISTINCT I.customerid) AS x ";
            $groupBy = "";
            $orderBy = "1";
            $limit = "";
        }


        $sql = "SELECT " . $fields . " \n"
                . "FROM invoice AS I \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.invoicestatus != 'A' " . $where . " AND \n"
                . "(" . $fldAmount . " > 0) \n"
                . $groupBy
                . "ORDER BY " . $orderBy . " \n"
                . $limit
                . "; ";
                
                
        file_put_contents("sql.txt", $sql);

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)['x']);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    
    
    public function treasuryBillToReceiveServiceList($arrayFilter) {

        $companyId = $this->real_escape_string($arrayFilter['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilter['dependencyId']);
        $initialDate = $this->real_escape_string($arrayFilter['initialDate']);
        $endDate = $this->real_escape_string($arrayFilter['endDate']);
        $consumertype = $this->real_escape_string($arrayFilter['consumertype']);
        $municipalityId = $this->real_escape_string($arrayFilter['municipalityId']);
        $neiborhood = $this->real_escape_string($arrayFilter['neiborhood']);

        $where = "";
        $whereHR = "";
        $whereCT = "";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
            $whereHR .= " AND HR.dependencyid = '" . $dependencyId . "' ";
        }
        if ($consumertype != -1) {
            $whereCT .= " AND CT.consumertype = '" . $consumertype . "' ";
        }
        if ($municipalityId != -1) {
            $whereCT .= " AND CT.billingmunicipality = '" . $municipalityId . "' ";
        }
        if ($neiborhood != "") {
            $whereCT .= " AND CT.billingneiborhood = '" . $neiborhood . "' ";
        }
        
        $where .= " AND  (CAST(I.invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
        $whereHR .= " AND  (CAST(HR.entrydate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) ";
       
        $whereCT = " AND EXISTS (SELECT CT.id FROM contract AS CT WHERE CT.contractid = xxxxxx.contractid " . $whereCT . ") \n";

        $partSql = "SELECT IL.productId, \n"
                . "(SELECT P.productdescription FROM product AS P WHERE P.productcode = IL.productId) AS productdescription, \n"
                . "(COUNT(I.id)) AS nInvoices, \n"
                . "(SUM(IL.quantity)) AS quantity, \n"
                . "(SUM(IL.subtotalLine * I.paymentinfaultperc)) AS subtotalLine \n"
                . "FROM invoice AS I, invoiceline AS IL  \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.consumptioninvoice = 0 AND UPPER(I.invoicestatus) != 'A' \n"
                . " AND IL.productId = 'yyyyyy' AND (I.paymentamount < I.totalInvoice)  \n"
                . "AND IL.invoiceNumber = I.invoicenumber \n"
                . $where 
                . str_replace("xxxxxx", "I", $whereCT)
                . " \n";

        $sql = "SELECT DISTINCT IL.productId \n"
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.consumptioninvoice = 0 AND UPPER(I.invoicestatus) != 'A'   " . $where
                . str_replace("xxxxxx", "I", $whereCT) . " AND (I.paymentamount < I.totalInvoice) \n"
                . "AND IL.invoiceNumber = I.invoicenumber AND IL.subtotalLine > 0.01 \n"
                . "ORDER BY IL.productDescription; ";

        file_put_contents("sql-2.txt", $sql);
        
        $result = $this->query($sql);
        $n = 0;
        $sql = "";
        while ($row = mysqli_fetch_array($result)) {
            if ($n != 0) {
                $sql .= " UNION \n";
            }
            $sql .= str_replace("yyyyyy", $row['productId'], $partSql);
            $n += 1;
        }
        
        file_put_contents("sql.txt", $sql);
        
        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    
    public function treasuryTaxReport($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $sectionId = $this->real_escape_string($arrayFilterInfo['sectionId']);
        $category = $this->real_escape_string($arrayFilterInfo['category']);
        $family = $this->real_escape_string($arrayFilterInfo['family']);
        $brand = $this->real_escape_string($arrayFilterInfo['brand']);

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND I.dependencyid = '" . $dependencyId . "' ";
        }
        if ($sectionId != -1) {
            $where .= " AND IL.productSection = '" . $sectionId . "' ";
        }

        $whereCatFamBra = " AND EXISTS(SELECT P.id FROM product AS P WHERE P.xxxxxx = 'yyyyyy' AND P.id = IL.productId) ";
        if ($category != -1) {
            $where .= str_replace("xxxxxx", "productcategory", str_replace("yyyyyy", $category, $whereCatFamBra));
        }
        if ($family != -1) {
            $where .= str_replace("xxxxxx", "productfamily", str_replace("yyyyyy", $family, $whereCatFamBra));
        }
        if ($brand != -1) {
            $where .= str_replace("xxxxxx", "productbrand", str_replace("yyyyyy", $brand, $whereCatFamBra));
        }


        $sql = "SELECT I.request, I.invoicenumber, "
                . "IL.productDescription, IL.productId, IL.quantity, IL.taxPercentage, IL.ivaValue, IL.subtotalLine  \n"
                . "FROM invoice AS I, invoiceline AS IL \n"
                . "WHERE I.companyid = '" . $companyId . "' AND I.invoicenumber = IL.invoicenumber AND UPPER(I.invoicestatus)!= 'A'  " . $where
                . "AND (CAST(invoicedate AS DATE) BETWEEN CAST('" . $initialDate . "' AS DATE) AND CAST('" . $endDate . "' AS DATE)) \n"
                . "AND IL.note = 0 \n"
                . "ORDER BY IL.taxPercentage, I.invoicenumber ; ";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function printOptionSaveSignature($companyId, $dependencyId, $arraySignatureInfo) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);

        $sql = "";
        foreach ($arraySignatureInfo as $key => $sign) {
            $person = $this->real_escape_string($sign['person']);
            $quality = $this->real_escape_string($sign['quality']);

            if (($person != "") || ($quality != "")) {
                $sql .= "INSERT INTO documentsignature ("
                        . "companyid, dependencyid, person, quality) \n"
                        . "SELECT '" . $companyId . "', '" . $dependencyId . "', '" . $person . "', '" . $quality . "' \n"
                        . "FROM insert_table "
                        . "WHERE NOT EXISTS (SELECT id FROM documentsignature WHERE "
                        . "companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "' AND person = '" . $person . "' AND quality = '" . $quality . "'); \n";
            }
        }

        if ($sql != "") {
            $this->multi_query($sql);
        }

        return json_encode(array("result" => 1, "msg" => "Assinaturas actualizadas"));
    }

    public function printOptionGetSignature($companyId, $dependencyId) {
        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);

        $sql = "SELECT * \n"
                . "FROM documentsignature  \n"
                . "WHERE companyid = '" . $companyId . "' AND dependencyid = '" . $dependencyId . "'  \n"
                . "ORDER BY person;";

        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function waterTaxGetWaterTax() {

        $sql = "SELECT * "
                . "FROM watertax "
                . "ORDER BY taxlevel;";
        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function customerGetNeiborhoodList($companyId, $dependencyId, $type) {

        $companyId = $this->real_escape_string($companyId);
        $dependencyId = $this->real_escape_string($dependencyId);
        $type = $this->real_escape_string($type);
        $table = "linkrequest";

        if ($type == 2) {
            $table = "customer";
        } elseif ($type == 3) {
            $table = "contract";
        }

        $where = "";
        if ($dependencyId != -1) {
            $where .= " AND dependencyid = '" . $dependencyId . "' ";
        }


        $sql = "SELECT neiborhood, serie \n"
                . "FROM neiborhood; \n";
        /*           . "UNION \n";
          $sql .= "SELECT DISTINCT billingneiborhood AS neiborhood, '' AS serie \n"
          . "FROM  " . $table . "  \n"
          . "WHERE companyid = '" . $companyId . "' AND billingneiborhood != '' AND \n"
          . "billingneiborhood NOT IN (SELECT neiborhood FROM neiborhood)  \n"
          . "ORDER BY neiborhood;"; */

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function hydrometerGetBrandList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);

        $sql = "SELECT brand \n"
                . "FROM hydrometerbrand; \n";
        /*         . "UNION  \n";
          $sql .= "SELECT DISTINCT brand AS brand \n"
          . "FROM  hydrometer  \n"
          . "WHERE companyid = '" . $companyId . "' AND brand != '' AND \n"
          . "brand NOT IN (SELECT brand FROM hydrometerbrand) \n"
          . "ORDER BY brand;"; */

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterBusinessNew($business) {
        $sql = " INSERT INTO registerbusiness (business)"
                . "SELECT '" . $business . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM registerbusiness WHERE business = '" . $business . "');
";
        $this->query($sql);
        $sql = "SELECT id FROM registerbusiness WHERE business = '" . $business . "';
";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function preRegisterBusinessSave($businessId, $business, $serviceTitle) {
        $businessId = $this->real_escape_string($businessId);
        $business = $this->real_escape_string($business);
        $serviceTitle = $this->real_escape_string($serviceTitle);

        if (!is_numeric($businessId)) {
            $businessId = $this->preRegisterBusinessNew($business);
        }

        $sql = "UPDATE registerbusiness SET "
                . "business = '" . $business . "', "
                . "servicetitle = '" . $serviceTitle . "' \n"
                . "WHERE id = " . $businessId . ";
";
        $result = $this->multi_query($sql);
        return 1;
    }

    public function preRegisterBusinessDelete($businessId) {
        $businessId = $this->real_escape_string($businessId);
        $sql = "DELETE FROM registerbusiness "
                . "WHERE id = " . $businessId . ";
";
        $result = $this->multi_query($sql);
        return true;
    }

    public function preRegisterGetServices($businessId, $serviceId) {
        $businessId = $this->real_escape_string($businessId);
        $serviceId = $this->real_escape_string($serviceId);
        $where = "";
        if ($serviceId != "") {
            $where = " AND id = '" . $serviceId . "' ";
        }
        $sql = "SELECT * "
                . "FROM registerservice "
                . "WHERE businessid = '" . $businessId . "' " . $where
                . "ORDER BY service;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterServiceNew($businessId, $service) {
        $businessId = $this->real_escape_string($businessId);
        $service = $this->real_escape_string($service);
        $sql = " INSERT INTO registerservice (businessid, service)"
                . "SELECT '" . $businessId . "', '" . $service . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM registerservice WHERE businessid = '" . $businessId . "' AND service = '" . $service . "');
";
        $this->query($sql);
        $sql = "SELECT id FROM registerservice WHERE businessid = '" . $businessId . "' AND service = '" . $service . "';
";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function preRegisterServiceSave($businessId, $serviceId, $service) {
        $businessId = $this->real_escape_string($businessId);
        $serviceId = $this->real_escape_string($serviceId);
        $service = $this->real_escape_string($service);

        if (!is_numeric($serviceId)) {
            $serviceId = $this->preRegisterServiceNew($businessId, $service);
        }

        $sql = "UPDATE registerservice SET "
                . "service = '" . $service . "' \n"
                . "WHERE id = " . $serviceId . ";
";

        $result = $this->multi_query($sql);
        return 1;
    }

    public function preRegisterServiceDelete($serviceId) {
        $serviceId = $this->real_escape_string($serviceId);
        $sql = "DELETE FROM registerservice "
                . "WHERE id = " . $serviceId . ";
";
        $result = $this->multi_query($sql);
        return true;
    }

    public function preRegisterGetQuestions($businessId, $questionId) {
        $businessId = $this->real_escape_string($businessId);
        $questionId = $this->real_escape_string($questionId);
        $where = "";
        if ($questionId != "") {
            $where = " AND id = '" . $questionId . "' ";
        }
        $sql = "SELECT * "
                . "FROM registerquestion "
                . "WHERE businessid = '" . $businessId . "' " . $where
                . "ORDER BY question;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterQuestionNew($businessId, $question) {
        $businessId = $this->real_escape_string($businessId);
        $question = $this->real_escape_string($question);
        $sql = " INSERT INTO registerquestion (businessid, question) \n"
                . "SELECT '" . $businessId . "', '" . $question . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM registerquestion WHERE businessid = '" . $businessId . "' AND question = '" . $question . "');
";
        $this->query($sql);
        $sql = "SELECT id FROM registerquestion WHERE businessid = '" . $businessId . "' AND question = '" . $question . "';
";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function preRegisterQuestionSave($businessId, $questionId, $question) {
        $businessId = $this->real_escape_string($businessId);
        $questionId = $this->real_escape_string($questionId);
        $question = $this->real_escape_string($question);

        if (!is_numeric($questionId)) {
            $questionId = $this->preRegisterQuestionNew($businessId, $question);
        }

        $sql = "UPDATE registerquestion SET "
                . "question = '" . $question . "' \n"
                . "WHERE id = " . $questionId . ";
";

        $result = $this->multi_query($sql);
        return 1;
    }

    public function preRegisterQuestionDelete($questionId) {
        $questionId = $this->real_escape_string($questionId);
        $sql = "DELETE FROM registerquestion "
                . "WHERE id = " . $questionId . ";
";
        $result = $this->multi_query($sql);
        return true;
    }

    public function preRegisterGetInvestments($businessId, $investmentId) {
        $businessId = $this->real_escape_string($businessId);
        $investmentId = $this->real_escape_string($investmentId);
        $where = "";
        if ($investmentId != "") {
            $where = " AND id = '" . $investmentId . "' ";
        }
        $sql = "SELECT *, 1 AS status, \n"
                . "(SELECT P.productdescription FROM product AS P WHERE P.id = RI.productid LIMIT 1) AS productdescription, \n"
                . "(COALESCE((SELECT PP.pvp FROM productprice AS PP WHERE PP.productid = RI.productid LIMIT 1), 0)) AS PVP \n"
                . "FROM registerinvestment AS RI \n"
                . "WHERE businessid = '" . $businessId . "' " . $where
                . "ORDER BY productdescription;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterInvestmentDefaults() {

        function strInsert($businessId, $productId) {
            return "INSERT INTO registerinvestment (businessid, productid) \n"
                    . "SELECT '" . $businessId . "', '" . $productId . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS (SELECT id FROM registerinvestment WHERE businessid = '" . $businessId . "' AND productid = '" . $productId . "');
\n";
        }

        $sql = "";
        for ($index = 300462; $index <= 300465; $index++) {
            $sql .= strInsert(99, $index);
        }
        for ($index1 = 101; $index1 <= 355; $index1++) {
            for ($index2 = 300466; $index2 <= 300473; $index2++) {
                $sql .= strInsert($index1, $index2);
            }
        }

        $arrayExcptions = array(510, 512, 525, 526, 535, 553, 554, 555, 556, 557, 558,
            559, 560, 561, 562, 563, 564, 565, 566, 567, 568, 569, 570,
            571, 572, 573, 578, 591, 598, 601, 624, 630, 631, 635, 636,
            637, 638, 639, 640, 641, 642, 643, 644, 645, 646, 647, 648,
            649, 650, 651, 654, 700, 701);

        for ($index3 = 501; $index3 <= 732; $index3++) {
            if (!in_array($index3, $arrayExcptions)) {
                for ($index4 = 300479; $index4 <= 300483; $index4++) {
                    $sql .= strInsert($index3, $index4);
                }
            }
        }
        $arrMark = array(300295, 300358, 300366, 300374, 300375, 300476);
        foreach ($arrMark as $value) {
            $sql .= strInsert(510, $value);
        }
        $arrMark = array(300277, 300280, 300281, 300285, 300286, 300448, 300449, 300450, 300453, 300454);
        foreach ($arrMark as $value) {
            $sql .= strInsert(578, $value);
        }
        $arrMark = array(300232, 300233, 300234, 300235, 300236, 300237, 300238, 300239, 300240, 300241);
        foreach ($arrMark as $value) {
            $sql .= strInsert(654, $value);
        }
        $arrMark = array(300242, 300243, 300244, 300245, 300246, 300479, 300481, 300482, 300483, 300489, 300490, 300491, 300492, 300493);
        foreach ($arrMark as $value) {
            $sql .= strInsert(591, $value);
        }
        $arrMark = array(300267, 300268, 300269, 300270, 300271, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(535, $value);
        }
        $arrMark = array(300247, 300248, 300249, 300250, 300251, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(631, $value);
        }
        $arrMark = array(300262, 300263, 300264, 300265, 300266, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(601, $value);
        }
        $arrMark = array(300252, 300253, 300254, 300255, 300256, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(624, $value);
        }
        $arrMark = array(300225, 300292, 300355, 300363, 300368);
        foreach ($arrMark as $value) {
            $sql .= strInsert(630, $value);
        }
        $arrMark = array(300227, 300357, 300460, 300474, 300475);
        foreach ($arrMark as $value) {
            $sql .= strInsert(512, $value);
        }
        $arrMark = array(300228, 300296, 300359, 300367, 300477);
        foreach ($arrMark as $value) {
            $sql .= strInsert(635, $value);
        }
        $arrMark = array(300231, 300299, 300362, 300372, 300376, 300459);
        foreach ($arrMark as $value) {
            $sql .= strInsert(636, $value);
        }
        $arrMark = array(300257, 300258, 300259, 300260, 300261);
        foreach ($arrMark as $value) {
            $sql .= strInsert(598, $value);
        }
        $arrMark = array(300287, 300291, 300455, 300457, 300479, 300481, 300482, 300483, 300484, 300485, 300486, 300487, 300488);
        foreach ($arrMark as $value) {
            $sql .= strInsert(700, $value);
        }
        $arrMark = array(300272, 300273, 300274, 300275, 300276, 300452, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(701, $value);
        }
        $arrMark = array(300226, 300293, 300356, 300369, 300458, 300479, 300481, 300482, 300483);
        foreach ($arrMark as $value) {
            $sql .= strInsert(525, $value);
            $sql .= strInsert(526, $value);
        }
        for ($index5 = 637; $index5 <= 651; $index5++) {
            $arrMark = array(300230, 300298, 300361, 300364, 300371);
            foreach ($arrMark as $value) {
                $sql .= strInsert($index5, $value);
            }
        }
        for ($index6 = 553; $index6 <= 573; $index6++) {
            $arrMark = array(300229, 300297, 300360, 300365, 300478);
            foreach ($arrMark as $value) {
                $sql .= strInsert($index6, $value);
            }
        }

        for ($index7 = 101; $index7 <= 351; $index7++) {
            $sql .= strInsert($index7, 300461);
        }
        for ($index8 = 501; $index8 <= 732; $index8++) {
            $sql .= strInsert($index8, 300461);
        }

        $this->multi_query($sql);
    }

    public function preRegisterInvestmentNew($businessId, $productId) {
        $businessId = $this->real_escape_string($businessId);
        $productId = $this->real_escape_string($productId);
        $sql = " INSERT INTO registerinvestment (businessid, productid) "
                . "SELECT '" . $businessId . "', '" . $productId . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM registerinvestment WHERE businessid = '" . $businessId . "' AND productid = '" . $productId . "');
";
        $this->query($sql);
        $sql = "SELECT id FROM registerinvestment WHERE businessid = '" . $businessId . "' AND productid = '" . $productId . "';
";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function preRegisterInvestmentSave($businessId, $investId, $productId) {
        $businessId = $this->real_escape_string($businessId);
        $investId = $this->real_escape_string($investId);
        $productId = $this->real_escape_string($productId);

        if (!is_numeric($investId)) {
            $investId = $this->preRegisterInvestmentNew($businessId, $productId);
        }

        $sql = "UPDATE registerinvestment SET "
                . "productid = '" . $productId . "' \n"
                . "WHERE id = " . $investId . ";
";

        $result = $this->multi_query($sql);
        return 1;
    }

    public function preRegisterInvestmentDelete($investId) {
        $investId = $this->real_escape_string($investId);
        $sql = "DELETE FROM registerinvestment "
                . "WHERE id = " . $investId . ";
";
        $result = $this->multi_query($sql);
        return true;
    }

    public function preRegisterRegisterNew($registerTaxId, $companyId, $request, $entryuser,
            $coordinatorUser, $managerUser, $directorGeneralUser, $administratorUser, $partinership) {

        $registerTaxId = $this->real_escape_string($registerTaxId);
        $companyId = $this->real_escape_string($companyId);
        $request = $this->real_escape_string($request);
        $entryuser = $this->real_escape_string($entryuser);
        $coordinatorUser = $this->real_escape_string($coordinatorUser);
        $managerUser = $this->real_escape_string($managerUser);
        $directorGeneralUser = $this->real_escape_string($directorGeneralUser);
        $administratorUser = $this->real_escape_string($administratorUser);
        $partinership = $this->real_escape_string($partinership);

        $sql = "INSERT INTO register (companyid, registertaxid, entryuser, request, \n"
                . "coordinatorUser, managerUser, \n"
                . "directorGeneralUser, administratorUser, partinership) \n"
                . "SELECT '" . $companyId . "', '" . $registerTaxId . "', '" . $entryuser . "', '" . $request . "', \n"
                . "'" . $coordinatorUser . "', '" . $managerUser . "', \n"
                . "'" . $directorGeneralUser . "', '" . $administratorUser . "', '" . $partinership . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM register WHERE registertaxid = '" . $registerTaxId . "');
\n";
        $this->query($sql);

        $sql = "SELECT id FROM register WHERE registertaxid = '" . $registerTaxId . "';
";
        $registerId = 0;
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $registerId = $row[0];
            mysqli_free_result($result);
        }

        return $registerId;
    }

    private function preRegisterRegisterValidateNIF($registerId, $taxId) {
        $sql = "SELECT registertaxid \n"
                . "FROM register \n"
                . "WHERE registertaxid <> '' AND registertaxid = '" . $taxId . "' AND (CAST(id AS CHAR)) <> '" . $registerId . "' \n"
                . "LIMIT 1;";

        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            mysqli_free_result($result);
            return 1;
        } else {
            return 0;
        }
    }

    public function preRegisterRegisterSaveInfo($arrayRegisterInfo, $sender) {
        $companyId = $this->real_escape_string($arrayRegisterInfo['companyId']);
        $registerId = $this->real_escape_string($arrayRegisterInfo['registerId']);
        $registerName = $this->real_escape_string($arrayRegisterInfo['registerName']);
        $entryuser = $this->real_escape_string($arrayRegisterInfo['entryuser']);
        $laststage = $this->real_escape_string($arrayRegisterInfo['laststage']);

        if ($sender == "REGISTER") {
            $persontype = $this->real_escape_string($arrayRegisterInfo['persontype']);
            $registerdoc = $this->real_escape_string($arrayRegisterInfo['registerdoc']);
            $registerdocnumber = $this->real_escape_string($arrayRegisterInfo['registerdocnumber']);
            $registernationality = $this->real_escape_string($arrayRegisterInfo['registernationality']);
            $fathername = $this->real_escape_string($arrayRegisterInfo['fathername']);
            $mothername = $this->real_escape_string($arrayRegisterInfo['mothername']);
            $profission = $this->real_escape_string($arrayRegisterInfo['profission']);
            $registerTaxId = $this->real_escape_string($arrayRegisterInfo['registerTaxId']);
            //$registerType = $this->real_escape_string($arrayRegisterInfo['registerType']);
            $businessid = $this->real_escape_string($arrayRegisterInfo['businessid']);

            $billingCountry = $this->real_escape_string($arrayRegisterInfo['billingCountry']);
            $billingProvince = $this->real_escape_string($arrayRegisterInfo['billingProvince']);
            $billingMunicipality = $this->real_escape_string($arrayRegisterInfo['billingMunicipality']);
            $billingCity = $this->real_escape_string($arrayRegisterInfo['billingCity']);
            $billingDistrict = $this->real_escape_string($arrayRegisterInfo['billingDistrict']);
            $billingComuna = $this->real_escape_string($arrayRegisterInfo['billingComuna']);
            $billingNeiborhood = $this->real_escape_string($arrayRegisterInfo['billingNeiborhood']);
            $billingStreetName = $this->real_escape_string($arrayRegisterInfo['billingStreetName']);
            $billingBuildNumber = $this->real_escape_string($arrayRegisterInfo['billingBuildNumber']);
            $billingPostalCode = $this->real_escape_string($arrayRegisterInfo['billingPostalCode']);
            $billingPhone1 = $this->real_escape_string($arrayRegisterInfo['billingPhone1']);
            $billingPhone2 = $this->real_escape_string($arrayRegisterInfo['billingPhone2']);
            $billingPhone3 = $this->real_escape_string($arrayRegisterInfo['billingPhone3']);
            $billingEmail = $this->real_escape_string($arrayRegisterInfo['billingEmail']);
            $billingWebsite = $this->real_escape_string($arrayRegisterInfo['billingWebsite']);
            $dependence = $this->real_escape_string($arrayRegisterInfo['dependence']);

            $request = $this->real_escape_string($arrayRegisterInfo['request']);
            $coordinatorUser = $this->real_escape_string($arrayRegisterInfo['coordinatorUser']);
            $managerUser = $this->real_escape_string($arrayRegisterInfo['managerUser']);
            $directorGeneralUser = $this->real_escape_string($arrayRegisterInfo['directorGeneralUser']);
            $administratorUser = $this->real_escape_string($arrayRegisterInfo['administratorUser']);
            $partinership = $this->real_escape_string($arrayRegisterInfo['partinership']);

            if ($this->preRegisterRegisterValidateNIF($registerId, $registerTaxId) == 1) {
                echo json_encode(array("status" => 0, "msg" => 'O NIF já foi cadastrado.'));
                return false;
            }
        }
        if ($sender == "PERSON") {
            $personname = $this->real_escape_string($arrayRegisterInfo['personname']);
            $personfunction = $this->real_escape_string($arrayRegisterInfo['personfunction']);
            $identificationdoc = $this->real_escape_string($arrayRegisterInfo['identificationdoc']);
            $identificationnumber = $this->real_escape_string($arrayRegisterInfo['identificationnumber']);
            $personnationality = $this->real_escape_string($arrayRegisterInfo['personnationality']);
            $personphone1 = $this->real_escape_string($arrayRegisterInfo['personphone1']);
            $personphone2 = $this->real_escape_string($arrayRegisterInfo['personphone2']);
            $personemail = $this->real_escape_string($arrayRegisterInfo['personemail']);

            $academicdegree = $this->real_escape_string($arrayRegisterInfo['academicdegree']);
            $schoolname = $this->real_escape_string($arrayRegisterInfo['schoolname']);
            $formationyear = $this->real_escape_string($arrayRegisterInfo['formationyear']);
            $worktitle = $this->real_escape_string($arrayRegisterInfo['worktitle']);
        }
        if ($sender == "ATTACH") {
            $attachdocument1 = $this->real_escape_string($arrayRegisterInfo['attachdocument1']);
            $attachdocument2 = $this->real_escape_string($arrayRegisterInfo['attachdocument2']);
            $attachdocument3 = $this->real_escape_string($arrayRegisterInfo['attachdocument3']);
            $attachdocument4 = $this->real_escape_string($arrayRegisterInfo['attachdocument4']);
            $attachdocument5 = $this->real_escape_string($arrayRegisterInfo['attachdocument5']);
            $attachdocument6 = $this->real_escape_string($arrayRegisterInfo['attachdocument6']);
        }


        if (!is_numeric($registerId)) {
            $registerId = $this->preRegisterRegisterNew($registerTaxId, $companyId, $request, $entryuser,
                    $coordinatorUser, $managerUser, $directorGeneralUser, $administratorUser, $partinership);
        }


        $sql = "UPDATE register SET \n";
        if ($sender == "REGISTER") {
            $sql .= "registername = '" . $registerName . "', "
                    . "persontype = '" . $persontype . "', "
                    . "registerdoc = '" . $registerdoc . "', "
                    . "registerdocnumber = '" . $registerdocnumber . "', "
                    . "registernationality = '" . $registernationality . "', "
                    . "fathername = '" . $fathername . "', "
                    . "mothername = '" . $mothername . "', "
                    . "profission = '" . $profission . "', "
                    . "businessid = '" . $businessid . "', \n"
                    . "billingcountry = '" . $billingCountry . "', "
                    . "billingprovince = '" . $billingProvince . "', "
                    . "billingmunicipality = '" . $billingMunicipality . "', "
                    . "billingcity = '" . $billingCity . "', "
                    . "billingdistrict = '" . $billingDistrict . "', "
                    . "billingcomuna = '" . $billingComuna . "', \n"
                    . "billingneiborhood = '" . $billingNeiborhood . "', "
                    . "billingstreetname = '" . $billingStreetName . "', "
                    . "billingbuildingnumber = '" . $billingBuildNumber . "', \n"
                    . "billingpostalcode = '" . $billingPostalCode . "', "
                    . "billingtelephone1 = '" . $billingPhone1 . "', "
                    . "billingtelephone2 = '" . $billingPhone2 . "', "
                    . "billingtelephone3 = '" . $billingPhone3 . "', "
                    . "billingemail = '" . $billingEmail . "', "
                    . "billingwebsite = '" . $billingWebsite . "', "
                    . "dependence = '" . $dependence . "', \n";
        }

        if ($sender == "PERSON") {
            $sql .= "personname = '" . $personname . "', "
                    . "personfunction = '" . $personfunction . "', "
                    . "identificationdoc = '" . $identificationdoc . "', "
                    . "identificationnumber = '" . $identificationnumber . "', \n"
                    . "personnationality = '" . $personnationality . "', "
                    . "personphone1 = '" . $personphone1 . "', "
                    . "personphone2 = '" . $personphone2 . "', "
                    . "personemail = '" . $personemail . "', \n"
                    . "academicdegree = '" . $academicdegree . "', "
                    . "schoolname = '" . $schoolname . "', "
                    . "formationyear = '" . $formationyear . "', "
                    . "worktitle = '" . $worktitle . "', \n";
        }
        if ($sender == "ATTACH") {
            $sql .= "attachdocument1 = '" . $attachdocument1 . "', "
                    . "attachdocument2 = '" . $attachdocument2 . "', "
                    . "attachdocument3 = '" . $attachdocument3 . "', "
                    . "attachdocument4 = '" . $attachdocument4 . "', "
                    . "attachdocument5 = '" . $attachdocument5 . "', "
                    . "attachdocument6 = '" . $attachdocument6 . "', ";
        }

        $sql .= "statususer = '" . $entryuser . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = '" . $registerId . "';
\n";
        if ($sender == "PERSON") {
            $strProfitionalProfition = $arrayRegisterInfo['profitionalFormation'];
            $arrayProfitionalProfition = json_decode($strProfitionalProfition, true);

            foreach ($arrayProfitionalProfition as $key => $fp) {
                $id = $this->real_escape_string($fp['id']);
                $formationtype = $this->real_escape_string($fp['formationtype']);
                $qualification = $this->real_escape_string($fp['qualification']);
                $schoolname = $this->real_escape_string($fp['schoolname']);
                $formationyear = $this->real_escape_string($fp['formationyear']);
                $country = $this->real_escape_string($fp['country']);
                $status = $this->real_escape_string($fp['status']);

                if ($status == 0) {
                    $sql .= "DELETE registeraprofitionalformation WHERE id = '" . $id . "';
\n";
                } elseif ($id == 0) {
                    $sql .= "INSERT INTO registeraprofitionalformation \n"
                            . "(registerid, formationtype, qualification, schoolname, formationyear, country) \n"
                            . "SELECT '" . $registerId . "', '" . $formationtype . "', '" . $qualification . "', \n"
                            . "'" . $schoolname . "', '" . $formationyear . "', '" . $country . "';
\n";
                } else {
                    $sql .= "UPDATE registeraprofitionalformation SET "
                            . "formationtype = '" . $formationtype . "', "
                            . "qualification = '" . $qualification . "', "
                            . "schoolname = '" . $schoolname . "', "
                            . "formationyear = '" . $formationyear . "', "
                            . "country = '" . $country . "' \n"
                            . "WHERE id = '" . $id . "';
";
                }
            }
        }

        $sql .= "UPDATE register SET "
                . "laststage = '" . $laststage . "' \n"
                . "WHERE id = '" . $registerId . "' AND laststage < '" . $laststage . "';
\n";

        $result = $this->multi_query($sql);
        return json_encode(array("status" => true, "registerId" => $registerId, "registerName" => $registerName));
    }

    public function preRegisterRegisterList($companyId, $registerId = -1) {
        $companyId = $this->real_escape_string($companyId);
        $registerId = $this->real_escape_string($registerId);
        $where = "";
        if ($registerId != "") {
            $where = " AND id = '" . $registerId . "' ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT WD.invoicenumber FROM workingdocument AS WD WHERE WD.registerid = R.id LIMIT 1) AS workingDocument \n"
                . "FROM register AS R \n"
                . "WHERE companyid = '" . $companyId . "' " . $where
                . "ORDER BY registername;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterRegisterGetInvestment($companyId, $businessId) {
        $companyId = $this->real_escape_string($companyId);
        $businessId = $this->real_escape_string($businessId);

        /*   $sql = "SELECT *, \n"
          . "(COALESCE((SELECT RAI.status FROM registerasignedinvestment AS RAI WHERE RAI.registerid = '" . $registerId . "' AND investmentid = RI.id LIMIT 1), 0)) AS status \n"
          . "FROM registerinvestment AS RI \n"
          . "WHERE businessid = '" . $businessId . "' \n"
          . "ORDER BY investment;
          \n"; */

        $result = $this->productGetFullDescription("", $companyId, $businessId);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterRegisterGetWorkingDocument($registerId) {
        $registerId = $this->real_escape_string($registerId);

        $sql = "SELECT invoicenumber, totalInvoice, invoicedate \n"
                . "FROM workingdocument AS WD \n"
                . "WHERE registerid = '" . $registerId . "' "
                . "ORDER BY invoicedate;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterGetAsignedServices($businessId, $registerId) {
        $businessId = $this->real_escape_string($businessId);
        $registerId = $this->real_escape_string($registerId);

        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT RAS.status FROM registerasignedservice AS RAS WHERE RAS.registerid = '" . $registerId . "' AND serviceid = RS.id LIMIT 1), 0)) AS status \n"
                . "FROM registerservice AS RS \n"
                . "WHERE businessid = '" . $businessId . "' \n"
                . "ORDER BY service;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterAsignesServiceSave($registerId, $arrayService) {
        $registerId = $this->real_escape_string($registerId);

        $sql = "";
        foreach ($arrayService as $key => $asignedServ) {
            $serviceId = $this->real_escape_string($asignedServ['id']);
            $status = $this->real_escape_string($asignedServ['status']);

            $sql .= "UPDATE registerasignedservice SET \n"
                    . "status = '" . $status . "' \n"
                    . "WHERE registerid = '" . $registerId . "' AND serviceid = '" . $serviceId . "';
\n";

            $sql .= "INSERT INTO registerasignedservice ("
                    . "registerid, serviceid, status) \n"
                    . "SELECT '" . $registerId . "', '" . $serviceId . "', '" . $status . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM registerasignedservice WHERE registerid = '" . $registerId . "' AND serviceid = '" . $serviceId . "');
\n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("status" => true, "registerId" => $registerId));
    }

    public function preRegisterGetAsignedQuestion($businessId, $registerId) {
        $businessId = $this->real_escape_string($businessId);
        $registerId = $this->real_escape_string($registerId);

        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT RAQ.status FROM registerasignedquestion AS RAQ WHERE RAQ.registerid = '" . $registerId . "' AND questionid = RQ.id LIMIT 1), 0)) AS status \n"
                . "FROM registerquestion AS RQ \n"
                . "WHERE businessid = '" . $businessId . "' \n"
                . "ORDER BY question;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterAsignesQuestionSave($registerId, $arrayQuestion) {
        $registerId = $this->real_escape_string($registerId);

        $sql = "";
        foreach ($arrayQuestion as $key => $asignedQuest) {
            $questionId = $this->real_escape_string($asignedQuest['id']);
            $status = $this->real_escape_string($asignedQuest['status']);

            $sql .= "UPDATE registerasignedquestion SET \n"
                    . "status = '" . $status . "' \n"
                    . "WHERE registerid = '" . $registerId . "' AND questionid = '" . $questionId . "';
\n";

            $sql .= "INSERT INTO registerasignedquestion ("
                    . "registerid, questionid, status) \n"
                    . "SELECT '" . $registerId . "', '" . $questionId . "', '" . $status . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM registerasignedquestion WHERE registerid = '" . $registerId . "' AND questionid = '" . $questionId . "');
\n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("status" => true, "registerId" => $registerId));
    }

    public function preRegisterGetAsignedInvestment($businessId, $registerId) {
        $businessId = $this->real_escape_string($businessId);
        $registerId = $this->real_escape_string($registerId);

        $sql = "SELECT *, \n"
                . "(COALESCE((SELECT RAI.status FROM registerasignedinvestment AS RAI WHERE RAI.registerid = '" . $registerId . "' AND investmentid = RI.id LIMIT 1), 0)) AS status \n"
                . "FROM registerinvestment AS RI \n"
                . "WHERE businessid = '" . $businessId . "' \n"
                . "ORDER BY investment;
\n";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function preRegisterAsignesInvestmentSave($registerId, $arrayInvestment) {
        $registerId = $this->real_escape_string($registerId);

        $sql = "";
        foreach ($arrayInvestment as $key => $asignedInvest) {
            $investmentId = $this->real_escape_string($asignedInvest['id']);
            $status = $this->real_escape_string($asignedInvest['status']);

            $sql .= "UPDATE registerasignedinvestment SET \n"
                    . "status = '" . $status . "' \n"
                    . "WHERE registerid = '" . $registerId . "' AND investmentid = '" . $investmentId . "';
\n";

            $sql .= "INSERT INTO registerasignedinvestment ("
                    . "registerid, investmentid, status) \n"
                    . "SELECT '" . $registerId . "', '" . $investmentId . "', '" . $status . "' \n"
                    . "FROM insert_table \n"
                    . "WHERE NOT EXISTS(SELECT id FROM registerasignedinvestment WHERE registerid = '" . $registerId . "' AND investmentid = '" . $investmentId . "');
\n";
        }

        $result = $this->multi_query($sql);
        return json_encode(array("status" => true, "registerId" => $registerId));
    }

    
    public function licenseTypeNew($designation) {
        $designation = $this->real_escape_string($designation);

        $sql = " INSERT INTO licensetype (designation) \n"
                . "SELECT '" . $designation . "' \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM licensetype WHERE designation = '" . $designation . "'); \n";
        $this->query($sql);
        $sql = "SELECT id FROM licensetype WHERE designation = '" . $designation . "'; \n";
        $result = $this->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            return $row[0];
        }
    }

    public function licenseTypeSave($arrayLicenseType) {

        $licensetypeid = $this->real_escape_string($arrayLicenseType['licensetypeid']);
        $designation = $this->real_escape_string($arrayLicenseType['designation']);
        $selfdomain = $this->real_escape_string($arrayLicenseType['selfdomain']);
        $silicatype = $this->real_escape_string($arrayLicenseType['silicatype']);
        $desktoptype = $this->real_escape_string($arrayLicenseType['desktoptype']);
        $maxusers = $this->real_escape_string($arrayLicenseType['maxusers']);
        $maxproducts = $this->real_escape_string($arrayLicenseType['maxproducts']);
        $maxinvoices = $this->real_escape_string($arrayLicenseType['maxinvoices']);
        $saleamount = $this->real_escape_string($arrayLicenseType['saleamount']);
        $salepercentage = $this->real_escape_string($arrayLicenseType['salepercentage']);
        $duration = $this->real_escape_string($arrayLicenseType['duration']);
        $licenselevel = $this->real_escape_string($arrayLicenseType['licenselevel']);

        if ($this->validateDuplicateElement(-1, $licensetypeid, "id",
                        $designation, "licensetype", "designation") == 1) {
            return json_encode(array("status" => 0, "msg" => "Nome já cadastrada."));
        }

        if (!is_numeric($licensetypeid)) {
            $licensetypeid = $this->licenseTypeNew($designation);
        }

        $sql = "UPDATE licensetype SET \n"
                . "designation = '" . $designation . "', "
                . "selfdomain = '" . $selfdomain . "', "
                . "silicatype = '" . $silicatype . "', "
                . "desktoptype = '" . $desktoptype . "', "
                . "maxusers = '" . $maxusers . "', "
                . "maxproducts = '" . $maxproducts . "', "
                . "maxinvoices = '" . $maxinvoices . "', "
                . "saleamount = '" . $saleamount . "', "
                . "salepercentage = '" . $salepercentage . "', "
                . "duration = '" . $duration . "',"
                . "licenselevel = '" . $licenselevel . "' \n"
                . "WHERE id = " . $licensetypeid . "; ";
        $result = $this->multi_query($sql);
        return json_encode(array("status" => 1, "msg" => "Actualizado com sucesso."));
    }

    public function licenseTypeGetList($licenseTypeId) {
        $licenseTypeId = $this->real_escape_string($licenseTypeId);
        $where = "";
        if ($licenseTypeId != "") {
            $where = " AND id = '" . $licenseTypeId . "' ";
        }
        $sql = "SELECT * "
                . "FROM licensetype "
                . "WHERE id > 0 " . $where
                . "ORDER BY designation";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function licenseTypeDelete($licenseTypeId) {
        $licenseTypeId = $this->real_escape_string($licenseTypeId);
        $sql = "DELETE FROM licensetype "
                . "WHERE id = " . $licenseTypeId . "; ";
        $result = $this->multi_query($sql);
        return true;
    }

    public function licenseAssignedSave($arrayLicenseAssigned) {

        $companyid = $this->real_escape_string($arrayLicenseAssigned['companyid']);
        $invoicenumber = $this->real_escape_string($arrayLicenseAssigned['invoicenumber']);
        $productdescription = $this->real_escape_string($arrayLicenseAssigned['productdescription']);
        $licensetypeid = $this->real_escape_string($arrayLicenseAssigned['licensetypeid']);
        $entryuser = $this->real_escape_string($arrayLicenseAssigned['entryuser']);


        $serialnumber = generateSerialNumber();

        $sql = "INSERT INTO licenseassigned (\n"
                . "companyid, invoicenumber, productdescription, licensetypeid, serialnumber, \n"
                . "status, entryuser, entrydate) \n"
                . "SELECT '" . $companyid . "', '" . $invoicenumber . "', '" . $productdescription . "', '" . $licensetypeid . "', \n"
                . "'" . $serialnumber . "', '2', '" . $entryuser . "', NOW() \n"
                . "FROM insert_table \n"
                . "WHERE NOT EXISTS (SELECT id FROM licenseassigned "
                . "WHERE companyid = '" . $companyid . "' AND invoicenumber = '" . $invoicenumber . "' AND productdescription = '" . $productdescription . "' AND licensetypeid = '" . $licensetypeid . "'); ";

        $result = $this->multi_query($sql);
        return 1;
    }

    public function licenseAssignedList($companyId, $licenseType) {
        $companyId = $this->real_escape_string($companyId);
        $licenseType = $this->real_escape_string($licenseType);

        $where = "";
        if ($companyId != -1) {
            $where = " AND companyid = '" . $companyId . "' ";
        }
        if ($licenseType != -1) {
            $where .= " AND LA.licensetypeid IN (SELECT LT.id FROM licensetype AS LT WHERE LT.silicatype = '" . $licenseType . "') ";
        }
        $sql = "SELECT *, \n"
                . "(SELECT C.companyname FROM company AS C WHERE C.id = LA.companyid LIMIT 1) AS companyname, \n"
                . "(SELECT LT. designation FROM licensetype AS LT WHERE LT.id = LA.licensetypeid LIMIT 1) AS licensetype, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = LA.entryuser LIMIT 1) AS operatorname \n"
                . "FROM licenseassigned AS LA \n"
                . "WHERE id > 0 " . $where
                . "ORDER BY companyname, status";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row['enddate'] = _rsaCryptGeneral::getInstance()->decryptRSA_general($row['enddate']);
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function licenseAssignedDelete($licenseAssignedId) {
        $licenseAssignedId = $this->real_escape_string($licenseAssignedId);
        $sql = "DELETE FROM licenseassigned "
                . "WHERE id = " . $licenseAssignedId . "; ";
        $result = $this->multi_query($sql);
        return true;
    }

    public function licenseAssignedGetInvoice($customerId) {
        $customerId = $this->real_escape_string($customerId);

        $sql = "SELECT invoicenumber, invoicedate  \n"
                . "FROM invoice \n"
                . "WHERE customerid = '" . $customerId . "' AND  UPPER(I.invoicestatus)!= 'A') \n"
                . "ORDER BY invoicedate DESC";

        $result = $this->query($sql);

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return json_encode($arrayResult);
    }

    public function licenseGetLicenseStatus($companyId) {
        $companyId = $this->real_escape_string($companyId);

        $strQL = "SELECT *, LA.id AS oldLicenseId, LT.id AS licenseTypeId, \n"
                . "(SELECT COUNT(U.id) FROM systemuser AS U WHERE U.companyid = LA.companyid) AS currentUsers, \n"
                . "((SELECT COUNT(I.id) FROM invoice AS I WHERE I.companyid = LA.companyid) + "
                . "(SELECT COUNT(W.id) FROM workingdocument AS W WHERE W.companyid = LA.companyid) ) AS currentInvoices, \n"
                . "(COALESCE((SELECT SUM(I.totalInvoice) FROM invoice AS I WHERE I.companyid = LA.companyid), 0)) AS currentAmount, \n"
                . "(SELECT C.companyname FROM company AS C WHERE C.id = LA.companyid LIMIT 1) AS companyname, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = LA.entryuser LIMIT 1) AS operatorname \n"
                . "FROM licenseassigned AS LA, licensetype AS LT \n"
                . "WHERE companyid = '" . $companyId . "' AND LA.status = xxxxxx AND LT.id = LA.licensetypeid AND  "
                . "LT.silicatype = 0 \n"
                . "ORDER BY entrydate \n"
                . "LIMIT 1;";
        $sql = str_replace("xxxxxx", "1", $strQL);
        file_put_contents("filename.txt", $sql);
        $result = $this->query($sql);
        $oldLicenseId = 0;

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $perc = $row['salepercentage'];
            $oldLicenseId = $row['oldLicenseId'];
            $row['enddate'] = _rsaCryptGeneral::getInstance()->decryptRSA_general($row['enddate']);
            $endDate = Date($row['enddate']);
            $row = $this->converteArrayParaUtf8($row);
            mysqli_free_result($result);

            /*   $sql = "UPDATE productprice SET silicacomission = '" . $perc . "' \n"
              . "WHERE productid IN (SELECT P.id FROM product AS P WHERE P.companyid = '" . $companyId . "'); \n";
              $this->query($sql);
              $sql = "UPDATE productpricepercentsuggest SET silicacomission = '" . $perc . "' \n"
              . "WHERE companyid = '" . $companyId . "';";
              $this->query($sql); */

            $today = strtotime(Date("Y-m-d"));
            $dateToOff = strtotime($endDate);
            if ($today <= $dateToOff) {
                return $row;
            }
        }

        //Procurar licensa em espera
        $sql = str_replace("xxxxxx", "2", $strQL);
        file_put_contents("filename2.txt", $sql);
        $newResult = $this->query($sql);

        if ($newResult->num_rows > 0) {
            //Desactivar licença anterior
            $sql = "UPDATE licenseassigned SET status = 0 \n"
                    . "WHERE id = '" . $oldLicenseId . "'; \n";
            $this->query($sql);

            $newRow = mysqli_fetch_array($newResult);
            $newLicenseId = $newRow['oldLicenseId'];
            $duration = $newRow['duration'];
            if ($duration == -1) {
                $duration = 10000;
            }
            $startDate = Date('Y-m-d');
            $newRow['startdate'] = $startDate;
            $endDate = Date('Y-m-d', strtotime('+' . $duration . ' days'));
            $newRow['enddate'] = $endDate;
            $newRow['status'] = 1;
            $newRow = $this->converteArrayParaUtf8($newRow);

            //Activar licença nova licença
            $sql = "UPDATE licenseassigned SET status = 1, startdate = '" . $startDate . "', \n"
                    . "enddate = '" . _rsaCryptGeneral::getInstance()->encryptRSA_general($endDate) . "' \n"
                    . "WHERE id = '" . $newLicenseId . "'; \n";
            $this->query($sql);
            return $newRow;
        }
        return array("status" => 0, "msg" => "Não foi encontrada uma licença válida.", "licenselevel" => 0);
    }

    public function licenseAssignedDesktopActivate($arrayLicenseInfo) {
        $licenseId = $this->real_escape_string($arrayLicenseInfo['licenseId']);
        $companyid = $this->real_escape_string($arrayLicenseInfo['companyid']);
        $serialnumber = $this->real_escape_string($arrayLicenseInfo['serialnumber']);
        $mac1 = $this->real_escape_string($arrayLicenseInfo['mac1']);
        $mac2 = $this->real_escape_string($arrayLicenseInfo['mac2']);
        $mac3 = $this->real_escape_string($arrayLicenseInfo['mac3']);
        $licensetype = $this->real_escape_string($arrayLicenseInfo['licensetype']);
        $statususer = $this->real_escape_string($arrayLicenseInfo['statususer']);


        $duration = 3000;

        $startDate = Date('Y-m-d');
        $endDate = Date('Y-m-d', strtotime('+' . $duration . ' months'));

        $sql = "UPDATE licenseassigned SET status = 1, startdate = '" . $startDate . "', \n"
                . "enddate = '" . _rsaCryptGeneral::getInstance()->encryptRSA_general($endDate) . "', \n"
                . "servermacaddress = '" . $mac1 . ";" . $mac2 . ";" . $mac3 . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = '" . $licenseId . "'; \n";
        $this->query($sql);

        $commercial = ($licensetype == 1) ? "false" : "true";
        $txtContent = $licenseId . ";" . $commercial . ";" . $companyid . ";"
                . macToCode($mac1) . ";" . macToCode($mac2) . ";" . macToCode($mac3) . ";"
                . $duration . ";"
                . date_diff(date_create('1990-01-01'), date_create($startDate))->format('%a') . ";"
                . date_diff(date_create('1990-01-01'), date_create($endDate))->format('%a') . ";"
                . $licensetype . ";";

        $myfile = fopen("../_lic/" . $serialnumber . ".lic", "w") or die("Unable to open file!");
        fwrite($myfile, $txtContent);
        fclose($myfile);


        return json_encode(array("status" => 1, "msg" => "Licença activada com sucesso."));
    }

    public function complaintSave($arrayComplaintInfo) {

        $complaintid = $this->real_escape_string($arrayComplaintInfo['id']);
        $companyid = $this->real_escape_string($arrayComplaintInfo['companyid']);
        $dependencyid = $this->real_escape_string($arrayComplaintInfo['dependencyid']);
        $complaintdate = $this->real_escape_string($arrayComplaintInfo['complaintdate']);
        $reference = $this->real_escape_string($arrayComplaintInfo['reference']);
        $category = $this->real_escape_string($arrayComplaintInfo['category']);
        $type = $this->real_escape_string($arrayComplaintInfo['type']);
        $treatment = $this->real_escape_string($arrayComplaintInfo['treatment']);
        $complaintsource = $this->real_escape_string($arrayComplaintInfo['complaintsource']);
        $customerid = $this->real_escape_string($arrayComplaintInfo['customerid']);
        $details = $this->real_escape_string($arrayComplaintInfo['details']);
        $complaintstatus = $this->real_escape_string($arrayComplaintInfo['complaintstatus']);
        $statususer = $this->real_escape_string($arrayComplaintInfo['statususer']);
        $request = $this->real_escape_string($arrayComplaintInfo['request']);
        $nextStatus = $this->real_escape_string($arrayComplaintInfo['nextStatus']);
        $registerNumber = round(microtime(true) * 1000) . $this->getRandomString(20);

        $sql = "";
        $partSQl = "";
        if (!is_numeric($complaintid)) {
            $sql .= "INSERT INTO complaint (\n"
                    . "companyid, dependencyid,  \n"
                    . "customerid, complaintstatus, entryuser, statususer, \n"
                    . "request, registernumber) \n"
                    . "SELECT '" . $companyid . "', '" . $dependencyid . "', "
                    . "'" . $customerid . "', '1', '" . $statususer . "', '" . $statususer . "', "
                    . "'" . $request . "', '" . $registerNumber . "'  \n"
                    . "FROM insert_table; ";
            $complaintid = "(SELECT id FROM complaint WHERE registernumber = '" . $registerNumber . "' LIMIT 1) ";
        } else {
            $complaintid = "'" . $complaintid . "'";
            if ($nextStatus != -1) {
                $partSQl = "complaintstatus = '" . $nextStatus . "', \n";
            }
        }

        $sql .= "UPDATE complaint SET \n"
                . $partSQl
                . "complaintdate = '" . $complaintdate . "', "
                . "reference = '" . $reference . "', "
                . "category = '" . $category . "', "
                . "type = '" . $type . "', "
                . "treatment = '" . $treatment . "', "
                . "complaintsource = '" . $complaintsource . "', "
                . "details = '" . $details . "', "
                . "statususer = '" . $statususer . "', "
                . "statusdate = NOW() \n"
                . "WHERE id = " . $complaintid . "; \n";

        $sql .= "SELECT id \n"
                . "FROM complaint \n"
                . "WHERE id = " . $complaintid . "; \n";

        $saved = false;
        if ($this->multi_query($sql)) {
            do {
                if ($result = $this->store_result()) {
                    while ($row = mysqli_fetch_array($result)) {
                        $saved = true;
                    }
                    $result->free();
                }
            } while ($this->next_result());
        }

        if (!$saved) {
            return json_encode(array("status" => 0, "msg" => "Não foi possível guardar a reclamação."));
        }
        return json_encode(array("status" => 1, "msg" => "Reclamação guardada com sucesso"));
    }

    public function complaintGetList($arrayFilterInfo) {

        $companyId = $this->real_escape_string($arrayFilterInfo['companyId']);
        $dependencyId = $this->real_escape_string($arrayFilterInfo['dependencyId']);
        $filterByDate = $this->real_escape_string($arrayFilterInfo['filterByDate']);
        $initialDate = $this->real_escape_string($arrayFilterInfo['initialDate']);
        $endDate = $this->real_escape_string($arrayFilterInfo['endDate']);
        $customerid = $this->real_escape_string($arrayFilterInfo['customerid']);
        $userId = $this->real_escape_string($arrayFilterInfo['userId']);
        $onlynumber = $this->real_escape_string($arrayFilterInfo['onlynumber']);
        $startIdx = $this->real_escape_string($arrayFilterInfo['startIdx']);
        $endIdx = $this->real_escape_string($arrayFilterInfo['endIdx']);


        $where = "";
        $limit = "";
        if ($dependencyId != -1) {
            $where .= " AND CP.dependencyid = '" . $dependencyId . "' ";
        }
        if ($userId != -1) {
            $where .= " AND CP.entryuser = '" . $userId . "' ";
        }
        if ($filterByDate == 1) {
            $where .= " AND  ( CAST(CP.complaintdate AS DATE) BETWEEN (CAST('" . $initialDate . "' AS DATE)) AND (CAST('" . $endDate . "' AS DATE))) ";
        }
        if ($customerid != -1) {
            $where = " AND CP.customerid = '" . $customerid . "' ";
        } else {
            $limit = "LIMIT " . $startIdx . ", " . ($endIdx - $startIdx) . " ";
        }

        $fields = "CP.id, CP.complaintdate, CP.reference, CP.category, CP.type, \n"
                . "CP.customerid, C.companyname, C.telephone1, C.telephone2, C.telephone3, C.email, "
                . "CP.complaintstatus, CP.statusdate, CP.statususer, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CP.statususer LIMIT 1) AS operatorName \n";

        if ($onlynumber == 1) {
            $fields = " COUNT(CP.id) ";
            $orderBy = "";
            $limit = "";
        } else {
            $orderBy = "ORDER BY complaintdate DESC \n";
        }

        $sql = "SELECT " . $fields . " \n"
                . "FROM complaint AS CP, customer AS C \n"
                . "WHERE CP.customerid = C.customerid AND CP.companyid = '" . $companyId . "'   " . $where
                . $orderBy
                . $limit
                . "; ";

        $result = $this->query($sql);

        if ($onlynumber == 1) {
            return array("n" => mysqli_fetch_array($result)[0]);
        }

        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    public function complaintGetDetails($complaintId) {

        $complaintId = $this->real_escape_string($complaintId);


        $sql = "SELECT CP.id, CP.complaintdate, CP.reference, CP.category, CP.type, \n"
                . "CP.treatment, CP.details, CP.complaintsource, "
                . "CP.customerid, C.companyname, C.telephone1, C.telephone2, C.telephone3, C.email, "
                . "CP.complaintstatus, CP.statusdate, CP.statususer, CP.entrydate, CP.entryuser, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CP.entryuser LIMIT 1) AS operatorEntry, \n"
                . "(SELECT U.userfullname FROM systemuser AS U WHERE U.userid = CP.statususer LIMIT 1) AS operatorName \n"
                . "FROM complaint AS CP, customer AS C \n"
                . "WHERE CP.customerid = C.customerid AND CP.id = '" . $complaintId . "' ; ";

        $result = $this->query($sql);

        while ($row = mysqli_fetch_array($result)) {
            return $this->converteArrayParaUtf8($row);
        }
        mysqli_free_result($result);
    }

    public function getDefaultValues($systemType, $fieldType) {
        $systemType = $this->real_escape_string($systemType);
        $fieldType = $this->real_escape_string($fieldType);

        $sql = "SELECT UPPER(itemvalue) AS itemvalue \n"
                . "FROM defaultvalue  \n"
                . "WHERE systemtype = '" . $systemType . "' AND fieldtype = '" . $fieldType . "'  \n"
                . "ORDER BY itemvalue;";

        $result = $this->query($sql);
        $arrayResult = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($arrayResult, json_encode($row));
        }
        mysqli_free_result($result);
        return $arrayResult;
    }

    /* FUNÇÃO ALISTAR DADOS NA TABELA DE ESCALA DE TRABALHO */

    public function employeeGetDescription($strEmployeeID) {

        $strEmployeeID = $this->real_escape_string($strEmployeeID);

        $sql = "SELECT * \n"
                . "FROM hremployee \n"
                . "WHERE id = '" . $strEmployeeID . "' \n";
        return $this->query($sql);
    }

    /* FUNÇÃO ALISTAR DADOS NA TABELA da pop-up DE ESCALA DE TRABALHO */

    public function funcionarioGetFuncList($companyId, $txtFuncionario = -1, $searchLimit = -1) {

        $companyId = $this->real_escape_string($companyId);
        $txtFuncionario = $this->real_escape_string($txtFuncionario);
        $searchLimit = $this->real_escape_string($searchLimit);

        $where = "";
        if ($txtFuncionario != -1) {
            $where .= " AND P.txtFuncionario = '" . $txtFuncionario . "' ";
        }
        $limit = "";
        if ($searchLimit >= 1) {
            $limit = "LIMIT " . $searchLimit . " \n";
        }


        $sql = "SELECT *, \n"
                . "(SELECT PS.section FROM productsection AS PS WHERE PS.id = P.productsection LIMIT 1) AS strproductsection, \n"
                . "(SELECT PC.category FROM productivacategory AS PC WHERE PC.id = P.productIvaCategory LIMIT 1) AS strproductIvaCategory, "
                . "(COALESCE((SELECT PP.pvp FROM productprice AS PP WHERE PP.productid = P.id LIMIT 1), 0)) AS PVP, \n"
                . "productstock, productweight, productnetweight \n"
                . "FROM product AS P \n"
                . "WHERE P.companyid = '" . $companyId . "' " . $where . " \n"
                . "ORDER BY productdescription \n"
                . $limit . "; ";

        $result = $this->query($sql);

        $array = array();
        while ($row = mysqli_fetch_array($result)) {
            $row = $this->converteArrayParaUtf8($row);
            array_push($array, json_encode($row));
        }
        mysqli_free_result($result);
        return $array;
    }

}
