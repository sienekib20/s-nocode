<?php

use Sienekib\Mehael\Bootstrap\Application;
use Sienekib\Mehael\Http\Response;
use Sienekib\Mehael\Support\Flash;
use Sienekib\Mehael\Support\Session;
use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Http\Src\Redirect;
use Sienekib\Mehael\Support\Auth;
use Sienekib\Mehael\Template\View;

if (!function_exists('auth')) {
    function auth()
    {
        return (new Auth());
    }
}

if (!function_exists('app')) :

    // A instância da classe Application

    function app(): Application
    {
        static $instance = null;
        if ($instance == null) {
            $instance = (new Application());
        }
        return $instance;
    }
endif;

if (!function_exists('abs_path')) :
    function abs_path()
    {
        return dirname(__DIR__, 2);
    }
endif;

if (!function_exists('env')) :
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
endif;

if (!function_exists('view_path')) :
    function view_path()
    {
        return abs_path() . '/resources/views';
    }
endif;

if (!function_exists('srepository')) :
    function srepository_path()
    {
        return  abs_path() . '/public/srepository/';
    }
endif;

if (!function_exists('storage_path')) :
    function storage_path()
    {
        return abs_path() . '/public/storage/';
    }
endif;


if (!function_exists('storage')) :

    function storage($path = '')
    {
        $path = ltrim($path, '/');
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);

        return  '/storage/' . $path;
    }

endif;

if (!function_exists('view')) :
    function view(string $view, array $params = [])
    {
        return View::render($view, $params);
    }
endif;

if (!function_exists('redirect')) :
    function redirect()
    {
        return new Redirect();
    }
endif;

if (!function_exists('response')) :
    function response()
    {
        return (new Response());
    }
endif;


if (!function_exists('parts')) :

    function parts($part)
    {
        $path = view_path() . '/parts/';
        if (str_contains($part, '.')) {
            $parts_view = explode('.', $part);
            foreach ($parts_view as $view) {
                if (is_dir($path . $view)) {
                    $path .= $view . '/';
                }
            }
            $path .= end($parts_view) . '.php';
        } else {
            $path .= $part . '.php';
        }

        try {
            if (!file_exists($path)) {
                throw new \Exception("Arquivo {$path} não encontrado");
            }
            ob_start();
            include $path;
            $output = ob_get_contents();
            ob_end_clean();
            echo $output;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            response()->setStatusCode(404);
            exit;
        }
    }

endif;

if (!function_exists('asset_path')) :

    function asset_path()
    {
        $asset_path = (parse_url($_SERVER['REQUEST_URI']) == '/')
            ? '/assets/'
            : env('APP_URL') . '/' . basename(abs_path()) . '/public/assets/';

        return  '/assets/';
    }

endif;



if (!function_exists('asset')) :

    function asset($asset)
    {
        $fileType = explode('/', $asset)[0];

        return asset_path() . $asset;
    }

endif;

if (!function_exists('__template')) :

    function __template(string $path = '')
    {
        return '/templates/' . ltrim($path, '/');
    }

endif;

if (!function_exists('__template_path')) :

    function __template_path(string $path = '')
    {
        return abs_path() .  '/public/templates/' . ltrim($path, '/');
    }

endif;

if (!function_exists('request')) :

    function request()
    {
        return (new Request());
    }

endif;

if (!function_exists('session')) :

    function session()
    {
        return (new Session());
    }

endif;

if (!function_exists('flash')) :

    function flash()
    {
        return (new Flash());
    }

endif;

if (!function_exists('route')) :

    function route(string $route, ...$bind)
    {
        if (str_contains($route, '.')) {
            $route = str_replace('.', '/', $route);
        }
        if ($route == '/') {
            return (!empty($bind)) ? "$route/$bind[0]" : "$route";
        }
        $route = "/$route";
        if (!empty($bind)) {
            foreach ($bind as $param) {
                $route .= "/$param";
            }
        }
        return $route;
    }

endif;

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
    }
}


/*

Silica
 */
function monthName($month)
{
    $month = (int) $month;
    $monthName = array();
    $monthName[1] = "Janeiro";
    $monthName[2] = "Fevereiro";
    $monthName[3] = "Marco";
    $monthName[4] = "Abril";
    $monthName[5] = "Maio";
    $monthName[6] = "Junho";
    $monthName[7] = "Julho";
    $monthName[8] = "AGosto";
    $monthName[9] = "Setembro";
    $monthName[10] = "Outubro";
    $monthName[11] = "Novembro";
    $monthName[12] = "Dezembro";

    return $monthName[$month];
}

function monthQuarter($currDay)
{
    $currQuarter = 1;
    if ($currDay < 8) {
        $currQuarter = 1;
    } elseif ($currDay < 15) {
        $currQuarter = 2;
    } elseif ($currDay < 22) {
        $currQuarter = 3;
    } elseif ($currDay < 29) {
        $currQuarter = 4;
    } elseif ($currDay < 32) {
        $currQuarter = 5;
    }
    return $currQuarter;
}

function strField($field, $extraCoalesce = 1, $ivaCoalesce = 0)
{
    $coalesce = $extraCoalesce ? "(SELECT " . $field . " FROM productpricepercentsuggest AS PPS WHERE PPS.companyid = P.companyid LIMIT 1), " : "";
    if ($ivaCoalesce == 1) {
        $coalesce = "(SELECT PC.productiva FROM productivacategory AS PC WHERE PC.id = P.productivacategory LIMIT 1), ";
    }
    return ", (COALESCE((SELECT " . $field . " FROM productprice AS PP WHERE PP.productid = P.id LIMIT 1), "
        . $coalesce . " 0)) AS " . $field . " \n";
}

function randomForSerial($stringSize)
{
    $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $stringSize; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function generateSerialNumber()
{

    $number = round(microtime(true) * 1000);

    return randomForSerial(2) . substr($number, 10) . "-"
        . randomForSerial(1) . substr($number, 0, 3) . randomForSerial(1) . "-"
        . randomForSerial(1) . substr($number, 6, 4) . "-"
        . substr($number, 3, 3) . randomForSerial(2);
}

function macToCode($mac)
{
    if (strlen($mac) <= 0) {
        return "";
    }
    $code = array(
        "A" => 10, "B" => 11, "C" => 12, "D" => 13, "E" => 14, "F" => 15,
        "0" => 16, "1" => 17, "2" => 18, "3" => 19, "4" => 20, "5" => 21,
        "6" => 22, "7" => 23, "8" => 24, "9" => 25
    );
    $newMac = "";

    for ($index = 0; $index < strlen($mac); $index++) {
        $newMac .= $code[substr($mac, $index, 1)];
    }
    return $newMac;
}

function systemUserDocsPath()
{
    $path =  srepository_path() . "_attachs/_userPhoto/";
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

function companyDocsPath()
{
    $path =  srepository_path() . "_attachs/_companyLogos/";
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

function productPhotoPath()
{
    $path =  srepository_path() . "_attachs/_productPhoto/";
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    return $path;
}

function names($field)
{
    return ", (SELECT U.userfullname FROM systemuser AS U WHERE U.userid = SU." . $field . " LIMIT 1) AS " . $field . "Name \n";
}


function headerGetCompanyInfo($companyid)
{
    return App\Http\Controllers\silica\_aqdb::getInstance()->companyGetInfo($companyid);
    // /* if ($result->num_rows > 0) {
    //       $row = mysqli_fetch_array($result); /* ->fetch_row(); */
    //      return $row;
    //  } else {
    //      return false;
    //  }*/
}

function checkPermissionForPage($userId, $pagePermission, $licenseLevel)
{

    if (!is_numeric($userId)) {
        return -1;
    }
    if ($userId < 1) {
        return -1;
    }
    if (strlen($pagePermission) < 4) {
        return 0;
    }

    $result = App\Http\Controllers\silica\_aqdb::getInstance()->systemUserGetPermissionListNoJason($userId, "", $licenseLevel);
    $arrayPermission = array();
    $permissionForJS = array();
    while ($row = mysqli_fetch_array($result)) {
        $arrayPermission[$row['permissionCode']] = $row;
        $permissionForJS["p" . $row['permissionCode']] = $row['permissionstatus'];
    }
    mysqli_free_result($result);
    $_SESSION["smsCookieByDataSystemUserPermission"] = json_encode($permissionForJS);

    if ($pagePermission == "0000") {
        return 1;
    }

    $permissions = explode("-", $pagePermission);
    foreach ($permissions as $value) {
        if (!array_key_exists($value, $arrayPermission)) {
            return 0;
        }
    }
    $havePerm = false;
    foreach ($permissions as $value) {
        if ($arrayPermission[$value]['permissionstatus'] == 1) {
            $havePerm = true;
        }
    }
    if (!$havePerm) {
        return 0;
    }
    return 1;
}

function headerGetUdaoxInfo()
{
    /*$result = App\Http\Controllers\silica\_aqdb::getInstance()->udaoxGetCompanyInfo();
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_array($result); 
        return $row;
    } else {
        return false;
    }*/
}

function checkPermissionForPageGME($userId, $pagePermission)
{

    if (!is_numeric($userId)) {
        return -1;
    }
    if ($userId < 1) {
        return -1;
    }
    if (strlen($pagePermission) < 4) {
        return 0;
    }

    $result = App\Http\Controllers\silica\_aqdb::getInstance()->systemUserGetPermissionListNoJasonGME($userId);
    $arrayPermission = array();
    $permissionForJS = array();
    while ($row = mysqli_fetch_array($result)) {
        $arrayPermission[$row['permissionCode']] = $row;
        $permissionForJS["p" . $row['permissionCode']] = $row['permissionstatus'];
    }
    mysqli_free_result($result);
    $_SESSION["smsCookieByDataSystemUserPermission"] = json_encode($permissionForJS);

    if ($pagePermission == "0000") {
        return true;
    }

    $permissions = explode("-", $pagePermission);
    foreach ($permissions as $value) {
        if (!array_key_exists($value, $arrayPermission)) {
            header('Location: home.php');
        }
    }
    $havePerm = false;
    foreach ($permissions as $value) {
        if ($arrayPermission[$value]['permissionstatus'] == 1) {
            $havePerm = true;
        }
    }
    if (!$havePerm) {
        return 0;
    }
    return true;
}


/*
Menu functions
 */

function menuItemOpen($label, $href = "", $aClass = "")
{
    if ($href != "") {
        $href = 'href="' . $href . '"';
    }
    //  $label = strtoupper(trim($label));
    $pattern = "/[^A-z0-9]/i";
    $menuId = "menu" . preg_replace($pattern, "", $label) . "id";
    $aId = "a" . preg_replace($pattern, "", $label) . "id";
    $ulId = "ul" . preg_replace($pattern, "", $label) . "id";
    $onClick = 'onclick="subMenuShow(' . "'" . $menuId . "', '" . $aId . "', '" . $ulId . "' " . ');"';
    $munuItem = '<li value=0 id="' . $menuId . '" ' . $onClick . '><a class="menuItem ' . $aClass . '" id="' . $aId . '" ' . $href . '>' . $label . '<span class="sub-arrow"> </span></a>'
        . '<ul id="' . $ulId . '">';
    return $munuItem;
}

function menuItemClose()
{
    $munuItem = '</ul>'
        . '</li>';
    return $munuItem;
}

function subMenuItem($label, $href = "", $aClass = "")
{
    $href = str_replace(" ", "", $href);
    if ($href != "") {
        $href = 'href="' . $href . '"';
    } else {
        $aClass .= ' noProcess';
    }
    $label = ucwords($label);
    $subMenu = '<li><a class="subMenuItem labelArrowRight ' . $aClass . '"  ' . $href . '>' . $label . '</a></li>';
    return $subMenu;
}

function usMenuItem($label, $href = "", $aClass = "")
{
    $href = str_replace(" ", "", $href);
    if ($href != "") {
        $href = 'href="' . $href . '"';
    } else {
        $aClass .= ' noProcess';
    }
    $subMenu = '<a class="content-icon-silica"  ' . $href . '><div class="' . $aClass . '"></div><span class="text-icon-silica">' . $label . '</span></a>';
    return $subMenu;
}

function checkPermission($arrayPPs, $pp = 1)
{
    // foreach ($arrayPPs as $value) {
    $ppS = explode('-', $pp);
    foreach ($ppS as $value) {
        if (array_key_exists($value, $arrayPPs)) {
            if ($arrayPPs[$value]['permissionstatus'] == 1) {
                return 1;
            }
        }
        //  }
    }
    return 0;
}

function mainMenuConstrutor($userId, $companyId, $menuBlock = false, $pagePermission = "0000", $licenseLevel = 1)
{
    $mainMenu = '';
    $headerMenu = "";

    $result = \App\Http\Controllers\silica\_aqdb::getInstance()->systemUserGetPermissionListNoJason($userId, "", $licenseLevel);
    $arrayPermission = array();
    while ($row = mysqli_fetch_array($result)) {
        $arrayPermission[$row['permissionCode']] = $row;
    }
    mysqli_free_result($result);

    /* SOLICITAÇÃO DE LIGAÇÃO */
    $permissinsList = '3001-3002-3003-3010-3011';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("SOLICITAÇÃO DE LIGAÇÃO", "", "labelLink");
        $menuLigacao = $openMenu;
        if (checkPermission($arrayPermission, '3001-3002-3003') == 1) {
            $menuLigacao .= subMenuItem("Lista de solicitações de ligação", "LinkRequestList.php");
        }
        if (checkPermission($arrayPermission, '3010-3011') == 1) {
            $menuLigacao .= subMenuItem("Lista de vistoria", "InspectionList.php");
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuLigacao);
        }
        $menuLigacao .= menuItemClose();
        $mainMenu .= $menuLigacao;
    }


    /* CLIENTE */
    $permissinsList = "0100-0101-0102-0105";
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen('Cliente', '', 'labelCustomer');
        $menuCLiente = $openMenu;
        if (checkPermission($arrayPermission, '0100-0101') == 1) {
            ///**/$menuCLiente .= subMenuItem("Cadastro de cliente em lote", "CustomerRegisterLote.php");
            /**/
            $menuCLiente .= subMenuItem("Lista de cliente", "CustomerList.php"); //
        }
        if (checkPermission($arrayPermission, '0101') == 1) {
            /**/
            $menuCLiente .= subMenuItem("Conta Corrente", "CustomerCurrentAccount.php");
            /**/
            $menuCLiente .= subMenuItem("Lista de devedores", "CustomerBadPayList.php");
        }
        if (checkPermission($arrayPermission, '0102') == 1) {
            /**/
            $menuCLiente .= subMenuItem("Histórico de cliente", "CustomerHistoric.php");
        }
        if (checkPermission($arrayPermission, '0105') == 1) {
            //**/$menuCLiente .= subMenuItem("", "CustomerAssignToService.php", "dictonaryCustomerAssignToService");
            //**/$menuCLiente .= subMenuItem("", "CustomerQueryByService.php", "dictonaryCustomerQueryByService");
        }
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuCLiente);
        }
        $menuCLiente .= menuItemClose();
        $mainMenu .= $menuCLiente;
    }

    /* ContratoS */
    $permissinsList = '3101-3102-3103';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("Contrato de ligação de água", "", "labelContract");
        $menuContracto = $openMenu;
        //**/$menuContracto .= subMenuItem("Cadastro de Contratos em lote", "ContractRegisterLote.php");
        /**/
        $menuContracto .= subMenuItem("Lista de Contratos de ligação de água", "ContractList.php");
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuContracto);
        }
        $menuContracto .= menuItemClose();
        $mainMenu .= $menuContracto;
    }

    /* CONTADOR E LEITURA */
    $permissinsList = '3201-3202-3210-3211';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("CONTADOR E LEITURA", "", "labelHydrometer");
        $menuContador = $openMenu;
        if (checkPermission($arrayPermission, "3201-3202") == 1) {
            //**/$menuContador .= subMenuItem("Cadastrar de contador em lote", "HydrometerRegisterLote.php");
            /**/
            $menuContador .= subMenuItem("Lista de contadores", "HydrometerList.php");
            /**/
            $menuContador .= subMenuItem("Lista de contratos sem contadores", "ContractWithoutHydrometerList.php");
        }
        if (checkPermission($arrayPermission, "3210-3211") == 1) {
            //**/$menuContador .= subMenuItem("Importar leitura em lote", "HydrometerRecordLote.php");
            /**/
            $menuContador .= subMenuItem("Lista de leituras", "HydrometerRecordList.php");
        }
        if (checkPermission($arrayPermission, "3220-3221") == 1) {
            /**/
            $menuContador .= subMenuItem("Lista de estimativas de consumo", "ConsumptionEstimated.php");
        }
        if (checkPermission($arrayPermission, "3230-3231") == 1) {
            /**/
            $menuContador .= subMenuItem("Ligação de água", "ContractWaterLinktList.php");
        }
        if (checkPermission($arrayPermission, "3240") == 1) {
            /**/
            $menuContador .= subMenuItem("Definições de estimativa do consumo", "ConsuptionEstomatedDefault.php");
        }
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuContador);
        }
        $menuContador .= menuItemClose();
        $mainMenu .= $menuContador;
    }

    /* PRODUTO */
    $permissinsList = '0301-0302';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("PRODUTO / SERVIÇO", "", "labelProduct");
        $menuProduto = $openMenu;
        if (checkPermission($arrayPermission, '0301') == 1) {
            /**/
            $menuProduto .= subMenuItem("Secção de Produto / Serviço", "ProductSection.php");
            //**/$menuProduto .= subMenuItem("Categoria de Produto / Serviço", "ProductIvaCategory.php");
            /**/
            $menuProduto .= subMenuItem("Cadastro de Produto / serviço", "ProductRegister.php");
            ///**/$menuProduto .= subMenuItem("Cadastro de Produto / serviço em lote", "ProductRegisterLote.php");
            /**/
            $menuProduto .= subMenuItem("Lista de produtos / Serviços", "ProductList.php");
            /**/
            $menuProduto .= subMenuItem("Data de validade de produtos", "ProductExpirationList.php");
        }
        if (checkPermission($arrayPermission, '0302') == 1) {
            /**/
            $menuProduto .= subMenuItem("Formulação de preço", "ProductPrice.php");
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuProduto);
        }
        $menuProduto .= menuItemClose();
        $mainMenu .= $menuProduto;
    }

    /* ESTOQUE */
    $permissinsList = '0304-0306-0308';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("Gestão de estoque", "", "labelStock");
        $menuEstoque = $openMenu;
        if (checkPermission($arrayPermission, '0304') == 1) {
            /**/
            $menuEstoque .= subMenuItem("Entrada de Estoque", "ProductStockEntrance.php");
            /**/
            $menuEstoque .= subMenuItem("Entrada de Estoque (Lista)", "ProductStockEntranceList.php");
        }
        if (checkPermission($arrayPermission, '0306') == 1) {
            /**/
            $menuEstoque .= subMenuItem("Entregas pendentes", "ProductStockMovimentPending.php");
            /**/
            $menuEstoque .= subMenuItem("Saída de Estoque", "ProductStockMoviment.php");
            /**/
            $menuEstoque .= subMenuItem("Saída de Estoque (Lista)", "ProductStockMovimentList.php");
        }
        if (checkPermission($arrayPermission, '0308') == 1) {
            /**/
            $menuEstoque .= subMenuItem("Cadastrar Armazém", "ProductWarehouseRegister.php");
            /**/
            $menuEstoque .= subMenuItem("Estoque Actual (Existência / Valorização)", "ProductStockCurrent.php");
            /**/
            $menuEstoque .= subMenuItem("Relatório de Estoque", "ProductStockReport.php");
        }
        if (checkPermission($arrayPermission, '0304') == 1) {
            if (checkPermission($arrayPermission, '0306') == 1) {
                /**/
                $menuEstoque .= subMenuItem("Ajuste Manual do Estoque", "ProductStockManualAjust.php");
            }
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuEstoque);
        }
        $menuEstoque .= menuItemClose();
        $mainMenu .= $menuEstoque;
    }

    /* AGENDA */
    $permissinsList = '0501';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("MARCAÇÃO de actividade", "", "labelCalendar");
        $menuAgenda = $openMenu;
        /**/
        $menuAgenda .= subMenuItem("Lista de actividades", "ScheduleList.php");
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuAgenda);
        }
        $menuAgenda .= menuItemClose();
        $mainMenu .= $menuAgenda;
    }

    /* ENCOMENDA */
    $permissinsList = '0207-0232';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen('ENCOMENDA', "", "labelSaleOrder");
        $menuEncomenda = $openMenu;
        if (checkPermission($arrayPermission, '0207') == 1) {
            /**/
            $menuEncomenda .= subMenuItem("Encomenda", "SaleInvoice.php?invoicetype=5");
        }
        /**/
        $menuEncomenda .= subMenuItem("Encomenda (Lista)", "SaleOrderList.php");

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuEncomenda);
        }
        $menuEncomenda .= menuItemClose();
        $mainMenu .= $menuEncomenda;
    }




    /* VENDA */
    $permissinsList = '0201-0202-0203-0204-0205-0206-0220-0221-0230-0231-0233-0234-0290';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen('VENDA', "", "labelSale");
        $menuVenda = $openMenu;
        if (checkPermission($arrayPermission, '0201') == 1) {
            /**/
            $menuVenda .= subMenuItem("Venda a Dinheiro", "SaleInvoice.php?invoicetype=1");
        }
        if (checkPermission($arrayPermission, '0202') == 1) {
            $menuVenda .= subMenuItem("Factura de consumo", "SaleInvoiceConsumption.php?invoicetype=2");
        }
        if (checkPermission($arrayPermission, '0203') == 1) {
            /**/
            $menuVenda .= subMenuItem("Factura", "SaleInvoice.php?invoicetype=3");
        }
        if (checkPermission($arrayPermission, '0204') == 1) {
            /**/
            $menuVenda .= subMenuItem("Consulta de preço", "SaleInvoice.php?invoicetype=4");
        }
        if (checkPermission($arrayPermission, '0205') == 1) {
            /**/
            $menuVenda .= subMenuItem("Recibo", "SalePayment.php");
        }
        if (checkPermission($arrayPermission, '0206') == 1) {
            /**/
            $menuVenda .= subMenuItem("Adiantamneto", "SaleAdvance.php");
        }
        //
        if ((checkPermission($arrayPermission, '0201-0202-0203-0204-0205-0206') == 1)) {
            /**/
            $menuVenda .= subMenuItem("Fecho de caixa", "SaleReportSale.php");
            //**/$menuVenda .= subMenuItem("Relatório de Vendas (Pagamentos)", "");
            /**/
            $menuVenda .= subMenuItem("Factura não paga (Lista)", "SaleReportInvoiceMissingPayment.php");
            /**/
            $menuVenda .= subMenuItem("Pós Facturação", "SalePostSale.php?operationType=1");
        }
        if (checkPermission($arrayPermission, '0234') == 1) {
            /**/
            $menuVenda .= subMenuItem("Relatório de consumo", "SaleInvoiceConsumptionReport.php?");
            /**/
            $menuVenda .= subMenuItem("Relatório de Vendas", "SaleReportSaleByProduct.php");
            /**/
            $menuVenda .= subMenuItem("Lista de documentos", "SaleDocumentList.php?");
        }
        if ((checkPermission($arrayPermission, '0230') == 1) ||
            (checkPermission($arrayPermission, '0231') == 1) ||
            (checkPermission($arrayPermission, '0232') == 1) ||
            (checkPermission($arrayPermission, '0233') == 1)
        ) {
            //**/$menuVenda .= subMenuItem("Anular venda", "SalePostSale.php?operationType=3");
        }
        if (checkPermission($arrayPermission, '0221') == 1) {
            // $menuVenda.= subMenuItem("Devolução / troca", "");
        }
        if (checkPermission($arrayPermission, '0202') == 1) {
            //$menuVenda .= subMenuItem("Importar factura em lote", "SaleInvoiceImportLote.php");
        }
        if (checkPermission($arrayPermission, '0205') == 1) {
            //$menuVenda .= subMenuItem("Importar recibo em lote", "SalePaymentImportLote.php");
        }
        if (checkPermission($arrayPermission, '0290') == 1) {
            /**/
            $menuVenda .= subMenuItem("Definições de Faturação", "SaleBillingSetting.php");
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuVenda);
        }
        $menuVenda .= menuItemClose();
        $mainMenu .= $menuVenda;
    }



    /* FORNECEDOR */
    $permissinsList = '0401-0402';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("FORNECEDOR", "", "labelSupply");
        $menuFornecedor = $openMenu;
        /**/
        $menuFornecedor .= subMenuItem("Cadastro de Fornecedor", "SupplierRegister.php");
        if (checkPermission($arrayPermission, '0402') == 1) {
            //**/$menuFornecedor .= subMenuItem("Histórico de Fornecedor", "SupplierHistoric.php");
        }
        if (checkPermission($arrayPermission, '0401') == 1) {
            /**/
            $menuFornecedor .= subMenuItem("Lista de Fornecedor", "SupplierList.php");
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuFornecedor);
        }
        $menuFornecedor .= menuItemClose();
        $mainMenu .= $menuFornecedor;
    }
    //
    /* RECLAMAÇÃO */
    $permissinsList = '3301-3302';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("Reclamação", "", "labelComplaint");
        $menuComplaint = $openMenu;
        if (checkPermission($arrayPermission, '3301-3302') == 1) {
            /**/
            $menuComplaint .= subMenuItem("Lista de reclamações", "ComplaintList.php");
        }

        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuComplaint);
        }
        $menuComplaint .= menuItemClose();
        $mainMenu .= $menuComplaint;
    }


    /* PATRIMÓNIO */
    $permissinsList = '1001-1003';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("PATRIMÓNIO", "", "labelPatrimony");
        $menuPatrimonio = $openMenu;
        /**/
        $menuPatrimonio .= subMenuItem("Lista de activos", "PatrimonyList.php");
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuPatrimonio);
        }
        $menuPatrimonio .= menuItemClose();
        $mainMenu .= $menuPatrimonio;
    }


    /* TESOURARIA */
    $permissinsList = '1101-1102-1103-1104-1105-1106-1107-1108-1109-1201-1202';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("TESOURARIA", "", "labelTreasury");
        $menuTesouraria = $openMenu;
        if (checkPermission($arrayPermission, '1101-1102') == 1) {
            /**/
            $menuTesouraria .= subMenuItem("Rúbrica de tesouraria", "TreasuryRubric.php");
        }
        if (checkPermission($arrayPermission, '1103') == 1) {
            //**/$menuTesouraria .= subMenuItem("Entradas de Caixa", "TreasuryCashInLet.php");
            /**/
            $menuTesouraria .= subMenuItem("Contas a receber (por cliente)", "TreasuryBillToReceive.php");
            /**/
            $menuTesouraria .= subMenuItem("Contas a receber (Consumo de água)", "TreasuryBillToReceiveConsumption.php");
            /**/
            $menuTesouraria .= subMenuItem("Contas a receber (Outros serviços)", "TreasuryBillToReceiveService.php");
        }
        if (checkPermission($arrayPermission, '1104-1105') == 1) {
            /**/
            $menuTesouraria .= subMenuItem("Contas a pagar", "TreasuryBillToPay.php");
        }
        if (checkPermission($arrayPermission, '1106-1107') == 1) {
            /**/
            $menuTesouraria .= subMenuItem("Saídas de Caixa", "TreasuryCashOutLet.php");
        }
        if (checkPermission($arrayPermission, '1108') == 1) {
            /**/
            $menuTesouraria .= subMenuItem("Fluxo de caixa", "TreasuryCashFlow.php");
        }
        if (checkPermission($arrayPermission, '1109') == 1) {
            //**/$menuTesouraria .= subMenuItem("Demonstração de Resultados", "");
        }
        if (checkPermission($arrayPermission, '1201') == 1) {
            $menuTesouraria .= subMenuItem("Relatório de imposto (IVA)", "TreasuryTaxReportIva.php");
            $menuTesouraria .= subMenuItem("Saft(AO) - Facturação", "TreasurySaft.php");
        }
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuTesouraria);
        }
        $menuTesouraria .= menuItemClose();
        $mainMenu .= $menuTesouraria;
    }



    /* DEFINIÇÕES DO SISTEMA */
    $permissinsList = '2101';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("Definições do Sistema", "", "labelUser");
        $menuSistema = $openMenu;
        if (checkPermission($arrayPermission, '2101') == 1) {
            /**/
            $menuSistema .= subMenuItem("Lista de marcas de contador", "SettingHydrometerBrandList.php");
            /**/
            $menuSistema .= subMenuItem("Lista de bairros", "SettingNeiborhoodList.php");
        }
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuSistema);
        }
        $menuSistema .= menuItemClose();
        $mainMenu .= $menuSistema;
    }



    /* PORTAL DO CLIENTE */
    $permissinsList = '3401';
    if (checkPermission($arrayPermission, $permissinsList) == 1) {
        $openMenu = menuItemOpen("Portal do cliente", "", "labelCustomer");
        $menuPortal = $openMenu;
        if (checkPermission($arrayPermission, '3401') == 1) {
            /**/
            $menuPortal .= subMenuItem("Lista de contas activas", "CustomerPortalList.php");
        }
        if (strpos($permissinsList, $pagePermission) !== false) {
            $headerMenu = str_replace($openMenu, '', $menuPortal);
        }
        $menuPortal .= menuItemClose();
        $mainMenu .= $menuPortal;
    }


    if (!$menuBlock) {
        return $mainMenu;
    } else {
        return $headerMenu;
    }
}


function universeSilicaMenuConstroctor($companyId)
{

    $existAqua = file_exists("../saqua");
    $existRH = file_exists("../srh");

    $usMenu = "";
    if ($existAqua) {
        $usMenu .= usMenuItem("Aqua", "../../../saqua/home.php?&frc=1", "icon-silica-aqua");
    }
    if ($existRH) {
        $usMenu .= usMenuItem("RH", "../../../srh/app/", "icon-silica-rh");
    }

    $usMenu .= usMenuItem("Definições", "../../../ssetting/app/", "icon-silica-setting");
    if ($companyId == 5000) {
        $usMenu .= usMenuItem("Gestão", "../../../sgest/app/", "icon-silica-gest");
    }
    return $usMenu;
}
