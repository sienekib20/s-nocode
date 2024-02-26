$(document).ready(function () {
    $('#input-phone').on('keydown', function (e) {
        var limite = $(this).val().length;
        if (limite === 11) {
            e.preventDefault();
            return;
        }
        var inputValue = $(this).val().replace(/[a-zA-Z]+/g, ''); 
        var formattedValue = formatPhoneNumber(inputValue);
        $(this).val(formattedValue);

    });

    function formatPhoneNumber(input) {
        var formatted = '';
        for (var i = 0; i < input.length; i++) {
            if (i === 0) {
                formatted += input[i];
            } else if (i === 3 || i === 6) {
                formatted += '-' + input[i];
            } else {
                if (i === 9)
                    break;
                formatted += input[i];
            }
        }
        return formatted;
    }
});