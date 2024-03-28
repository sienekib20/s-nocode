$(document).ready(function () {
    $('#send-response-to').submit(function (event) {
        if ($('.loading').hasClass('hide')) {
            $('.loading').removeClass('hide');
        }
        event.preventDefault();
        const formData = new FormData(event.target);
        $.ajax({
            url: '/answer/leads',
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                $('.loading').addClass('hide');
                $('input').val('');
                make_alert(result);
            },
            error: function (err) {
                console.log(err);
            }
        });
    });
});

$(document).ready(function () {
    const formData = new FormData();

    $('[name="delete-current-msg"]').click(function (e) {
        e.preventDefault();
        load_modal('Pretende eliminar esta mensagem?', 'Fazendo isso, vai sumir da tua conta, e não voltá a vê-lo outra vez');
        var account_id = $('[name="leaduser"]').val();
        formData.append('account', account_id);
        formData.append('lead_id', $(this).attr('target'));
    });

    // Remover atual mensagem
    $('[name="action-to-delete"]').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: '/remove/leads',
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                make_alert(result);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function (err) {
                console.log(err);
            }
        });
    });
});



function sendWithAjax(url, method, data) {
    let resultBack = null;
    $.ajax({
        url: url,
        method: method,
        dataType: 'JSON',
        data: data,
        contentType: false,
        processData: false,
        success: function (result) {
            resultBack = result;
        },
        error: function (err) {
            console.log(err);
        }
    });
    return resultBack;
}