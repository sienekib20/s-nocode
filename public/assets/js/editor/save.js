$(document).ready(function () {
  $('.save_btn_edit').click((e) => {
    e.preventDefault();
    $(window).close(); return;
    let template = '<html>';
    template += '<style>' + editor.getCss() + '</style>';
    template += editor.getHtml();
    template += '</html>';

    $.ajax({
      url: $('#rota-salvar-edicoes').val(),
      method: 'POST',
      dataType: 'JSON',
      data: {
        dominio: $('#__dominio').val(),
        id: $('#id_template').val(),
        template: template
      },
      contentType: false,
      processData: false,
      success: function (res) {
        console.log(res);
        alert(res);
        $(window).close();
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