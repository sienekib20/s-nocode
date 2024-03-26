
// Auto search for tipo template
$(document).ready(function (e) {
    const all_filter = document.querySelectorAll('.filter-type-id');
    $('.filter-type-id').change(function (e) {
        let templateModelType = '';
        all_filter.forEach(item => { item.checked = false; })
        this.checked = true;

        if (this.checked) {
            var id = $(this).attr('id').split('-')[1];

            var currentCheckboxParentClass = $(this).parent().parent().parent().parent().hasClass('ask-contain');
            if (currentCheckboxParentClass) {
                const all_t = document.querySelectorAll('#colLg3 input');
                all_t.forEach(all => {
                    var idd = all.id.split('-')[1];
                    if (idd == id) {
                        all.checked = true;
                    }
                });
            } else {
                const all_t = document.querySelectorAll('.ask-contain input');
                all_t.forEach(all => {
                    var idd = all.id.split('-')[1];
                    if (idd == id) {
                        all.checked = true;
                    }
                });
            }

            const all_types = document.querySelectorAll('.category_filter_id');

            const formData = new FormData();

            formData.append('id', id);

            all_types.forEach(type => {
                if (type.checked) {
                    formData.append('idCat', type.id.split('-')[1]);
                }
            });

            $.ajax({
                url: '/browse-get-id',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    /*if (result.length == 0) {
                        alert('Sem template disponível para esse tipo');
                        return;
                    }*/
                    var count = result.length < 9 ? `(0${result.length})` : `(${result.length})`;
                    $('#loaded-templates-results').text('');
                    $('#loaded-templates-results').text(count);
                    $('#templateModelContainer').html('');
                    $.each(result, function (key, value) {
                        templateModelType += '<div class="col-12 mt-5 col-md-5 col-lg-3">';
                        templateModelType += '<a href="/view/' + value.uuid.split('-')[0] + '" class="model">';
                        templateModelType += '<div class="model-img">';
                        templateModelType += '<img src="/html-templates/' + value.capa + '" alt=""/>';
                        templateModelType += '</div>';
                        templateModelType += '<span class="title">' + value.titulo + '</span>';
                        templateModelType += '</a>';
                        templateModelType += '</div>';
                    });
                    $('#templateModelContainer').append(templateModelType);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });
});



// Auto search for categoria
$(document).ready(function (e) {
    const all_filter = document.querySelectorAll('.category_filter_id');
    $('.category_filter_id').change(function (e) {
        let templateModelType = '';
        all_filter.forEach(item => { item.checked = false; })
        this.checked = true;

        if (this.checked) {
            var id = $(this).attr('id').split('-')[1];

            var currentCheckboxParentClass = $(this).parent().parent().parent().parent().hasClass('ask-contain');
            if (currentCheckboxParentClass) {
                const all_t = document.querySelectorAll('.colLg3 input');
                all_t.forEach(all => {
                    var idd = all.id.split('-')[1];
                    if (idd == id) {
                        all.checked = true;
                    }
                });
            } else {
                const all_t = document.querySelectorAll('.ask-contain input');
                all_t.forEach(all => {
                    var idd = all.id.split('-')[1];
                    if (idd == id) {
                        all.checked = true;
                    }
                });
            }

            const all_types = document.querySelectorAll('.filter-type-id');

            const formData = new FormData();
            formData.append('id', id);
            all_types.forEach(type => {
                if (type.checked) {
                    formData.append('idType', type.id.split('-')[1]);
                }
            });
            $.ajax({
                url: '/browse-get-id_2',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    /*if (result.length == 0) {
                        alert('Sem template disponível para esse tipo');
                        return;
                    }*/
                    var count = result.length < 9 ? `(0${result.length})` : `(${result.length})`;
                    $('#loaded-templates-results').text('');
                    $('#loaded-templates-results').text(count);
                    $('#templateModelContainer').html('');
                    $.each(result, function (key, value) {
                        templateModelType += '<div class="col-12 mt-5 col-md-5 col-lg-3">';
                        templateModelType += '<a href="/view/' + value.uuid.split('-')[0] + '" class="model">';
                        templateModelType += '<div class="model-img">';
                        templateModelType += '<img src="/html-templates/' + value.capa + '" alt=""/>';
                        templateModelType += '</div>';
                        templateModelType += '<span class="title">' + value.titulo + '</span>';
                        templateModelType += '</a>';
                        templateModelType += '</div>';
                    });
                    $('#templateModelContainer').append(templateModelType);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });
});


$(document).ready(function (e) {
    $('[name="add-to-favorite"]').click(function (e) {
        e.preventDefault();

        var id = $(this).attr('id').split('|');
        var templata_id = id[1], user_id = id[2];

        const formData = new FormData();
        formData.append('template_id', templata_id);
        formData.append('user_id', user_id);

        $.ajax({
            url: '/favoritos/add',
            method: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                make_alert(result.response);
            },
            error: function (err) {
                console.error(err)
            }
        });

    });
});

