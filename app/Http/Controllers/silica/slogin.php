<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;
use Sienekib\Mehael\Http\Request;

class slogin extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == "POST") {
            $action = "";
            if ($request->action) {
                $action = $request->action;

                if ($action == "getSystemUserInfo") {
                    $userName = $request->username;
                    $passWord = $request->pass;
                    $whoCall = $request->whoCall;
                    //   $openUser = $_POST['openUser'];
                    $sessionId = round(microtime(true) * 1000);
                    // setcookie("SAquaCookieByNowEntryOut", $sessionId, 0, "/");
                    session()->set("SAquaCookieByNowEntryOut", $sessionId);
                    session()->set('LAST_LOGIN', time());

                    $logonSuccess = _aqdb::getInstance()->check_credentials($userName, $passWord, $whoCall, $sessionId);

                    echo json_encode($logonSuccess); exit;

                    if ($logonSuccess['status'] != false) {
                        echo json_encode(
                            array(
                                "status" => $logonSuccess['status'], 
                                "defaultPass" => $logonSuccess['defaultPass'], 
                                "userid" => $logonSuccess['userid']
                            )
                        );
                    } else {
                        echo json_encode(
                            array(
                                "status" => 0, 
                                "defaultPass" => 0, 
                                "userid" => -1
                            )
                        );
                    }
                }


                if ($action == "logoutUser") {
                    if (session()->has("SAquaCookieByNowEntryOut")) {
                        $sessionId = session()->get("SAquaCookieByNowEntryOut");
                        $logonSuccess = _aqdb::getInstance()->systemUserLogout($sessionId);
                    }
                    return false;
                }


                if ($action == "validateUsernameEmail") {
                    $userName = $request->username;
                    $email = $request->email;

                    echo _aqdb::getInstance()->systemUserRequestNewPass($userName, $email);
                    return false;
                }
            }
        }



    }
}


