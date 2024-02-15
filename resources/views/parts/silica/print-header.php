<?php

function divOpenHeader($style = "") {
    return '<div style=" ' . $style . '">';
}

function divCloseHeader() {
    return '</div> ';
}

function labelOpenHeader($name, $style = "") {
    if ($style != "") {
        $style = ' style="' . $style . '" ';
    }
    return '<legend ' . $style . '>' . $name . '</legend>';
}

function imgOpenHeader($src, $style = "") {
    if ($style != "") {
        $style = ' style="' . $style . '" ';
    }
    return '<img src="' . $src . '" ' . $style . ' height="100%" >';
}

$companyNameHeader = $result['companyname'];
$address = $dependencyInfo['address'];
$contacts = $dependencyInfo['phone1'];
if ($dependencyInfo['phone2'] != "") {
    $contacts .= ", " . $dependencyInfo['phone2'];
}
if ($dependencyInfo['phone3'] != "") {
    $contacts .= ", " . $dependencyInfo['phone3'];
}
$email = $dependencyInfo['email'];
$website = $dependencyInfo['website'];
$facebook = $dependencyInfo['facebook'];
$whatsapp = $dependencyInfo['whatsapp'];

$src = $companyLogo;
$existLogo = false;
if ($companyLogo != "") {
    if (file_exists($src)) {
        $existLogo = true;
    }
}

$companyFontSize = ($existLogo) ? "font-size: 12pt;" : "font-size: 14pt;";
$divCompany = divOpenHeader("margin-bottom: 5px; margin-top: 5px; padding-top: 5px; padding-bottom: 1px;")
        . divOpenHeader("font-weight: bold; text-align: left;" . $companyFontSize) . $companyNameHeader . divCloseHeader()
        . divOpenHeader() . $companyTaxId . divCloseHeader()
        . divCloseHeader();

$imgStyle = "width: 3mm; height: 3mm, margin-top: auto; margin-bottom: auto;";
$labelStyle = "padding-left: 5px;";
$divInfoStyle = "display: flex; min-height: 4.2mm; width: fit-content; margin-right: 2mm; margin-top: 1mm;";
$divAddress = "";
if ($address != "") {
    $divAddress .= divOpenHeader("display: flex; min-height: 4.2mm; width: 90%; ")
            . imgOpenHeader("./_svg/home_gray_png.png", $imgStyle)
            . labelOpenHeader($address, $labelStyle)
            . divCloseHeader();
}
$divContact = "";
if ($contacts != "") {
    $divContact .= divOpenHeader($divInfoStyle)
            . imgOpenHeader("./_svg/phone_square_gray_png.png", $imgStyle)
            . labelOpenHeader($contacts, $labelStyle)
            . divCloseHeader();
}
$divEmail = "";
if ($email != "") {
    $divEmail .= divOpenHeader($divInfoStyle)
            . imgOpenHeader("./_svg/mail_gray_png.png", $imgStyle)
            . labelOpenHeader($email, $labelStyle)
            . divCloseHeader();
}
$divWebSite = "";
if ($website != "") {
    $divWebSite .= divOpenHeader($divInfoStyle)
            . imgOpenHeader("./_svg/globe_gray_png.png", $imgStyle)
            . labelOpenHeader($website, $labelStyle)
            . divCloseHeader();
}
$divFacebook = "";
if ($facebook != "") {
    $divFacebook .= divOpenHeader($divInfoStyle)
            . imgOpenHeader("./_svg/fb_gray_png.png", $imgStyle)
            . labelOpenHeader($facebook, $labelStyle)
            . divCloseHeader();
}
$divWhatsApp = "";
if ($whatsapp != "") {
    $divWhatsApp .= divOpenHeader($divInfoStyle)
            . imgOpenHeader("./_svg/whatsapp_gray_png.png", $imgStyle)
            . labelOpenHeader($whatsapp, $labelStyle)
            . divCloseHeader();
}


$htmlHeader = "";

$htmlHeader .= divOpenHeader("padding-left: 10px;")
        . $divCompany . $divAddress . $divContact
        . $divEmail . $divWebSite . $divFacebook . $divWhatsApp
        . divCloseHeader()
        . divCloseHeader();



//echo $htmlHeader;
?>

<div style="display: flex; height: 25mm; margin-bottom: 2mm;">
    <div style="overflow: hidden; height: 25mm; width: 25mm;">
        <?php echo imgOpenHeader($src); ?>
    </div>
    <div style="flex: 2; font-weight: bold; text-align: left; font-size: 12pt; 
         padding-left: 5mm; padding-top: 8mm; ">
         <?php echo $companyNameHeader; ?>
    </div>
</div>
<div class="div-footer"  style="border-top: 1px solid gray; padding-top: 1mm; background-color: white;">
    <div style="display: flex; flex-wrap: wrap;">
        <?php
        echo $divAddress . $divContact
        . $divEmail . $divWebSite . $divFacebook . $divWhatsApp;
        ?>
    </div>
    
    <div>
        <?php echo $companyTaxId; ?>
    </div>
</div>

<style>

    .visa-box{
        display: none;
        position: absolute; 
        top: 5mm; right: 5mm; 
        width: 5cm; 
        background-color: white; 
        text-align: center;
        padding: 2mm;
        border-radius: 10px;
    }
    .content-signature-box{
        display: flex; 
        margin-top: 20px; 
        text-align: center;
        width: 100%;
    }
    .signature-box{
        display: none;
        min-width: 6cm; 
        background-color: white; 
        text-align: center;
        padding: 3mm;
        flex: 2;
    }
    .signature-line{
        margin-right: auto; 
        margin-left: auto;
        width: 80%;
        max-width: 6cm; 
        height: 8mm;
        border-bottom: 1px solid black; 
    }

</style>
<div id="divPrintHeaderTitleSubTitle">
    <div style="text-align: center; ">
        <h1 id="lbPrintHeaderDocumentTitle">Título do documento</h1>
        <span id="lbPrintHeaderDocumentSubTitle" style="font-size: 14pt;">Sub-título do documento</span>
    </div>

    <div id="divPrintHeaderVisaBox" class="visa-box">
        <div style="border: 1px dashed gray; padding: 2mm; border-radius: 3mm;">
            <legend style="font-size: 7pt; font-style: italic;">Visto por:</legend>
            <legend id="lbPrintHeaderVisaQuality" style="font-size: 9pt;">Qualidade</legend>
            <div class="signature-line"></div>
            <legend id="lbPrintHeaderVisaPerson" style="font-size: 9pt;">Titular</legend>
        </div>
    </div>
</div>