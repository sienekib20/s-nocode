<!DOCTYPE html>
<html>
    <head>
        <?= parts('silica.favicon') ?>
        <?= parts('silica.common-header') ?>
        <meta charset="UTF-8">
        <title>Aqua - Página inicial</title> 
        <link rel="stylesheet" href="<?= asset('silica/css/estiloPaginaInicial.css') ?>"/>
        <script src="<?= asset('js/silica/systemUserFunctions.js') ?>"></script>
        <script src="<?= asset('js/silica/initialPageFunctions.js') ?>"></script>

        <?php
            $vendas = [2, 44, 78, 32, 56];
            $Saida = [3, 36, 42, 63, 78, 87];
            $Saida1 = [4, 22, 30, 23, 48, 77];
        ?>

      <!--  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
        <script type="text/javascript">


            //google.charts.load('current', {'packages': ['corechart']});
            //google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Semana', 'Metas', 'Atual', 'Em Falta'],
                    ['Segunda', <?= $vendas[0] ?>,<?= $Saida[0] ?>,<?= $Saida1[0] ?>],
                    ['Terça', <?= $vendas[1] ?>,<?= $Saida[1] ?>,<?= $Saida1[1] ?>],
                    ['Quarta', <?= $vendas[2] ?>,<?= $Saida[2] ?>,<?= $Saida1[2] ?>],
                    ['Quinta', <?= $vendas[3] ?>,<?= $Saida[3] ?>,<?= $Saida1[3] ?>]
                ]);

                var options = {
                    title: 'Gráfico',
                    curveType: 'function',
                    legend: {position: 'bottom'}
                };

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                var chart1 = new google.visualization.LineChart(document.getElementById('curve_chart1'));
                chart.draw(data, options);
                chart1.draw(data, options);
            }
        </script>
    </head>

    <body id="divFullBodyId"  class="divFullBody">
        <?php
            $pagePermission = "0000";
            $resetPass = 0;
            $whoCall = "INITPAG";
            $divUserResetPassToClose = "divPagIniResetPass";
        ?>
        <?= parts('silica.menu-principal') ?>
        <div class="divBodyContent">
            <header >
                <?php
                $classHeaderPageTitle = "";
                $lbHeaderPageTitle = "Bem Vindo ao Sílica Aqua";
                
                echo parts('silica.main-header');

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if (isset($_GET['rp'])) {
                        $resetPass = $_GET['rp'];
                    }
                }
                ?>
            </header>

            <div class="divForFormsInside">
                <div id="divPagIniAboutUser">
                    <label class="labelLabel">Nome completo: </label><p id="lbPagIniUserFullName" class="labelTarget"></p><br>
                    <label class="labelLabel">Local de trabalho: </label><p id="lbPagIniUserWorkPlace" class="labelFault"></p><br>
                </div>
                <p id="pPagIniTargetMonth" style="display: none;">Metas de Março/2021</p>
                <div class="row" style="width: 100%; display: none;">
                    <div class="colDivFormSS colDivFormMS colDivFormBS">
                        <fieldset class="fieldsetTarget"><legend class="legendOnFliedSet" style=" background-color: var(--colorPrimaryDark); ">Cadastros</legend>
                            <label class="labelLabel">Alcançado:</label>
                            <label id="lbPagIniReachedRegisterNumber" class="labelActualNumber">0</label><br>
                            <label class="labelLabel">Meta:</label>
                            <label id="lbPagIniTargetRegisterNumber" class="labelTarget">0</label>
                            <div id="divPagInInFaultRegister">
                                <label class="labelLabel">Em falta:</label>
                                <label id="lbPagIniInFaultRegisterNumber" class="labelFault">0</label>  
                            </div>
                        </fieldset>   
                    </div>
                    <div class="colDivFormSS colDivFormMS colDivFormBS">
                        <fieldset class="fieldsetTarget" ><legend class="legendOnFliedSet" style="background-color: rgba(237,50,55); ">Vendas</legend>
                            <label class="labelLabel">Alcançado:</label >
                            <label id="lbPagIniReachedSaleAmount" class="labelActualNumber">0</label><br>
                            <label class="labelLabel">Meta:</label>
                            <label id="lbPagIniTargetSaleAmount" class="labelTarget">0,00</label><br>
                            <div id="divPagIniInFaultSale">
                                <label  class="labelLabel">Em falta:</label>
                                <label id="lbPagIniInFaultSaleAmount" class="labelFault">0,00</label>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row" style="display: none;">
                    <div id="curve_chart" class="colDivFormSS colDivFormMS colDivFormBS" style="height: 250px; "></div>  
                    <div id="curve_chart1" class="colDivFormSS colDivFormMS colDivFormBS" style="height: 250px; "></div>  
                </div>
                <div id="divPagIniAboutLicense">
                    <p id="lbPagIniAboutLicense">Procurando licença...</p>
                </div>
            </div>
            <?php
            if ($resetPass == 1) {
                echo '<div id="' . $divUserResetPassToClose . '" class="overlay" style="display: block;">';
                echo '<div class="popup">';
                $login = "'index.php'";
                echo '<button class="popup-close-button labelClose" onclick="window.location.assign(' . $login . ');"></button>';
                include_once './_aqincludes/userPassword_.php';
                echo '</div>';
                echo '</div>';
            }
            ?>

            <footer >
                <?= parts('rodape') ?>
            </footer>
        </div>
        
        <div id="fb-root"></div>
        <script>
            initialPageOnLoad('');

            window.fbAsyncInit = function () {
                FB.init({
                    xfbml: true,
                    version: 'v11.0'
                });
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/pt_PT/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

        </script>



    </body>


</html>
