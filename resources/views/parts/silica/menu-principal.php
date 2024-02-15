
<head>
    <?php
    //include_once './_mainHeader/_headerFunctions.php';

    $systemUserId = -1;
    $systemUserFullName = "Precisa fazer login.";
    $companyId = -1;
    $needReCheck = 1;
    $licenseLevel = 0;
    $outOfWorkTime = 0;
    //Close after 30min
    if (isset($_SESSION['LAST_LOGIN'])) {
        if (time() - $_SESSION['LAST_LOGIN'] > 1800) {
            //  session_unset();
            // session_destroy();
        }
    }
    //Close after 10min without new page
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        if (time() - $_SESSION['LAST_ACTIVITY'] > 3600) {
            $_SESSION['SMS_NEED_RECHECK'] = 1;
        }
    }
    $_SESSION['LAST_ACTIVITY'] = time();

    if (isset($_SESSION['SMS_NEED_RECHECK'])) {
        $needReCheck = $_SESSION['SMS_NEED_RECHECK'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['frc']) && !empty($_GET['frc'])) {
            if ($_GET['frc'] == 1) {
                $needReCheck = 1;
            }
        }
    }

    // check last session
    if ($needReCheck) {
        if (isset($_SESSION["SAquaCookieByNowEntryOut"])) {
            $sessionId = $_SESSION["SAquaCookieByNowEntryOut"];
            //include_once './_aqconections/_conectdb.php';
            $logonSuccess = App\Http\Controllers\silica\_aqdb::getInstance()->systemUserNowState($sessionId);
            if ($logonSuccess['status'] != false) {
                $logonSuccess['sessionid'] = $sessionId;
                $_SESSION["smsCookieByDataSystemUser"] = json_encode($logonSuccess);
                //$systemUserId = $logonSuccess['id'];
                //$systemUserFullName = $logonSuccess['userfullname'];
                //  $systemUserBillingProfile = $logonSuccess['billingprofile'];
                // $coordinatorUser = $logonSuccess['coordinatoruser'];
                //  $managerUser = $logonSuccess['manageruser'];
                //  $directorGeneralUser = $logonSuccess['directorgeneraluser'];
                //  $administratorUser = $logonSuccess['administratoruser'];
                $companyId = $logonSuccess['companyid'];
                $workplaceId = $logonSuccess['workplaceid'];
                // $dependencyName = $logonSuccess['dependency'];
                //  $municipalityId = $logonSuccess['municipalityid'];
                //  $municipalityName = $logonSuccess['municipalityname'];
                // $provinceId = $logonSuccess['provinceid'];
                //  $provinceName = $logonSuccess['provinceName'];

                $result = headerGetCompanyInfo($companyId);
                $_SESSION["smsCookieByDataCompanyInfo"] = json_encode($result);

                $license = App\Http\Controllers\silica\_aqdb::getInstance()->licenseGetLicenseStatus($companyId);
                $licenseLevel = $license['licenselevel'];
                $_SESSION["smsCookieByDataLicenseInfo"] = json_encode($license);

                $dependencyInfo = App\Http\Controllers\silica\_aqdb::getInstance()->companyDependecyGetList($companyId, $workplaceId, true);
                $_SESSION["smsCookieByDataDependencyInfo"] = json_encode($dependencyInfo);

                $workschedule = App\Http\Controllers\silica\_aqdb::getInstance()->companyWorkScheduleGet($companyId, $workplaceId, date('w'));
                $_SESSION["smsCookieByDataWorkSchedule"] = json_encode($workschedule);

                $_SESSION['SMS_NEED_RECHECK'] = 0;
            } else {
                unset($_SESSION['smsCookieByDataSystemUser']);
            }
        }
    }
    if (isset($_SESSION["SAquaCookieByNowEntryOut"])) {
        if (!empty($_SESSION["SAquaCookieByNowEntryOut"])) {
            if (isset($_SESSION["smsCookieByDataSystemUser"])) {
                $ustatus = json_decode($_SESSION["smsCookieByDataSystemUser"], true);
                $systemUserId = $ustatus['userid'];
                $systemUserFullName = $ustatus['userfullname'];
                $companyId = $ustatus['companyid'];
                $billingprofile = $ustatus['billingprofile'];

                $result = json_decode($_SESSION["smsCookieByDataCompanyInfo"], true);

                $license = json_decode($_SESSION["smsCookieByDataLicenseInfo"], true);
                $licenseLevel = $license['licenselevel'];

                $dependencyInfo = json_decode($_SESSION["smsCookieByDataDependencyInfo"], true);

                $companyName = $result['companyname'] . " (" . $companyId . ")";
                $companyTaxId = "NIF: " . $result['companytaxid'];
                $companyIvaGroup = $result['ivagroup'];
                $companyLogo = ($result['logofilename'] != "") ? $result['logopath'] . $result['logofilename'] : "";

                if ($billingprofile == 1) {
                    $workschedule = json_decode($_SESSION["smsCookieByDataWorkSchedule"], true);
                    if ($workschedule['useworkschedule'] == 1) {
                        $startTime = date("H:i", strtotime($workschedule['starttime']));
                        $endTime = date("H:i", strtotime($workschedule['endtime']));
                        if ((time() < strtotime($startTime)) || (time() > strtotime($endTime))) {
                            $outOfWorkTime = 1;
                        }
                    }
                }
            }
        }
    }



    if ($systemUserId == -1) {
        $chkPermission = -1;
    } else {
        if ($outOfWorkTime == 1) {
            $chkPermission = -2;
        } else {
            $subPerm = substr($pagePermission, 0, 2);
            if ($subPerm < 50) {
                $chkPermission = checkPermissionForPage($systemUserId, $pagePermission, $licenseLevel);
            } else {
                if ($companyId != 5000) {
                    $chkPermission = 0;
                } else {
                    $chkPermission = checkPermissionForPageGME($systemUserId, $pagePermission);
                }
            }
        }
    }


    session()->set("smsCookieByDataPagePermission", $chkPermission);
    ?>
    <link rel="stylesheet" href="<?= asset('silica/css/colors.css') ?>"/>
    <link rel="stylesheet" href="<?= asset('silica/css/estiloMenu.css')?>"/>
</head>

<div id="divMenuPrincipal" class="noPrint" >  

    <div style="text-align: center">
        <div style="display: flow; margin-left: auto; margin-right: auto; "> 
            <img id="imgHeaderUserLogedPhoto" src="" alt="Avatar" class="imgUserLoged" onclick="showHideDivUserInfo('');">  
        </div>   
        <label id="lbHeaderUserFullname" style=" color: white; transition: display 1s;"></label>
        <label id="lbHeaderSessionTime" 
               style="font-style: italic; color: white; position: absolute; left: 16px; top: 20px; transition:font-size 1s; background-color: var(--colorPrimary); border: 0px; border-radius: 5px; padding: 3px; box-shadow: 1px 1px 1px black; transition: display 1s;">
            00:00
        </label>
    </div>

    <div id="divUserInfo" class="divUserInfo" >
        <div>
            <h4 id="lbHeaderUserFullname2" class="labelUserBlack" style="font-size: large; transition: display 1s;"></h4>
        </div>
        <!--    <button class="userButton" onclick="showParts('divMenuSilica'); hideParts('divMenuSilicaGestor');">Menu Principal</button>
            <button class="userButton" onclick="showParts('divMenuSilicaGestor'); hideParts('divMenuSilica');">Menu Gestor</button>-->
        <button id="btHeaderConfirmEmail" class="userButton" onclick="systemUserSendConfirmationEmail('');" style="display: none;">
            <b>Importante: </b>Precisa confirmar o seu e-mail.
        </button>
        <button type="button" class="userButton" onclick="window.location.assign('../../../ssetting/app/User/UserDashBoard');">Painel do operador</button>
        <button type="button" class="userButton" onclick="window.location.assign('../../../ssetting/app/User/UserRegister?sender=UTIINFO');">Dados Pessoas</button>
        <button type="button" class="userButton" onclick="window.location.assign('../../../ssetting/app/User/UserPassword');">Alterar palavra-passe</button>
        <button type="button" class="userButton" onclick="lockWindowShow();">Bloquear ecrã</button>
        <button type="button" class="userButton" onclick="systemUserLogOut();">Sair do Sistema</button>
    </div>

    <div class="" style="display: flex;">
        <div id="divMenuSilicaUniverse">
            <?= universeSilicaMenuConstroctor($companyId); ?>
        </div>

        <nav id="navMenuPrincipal" class="menuPrincipal">
            <ul class="menuPrincipalSection">
                <li><a href="./home.php?frc=1"> Página Inicial </a></li>
                <?= mainMenuConstrutor($systemUserId, $companyId, false, '0000', $licenseLevel);?>
            </ul> 
        </nav> 
    </div>
</div>

