<?php session()->set('SAquaCookieByNowEntryOut', ''); $buidVersion = "?s=2.369"; ?>
<!DOCTYPE html>
<html lang="pt-pt">
    <head>
        <meta charset="UTF-8"/>
        <title>Sílica Aqua - Login</title>
        
        <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>"/>
        <link rel="stylesheet" href="<?= asset('css/general-sans.css') ?>"/>
        <link rel="stylesheet" href="<?= asset('silica/css/colors.css') ?>"/>
        <link rel="stylesheet" href="<?= asset('silica/css/labelWithBackground.css') ?>"/>
        <link rel="stylesheet" href="<?= asset('silica/css/estiloLogin.css') ?>"/>
        <link rel="stylesheet" href="<?= asset('silica/css/jQKeyboard.css') ?>"/>

        <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
        <script src="<?= asset('js/silica/jQKeyboard.js') ?>"></script>
        <script src="<?= asset('js/silica/_cfjs_sa.js') ?>"></script>
        <script src="<?= asset('js/silica/loginFunctions.js') ?>"></script>
    </head>

    <body>
        <?= parts('silica.favicon') ?>
        <?= parts('labs.loader') ?>
        <header>
            <?php
            \App\Http\Controllers\silica\_connectdb::initialize();
            //$table = silicaDB::getInstance()->systemUserSetDefaultPermission();
            $whoCall = "LOGIN";
            $displayPlan = ' style="display: none;" ';
            if (request()->method() == "GET") {
                if (isset($_GET['wc']) && !empty($_GET['wc'])) {
                    $whoCall = $_GET['wc'];
                }
                if (isset($_GET['req']) && !empty($_GET['req'])) {
                    $displayPlan = ' style="display: block;" ';
                }
            }
            ?>
        </header>
        <div class="divPageContent">
            <?php parts('silica.indexSilicaContent_'); ?>


            <div class="divLogin">
                <div id="divIndLogPlanInfo" class="divLogin-Inside" <?= $displayPlan ?>>
                    <h3>Sobre os planos seleccionados</h3>
                    <div id="divIndReqAboutUser">
                        <p style="font-size: 16px;">Foi gerada e enviada por e-mail uma Nota de Encomenda, contendo o(s) plano(s) seleccionado(s). <br><br>
                            Após pagamento, por favor enviar o comprovativo para <b>cc@silicaweb.ao</b>, ou para WhatsApp <b>993700757</b>. Indicar o número da encomenda (Exemplo: NE xxxxxxxxxxx/xx).<br><br>
                            Nota que a(s) licença(s) só serão activada(s) após confirmação do pagamento. </p>
                    </div>
                </div>


                <div class="divLogin-Inside">
                    <h3>Bem vindo ao Sílica Aqua</h3>
                    <div>
                        <input type="email" class="labelLoginUser" id="txtIndUserName" placeholder="E-mail" required style="margin-bottom: 10px;">
                        <input type="password" class="labelLoginPass" id="txtIndPassword" placeholder="Palavra passe"  required style="margin-bottom: 10px;">
                        <legend id="lbIndMsgError" style="color: red; text-align: center; margin-left: auto; margin-right: auto;"></legend>
                        <button type="button" id="btIndLogin" onclick="indexLogin(0, '<?php echo $whoCall; ?>');" class="loginButton labelGetIn" >Entrar</button>
                        <!-- <a href="home.php" class="button">Entrar</a>-->
                    </div>
                    <br>
                    <a href="indexRequestNewPass.php">Esqueci minha palavra passe.</a> 
                </div>
            </div>

        </div>

        <script>
            loginOnLoad();
            setVirtualKeyboardOnInput();
        </script>
    </body>
</html>

<input type="hidden" id="login_url" value="<?= env('SUSER_LOGIN_HOST') ?>">