$(document).ready(function () {
    $('.dash-website-action').click(function (e) {
        if ($(this).text().trim() == 'excluir' || $(this).text().trim() == 'cancelar uso') {

            e.preventDefault();

            var checkbox = e.target.parentNode.parentNode.querySelector('.currentWebsiteCheck');
            if (!checkbox.checked) {
                make_alert('Primeiro seleciona o template');
                return;
            }

            const formData = new FormData();
            var currentID = checkbox.id.split('-')[1];
            formData.append('id', currentID);
            formData.append('action', $(this).text().trim());
            formData.append('dominio', $('#website-dominio-' + currentID).val());


            $.ajax({
                url: '/remove_template',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    make_alert(res);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000)
                },
                error: function (err) {
                    console.log(err);

                }
            });
        }
    });

});