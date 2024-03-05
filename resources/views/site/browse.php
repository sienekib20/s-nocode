<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>

</head>

<body>

    <div class="wrapper">
        <?= parts('nav.wr-navbar') ?>

        <small class="d-flex my-5"></small>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-12 card-top">
                            <div class="card-title">
                                <h4 class="title d-block">+100 de Modelos disponívels</h4>
                                <span>Explora e encontre o template de acordo com a tua lógica de negócio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="container-sm">
                    <form action="" class="row" id="searchTemplateInList">
                        <div class="input-group col-md-6">
                            <input type="text" id="searchTemplateInput" class="form-input" placeholder="Pesquise por template, autor...">
                            <small class="bi bi-search d-flex mr-3"></small>
                        </div>
                        <div class="input-group mt-3 mt-md-0 col-md-3">
                            <select id="templateCategory" class="form-select">
                                <option>Categorias</option>
                                <?php foreach ($categorias as $categoria) : ?>
                                    <option value="<?= $categoria->categoria_id ?>"><?= $categoria->categoria ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group mt-3 mt-md-0 col-md-3">
                            <select id="templateType" class="form-select">
                                <option>Tipo template</option>
                                <?php foreach ($tipo as $t) : ?>
                                    <option value="<?= $t->tipo_template_id ?>"><?= $t->tipo_template ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row" id="templateModelContainer">
                        <?php foreach ($templates as $template) : ?>
                            <div class="col-12 mt-3 col-md-5 col-lg-3">
                                <a href="<?= route('view', explode('-', $template->uuid)[0]) ?>" class="model">
                                    <div class="model-img">
                                        <img src="<?= "/html-templates/{$template->capa}" ?>" alt="">
                                    </div>
                                    <span class="title"><?= $template->titulo ?></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex mt-5"></small>
        <small class="d-flex mt-5"></small>
        <small class="d-flex mt-5"></small>
        <small class="d-flex mt-5"></small>
        <small class="d-flex mt-5"></small>


        <?= parts('nav.footer') ?>
    </div> <!-- /.wrapper -->


</body>

</html>
<input type="hidden" id="browse-route" value="<?= route('browse-get') ?>">

<script>
    applyDarkNavbar();
    $(document).ready(function(e) {
        $('#searchTemplateInput').keyup(function(e) {
            const inputValue = $(this).val();
            const templateCategory = $('#templateCategory').val();
            const templateType = $('#templateType').val();
            let templateModel = '';

            // Verifica se os campos não estão vazios
            if (inputValue.trim() !== '') {
                const formData = new FormData();
                formData.append('input', inputValue);

                if (templateCategory !== 'Categorias') {
                    formData.append('categoria', templateCategory);
                }

                if (templateType !== 'Tipo template') {
                    formData.append('tipo', templateType);
                }

                // Exibe feedback de carregamento ou algo semelhante aqui

                $.ajax({
                    url: $('#browse-route').val(),
                    method: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#templateModelContainer').html('');
                        console.log(res)
                        $.each(res, function(key, value) {
                            templateModel += '<div class="col-12 mt-3 col-md-5 col-lg-3">';
                            templateModel += '<a href="/view/' + value.uuid.split('-')[0] + '" class="model">';
                            templateModel += '<div class="model-img">';
                            templateModel += '<img src="/html-templates/' + value.capa + '" alt=""/>';
                            templateModel += '</div>';
                            templateModel += '<span class="title">' + value.titulo + '</span>';
                            templateModel += '</a>';
                            templateModel += '</div>';
                        });
                        $('#templateModelContainer').append(templateModel);
                        console.log(res);
                        // Oculta o feedback de carregamento aqui, se necessário
                    },
                    error: function(err) {
                        console.error(err);
                        // Exibe mensagem de erro ou feedback para o usuário aqui, se necessário
                    }
                });
            }
        });

    });
</script>