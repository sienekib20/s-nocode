$(document).ready(function () {
    getAndSetPrice('[name="categoria_template"]', '#preco-categoria', 'categoria-preco-');
    getAndSetPrice('[name="tipo_template"]', '#tipo-preco', 'preco_tipo_atual');

    $('#Subject').focus(function (e) {
        make_alert('Informa um título para o website que queres encomendar');
    });


    $('#form-encomenda').submit(function (e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        $.ajax({
            url: '/dash/send_demand',
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                console.log(res)
            },
            error: function (err) {
                console.log(err)
            }
        });
    });


});

function getAndSetPrice(get, set, target) {
    $(get).change(function (e) {
        var categoria_preco = $(this)
            .next()
            .attr('id', target + $(this).val())
            .val()
        $(set).val('');
        $(set).val(categoria_preco + "Kz");
        var cat = +$('#preco-categoria').val().split('Kz')[0];
        var type = +$('#tipo-preco').val().split('Kz')[0];
        var tt = cat + type;
        $('#total_price').val('')
        $('#total_price').val(tt + '.00Kz');
    });

}