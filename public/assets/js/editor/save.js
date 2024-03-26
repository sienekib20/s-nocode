$(document).ready(function () {
    $('.save_btn_edit').click((e) => {
        e.preventDefault();

        let template = '<html>';
        template += '<style>' + editor.getCss() + '</style>';
        template += editor.getHtml();
        template += '</html>';

        const fd = new FormData();
        fd.append('template', template);
        fd.append('dominio', $('[name="__dominio"]').val());

        $.ajax({
            url: '/save/delivered',
            method: 'POST',
            dataType: 'JSON',
            data: fd,
            contentType: false,
            processData: false,
            success: function (res) {

                alert(res);
                window.close();
            },
            error: function (err) {

            }
        });


    });

    $('.save_btn').click((e) => {
        e.preventDefault();
        let template = '<html>';
        template += '<style>' + editor.getCss() + '</style>';
        template += editor.getHtml();
        template += '</html>';

        $.ajax({
            url: $('#rota-salvar-alteracoes').val(),
            method: 'POST',
            dataType: 'JSON',
            data: {
                dominio: $('#__dominio').val(),
                id: $('#id_template').val(),
                template: template
            },
            success: function (res) {
                //console.log(res);
                alert(res);
                window.close();
                // se tudo ocorrer bem então
                // retorna a mensagem de sucesso e redireciona para a pagina 
                // de meus dados

                // mais pra frente colocar um alert que pergunta
                // quer abrir o link? se for sim, abre logo o site noutra âba
            },
            error: function (erro) {
                console.log(erro);
            }
        });
    });
});

//console.log(editor.getHtml());