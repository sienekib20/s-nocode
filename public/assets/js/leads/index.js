$(document).ready(function () {
    $('#send-response-to').submit(function (event) {
        const formData = new FormData(event.target);
        $.ajax({
            url: '/answer/leads',
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                console.log(result);
            },
            error: function (err) {
                console.log(err);
            }
        });
    });
});