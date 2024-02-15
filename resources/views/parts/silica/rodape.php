
<head>
    <style>
        .divFooter{
            width: 100%;
        }
        .divFooter a{
            display: inline !important;
        }

        #btPrintOptionPrint{
            background-size: 30px 30px !important;
            background-position: center !important;
            min-width: 40px !important;
        }

        .option-signature-box{
            display: block;
            color: black;
        }

        .option-signature-box div{
            flex: 2;
            margin-top: 5px;
        }


        @media screen and (min-width: 600px){
            .divFooter{
                /*  width: 47%;*/
            }
            .divPrintPreviewContent{
                width: max-content;
            }

            #btPrintOptionPrint{
                background-size: 20px 20px !important;
                background-position: left !important;
                min-width: 30px !important;
            }
            .option-signature-box{
                display: flex;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<div class="divRodape noPrint"  style="background-color: var(--colorPrimaryDark); font-size: 12px; ">
    <div style="display: flex; flex-wrap: wrap; ">
        <div class="divFooter divCompanyInformation"> 
            <?php
            if (file_exists($companyLogo)) {
                echo ' <img src="' . $companyLogo . '" alt="Avatar" class="imgCab1"> ';
            }
            ?>
            <div class="divMainHeaderCompanyInfo noPrint ">
                <label id="lbHearderCompanyName" style="font-size: x-large; text-shadow:1px 1px black "></label><br>
                <label id="lbHeaderCompanyTaxId"></label><br>
                <label id="lbHeaderCompanyNus">N.U.S:</label><br>
                <label id="lbHeaderDependency">Filial:</label><br>
                <label id="lbHeaderCompanyIVA">I.V.A:</label><br>
            </div>
            <br>
            <label id="lbHeaderLicenseStatus">Estado da Licença :</label><br>
        </div>
        <div class="divFooterXXXXXXXXXXXX divUdaoxInfoXXXXXXXXXXXXXXXXX" > 
            <?php
            //  include_once '_udaoxInformations.php';
            ?>
        </div>   
    </div>
    <div class="divCopyRight">
        <?php
        include_once '_copyrights.php';
        ?>
    </div>

    <div class="divShortcuts">
        <button id="btShortcutSaleInvoice" class="shortcutButton labelSale" type="button" title="Factura de consumo" onclick="window.location.assign('SaleInvoiceConsumption.php?invoicetype=2');"></button>
        <button id="btShortcutSaleWorkdocument" class="shortcutButton labelSaleWorkingDocument" title="Proforma" type="button" onclick="window.location.assign('SaleInvoice.php?invoicetype=4');"></button>
        <button id="btShortcutSalePayment" class="shortcutButton labelPayment" type="button" title="Emitir recibo" onclick="window.location.assign('SalePayment.php');"></button>
        <button id="btShortcutOrder" class="shortcutButton labelSaleOrder" type="button" title="Nova encomenda" onclick="window.location.assign('SaleInvoice.php?invoicetype=5');"></button>
        <!--        <button id="btShortcutPostSale" class="shortcutButton" type="button">Pós-venda</button>-->
        <button id="btShortcutMoviment" class="shortcutButton labelStock " type="button" title="Saída de estoque" onclick="window.location.assign('ProductStockMoviment.php');"></button>
        <button id="btShortcutEntrance" class="shortcutButton labelStockIn" type="button" title="Entrada de estoque" onclick="window.location.assign('ProductStockEntrance.php');"></button>
        <button id="btShortcutSchedule" class="shortcutButton labelCalendar" type="button" title="Marcar actividade" onclick="window.location.assign('ScheduleList.php')"></button>
        <button id="btShortcutSchedule" class="shortcutButton labelKeyboard" type="button" title="Activar / Desactivar teclado virtual" onclick="alterVirtualKeyboardStatus();"></button>
        <button id="btShortcutGotoTop" class="shortcutButton labelArrowUp" type="button" title="Ir para topo da página" onclick="goToTopPage()" style="display: none;"></button>
    </div>

    <?php
    include_once '_aqincludes/_previewPrintSend.php';
    include_once '_aqincludes/_lockWindows.php';
    ?>


    <script type="text/javascript">

        window.onscroll = function () {
            stickyButtonsBar();
            scrollFunction();
        };
        var buttonsBar = document.getElementById("divButtonsBar");
        var sticky = buttonsBar.offsetTop;
        function stickyButtonsBar() {
            if (window.pageYOffset >= sticky) {
                buttonsBar.classList.add("stickyButtonsBar");
            } else {
                buttonsBar.classList.remove("stickyButtonsBar");
            }
        }

        var buttonGotoTop = document.getElementById('btShortcutGotoTop');
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                buttonGotoTop.style.display = "inline";
            } else {
                buttonGotoTop.style.display = "none";
            }
        }

        function goToTopPage() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }


        setTriggerButtonByText('txtPriPrevCustomerEmail', 'btPriPrevSendMail');
        setTriggerButtonByText('txtPriPrevCustomerPhone', 'btPriPrevSendWhatsApp');

    </script>

</div>

<?php
include_once '_aqincludes/_printOptions.php';
?>

