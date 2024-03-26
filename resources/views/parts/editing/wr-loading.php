<style>
    .editing-loading {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        flex-direction: column;
        z-index: 129119 !important;
        gap: 1rem;
    }

    .editing-loading.hide {
        display: none;
    }

    .loading {
        width: 320px;
        height: 5px;
        position: relative;
        background-color: rgba(0, 0, 0, 0.3);
    }

    .loading .spinner {
        top: 0;
        left: 0;
        width: 0%;
        height: 100%;
        position: absolute;
        background-color: #000;
    }
</style>
<div class="editing-loading">
    <div class="loading">
        <span class="spinner"></span>
    </div>
    <span>Carregando</span>
</div>
<?= parts('nav.wr-alert') ?>

<script>
    let maxWidthSize = 0,
        reduceWidthSizeID, toLoad;

    function _reduce_width() {

        $('.spinner').css('width', `${maxWidthSize}%`);
        if (maxWidthSize > 100) {
            clearInterval(reduceWidthSizeID);
            $('.editing-loading').addClass('hide');
            if (toLoad != '/') {
                window.location.href = toLoad;
            }
        }
        if (maxWidthSize == 24) {
            __make_alert('Aguarde enquanto preparamos tudo pra você');
        }

        if (maxWidthSize == 78) {
            __make_alert('Deixamos tudo pronto pra você!');
        }
        maxWidthSize += 2;

    }

    $(document).ready(function() {
        start_loading('/');
    });

    function start_loading(uri) {
        toLoad = uri;
        if ($('.editing-loading').hasClass('hide')) {
            $('.editing-loading').removeClass('hide');
        }
        reduceWidthSizeID = setInterval(_reduce_width, 500);
    }

    let __alertIntervalID;
    var __maxWidth;

    function __make_alert(text) {
        $('.mkalert .alert-dismiss').css('display', 'none');
        $('.mkalert .alert-timing').css('display', 'none');
        $('#__msg').text('');
        $('#__msg').text(text);
        $('.mkalert').addClass('show');

        $('.alert-timing-progress').css('width', '100%');
        __maxWidth = 100;
        __alertIntervalID = setInterval(__decrease_alert_progress, 100);
    }

    function __decrease_alert_progress() {
        if (__maxWidth == 0) {
            $('.mkalert').removeClass('show');
            clearInterval(__alertIntervalID);
        }
        $('.alert-timing-progress').css('width', __maxWidth + '%');
        __maxWidth -= 2;
    }
</script>