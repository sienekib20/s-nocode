$(document).ready(() => {
    $('.validar-uso').click((e) => {
        e.preventDefault();
        console.log($('#dominio').val());
        $.ajax({
            url: $('#rota-choose').val(),
            method: 'POST',
            dataType: 'json',
            data: {
                usuario: $('#user_id').val(),
                mail: $('#mail').val(),
                dominio: $('#dominio').val(),
                titulo: $('#template_titulo').val(),
                id: $('#template_id').val(),
                template: $('#template').val(),
            },
            success: function (res) {
                console.log(res);
                $.each(res, (key, value) => {
                    alert(value);
                });
            },
            error: function (erro) {
                console.log('Erro: ' + erro);
            }
        });
    });


    $('.choose-open-editor-btn').click((e) => {
        e.preventDefault();
        var dominio = $('#dominio').val();
        if (dominio.length == 0) {
            alert('Preencha os campos necessários');
            $('#dominio').focus();
            return;
        }
        var url = e.target.href;
        var items = url.split('editor/');
        
        window.open(items[0] + 'editor/' + dominio + '/' + items[1], '_blank');
    });

})