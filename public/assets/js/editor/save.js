
$(document).ready(function () {
    $('.save_btn').click((e) => {
        e.preventDefault();

        $.ajax({
            url: $('#rota-salvar-alteracoes').val(),
            method: 'POST',
            dataType: 'JSON',
            data: { html: editor.getHtml() },
            success: function (res) {
                $.each(res, (key, val) => {
                    console.log(val);
                });
                // se tudo ocorrer bem então
                // retorna a mensagem de sucesso e redireciona para a pagina 
                // de meus dados

                // mais pra frente colocar um alert que pergunta
                // quer abrir o link? se for sim, abre logo o site noutra âba
            },
            error: function (erro) {
                $.each(erro, (k, val) => {
                    console.log('Erro', val);
                })
            }
        });
    });
});

//console.log(editor.getHtml());