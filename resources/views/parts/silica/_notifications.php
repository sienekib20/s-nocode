
<div id="divConfirmOverlay" class="modalContainer" style="display: none;">;
    <div class="alertBox">
        <h1>Sílica Aqua</h1>
        <p id="lbConfirmQuestion">Pergunta</p>
        <div style="text-align: center">
            <button type="button" id="btConfirmYes" class="btConfirm">Sim</button>
            <button type="button" class="btConfirm" onclick="hideParts('divConfirmOverlay'); hideNotification();">Não</button>
        </div>
    </div>
</div>

<div id="divNotification" class="divNotification">
    <p id="lbNotification"  style="color: white; margin-top: 20px; font-size: large; text-transform: uppercase;">Notificação</p>
    <a class="close" href="#" onclick="hideNotification();">&times;</a>
    <div id="divNotificationProgress" class="divProgress">
    </div>
</div>

<div id="divNotificationBox" class="notification-box">
    <div id="divNotificationBoxIcon" class="notification-icon">
    </div>
    <p id="pNotificationBoxText" class="notification-text"></p>
</div>

<div id="divAlertNoLicense" class="overlay" style="display: none;">
    <div class="popup">
        <div class="popup-header">
            <h2>SEM LICENÇA</h2>
        </div>
        <div class="popup-content">
            <h2>Lamentamos! <br>Infelizmente, não foi encontrada uma licença válida.</h2>
            <p>Experimenta recarregar a página.</p>
            <p>Se a situação continuar, entre em contacto com os serviços de apoio ao Sílica.</p>
            <div class="div-silica-contact">
                <span>Contactos: (+244) <a href="tel:993700757">993 700 757</a> / <a href="tel:948109778">948 109 778</a></span><br>
                <span>E-mail: <a href="mailto:cc@silicaweb.ao">cc@silicaweb.com</a> / <a href="mailto:at@silicaweb.ao">at@silicaweb.ao</a></span><br>
                <span>Website: <a href="https://www.silicaweb.ao" target="_blank">www.silicaweb.ao</a></span><br>
                <span>Facebook: <a href="https://www.facebook.com/silicaweb" target="_blank">/silicaweb</a></span><br>
                <span>WhatsApp: <a href="https://wa.me/244993700757" target="_blank">993 700 757</a></span>
            </div>
            <br>
            <button type="button" class="mainButton" onclick="window.open('../priceandplans.php?rn=1&cid=' + SYSTEM_USER_STATUS.companyId)">Comprar licença</button>
        </div>
    </div>
</div>


<div id="divAlertOutOfWorkSchedule" class="overlay" style="display: none;">
    <div class="popup">
        <div class="popup-content">
            <h2>Lamentamos!</h2>
            <h2>Está fora do horário de trabalho.</h2>
            <button type="button" onclick="systemUserLogOut();">Sair</button>
        </div>
    </div>
</div>
