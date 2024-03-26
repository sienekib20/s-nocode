$(document).ready(function () {

    $('#back-to-browse').click(function (e) { window.location.href = '/browse/categorias/todas'; });

    $('#back-to-first').click(function (e) {
        $('#snd-container').addClass('d-none');
        $('#snd-container').prev().removeClass('d-none');
    });

    $('#call-next').click(function (e) {
        if ($('#siteName').val().length == 0) {
            $('#siteName').focus();
            __make_alert('O nome do site é necessário');
            return;
        }
        window.localStorage.setItem('site_name', $('#siteName').val());
        $('#fst-container').addClass('d-none');
        $('#fst-container').next().removeClass('d-none');
    });


    $('#call-empty-editor').click(function (e) {
        window.localStorage.setItem('alvo', $('alvo').val());
        window.localStorage.setItem('tipo', $('type').val());
        start_loading('/site/blank');
    });

});