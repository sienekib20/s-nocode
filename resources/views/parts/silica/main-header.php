

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="_favicon/silica_icon.ico">
    <link rel="stylesheet" href="<?= asset('silica/css/estiloDictionary.css') ?>"/>
    <link rel="stylesheet" href="<?= asset('silica/css/labelWithBackground.css') ?>"/>
    <link rel="stylesheet" href="<?= asset('silica/css/estiloCabecalhoRodape.css') ?>"/>
    <link rel="stylesheet" href="<?= asset('silica/css/jQKeyboard.css') ?>"/>
    <script src="<?= asset('js/silica/jquery-2.1.4.min.js')?>"></script>
    <script src="<?= asset('js/silica/jQKeyboard.js')?>"></script>
</head>

<?= parts('silica._notifications'); ?>

<div class="divMainHeader noPrint ">
    <div class="divMainHeaderCompanyInfo noPrint ">
        <h2 class="<?php echo $classHeaderPageTitle; ?>" style="color: white; text-align: center;"><?php echo $lbHeaderPageTitle; ?></h2>
    </div>
    <div class="noPrint" style="text-align: center;"> 
        <img src="<?= asset('silica/_svg/saqua_logo_svg_dark.svg') ?>" alt="" class="imgCab1" >
    </div>
    <br>
    <div style="display: block; width: 100%; overflow: hidden;">
        <nav id="navHeaderMenu" style="height: 20px; width: 100%; overflow: hidden;">
            <?php
            $blockMenu = substr($pagePermission, 0, 2);
            echo mainMenuConstrutor($systemUserId, $companyId, true, $pagePermission, $licenseLevel);
            ?>

        </nav>
    </div>
</div>




<div id="divShowHideMainMenu" class="noPrint" style="margin-bottom: 5px; width: 100%; text-align: right;">
    <button type="button" id="btShowHideMainMenu" onclick="showHideMainMenu('a');" 
            style="width: 100%; margin-left: 0px; transition: width 1s;">= MENU =</button>
</div>

<div class="onlyOnPrint">
    <?= parts('print-header') ?>
</div>
<script>
    setSystemUserStatus('');

    window.addEventListener('mouseup', function (event) {
        var pol = document.getElementById('divUserInfo');
        if (event.target != pol && event.target.parentNode != pol) {
            pol.style.display = 'none';
        }
    });

    setTimeout(function () {
       // document.getElementById('lbHeaderUserFullname').style.display = "none";
      //  document.getElementById('lbHeaderSessionTime').style.display = "block";
    }, 10000);


    function showHideMainMenu(a) {
        const btShowHide = document.getElementById('btShowHideMainMenu');
        const divMainMenu = document.getElementById('divMenuPrincipal');
        const menuWidth = "350px";
        if (window.getComputedStyle(divMainMenu).width == "0px") {
            divMainMenu.style.width = menuWidth;
            btShowHide.style.width = "calc(100% - " + menuWidth + ")";
        } else {
            divMainMenu.style.width = "0px";
            btShowHide.style.width = "100%";
        }
    }

</script>

