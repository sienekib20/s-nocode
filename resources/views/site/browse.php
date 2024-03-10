<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400..700;1,400..700&family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>

</head>

<body>

    <div class="wrapper">
        <?= parts('nav.wr-navbar') ?>

        <small class="d-flex my-5"></small>

        <div class="card mb-4">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 card-top">
                            <div class="card-title">
                                <h3 class="card-heading text-black d-block">Crie um website a teu gosto. Explora +100 de Modelos disponívels</h3>
                                <span>Temos modelos para qualquer tipo de negócio. Seja para portfólio, um e-commerce, um blog pessoal, etc. Comece por se inspirar em nossos modelos.</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 mt-4 mt-lg-0">
                            <h4 class="card-heading"></h4>
                            <span class="d-none d-lg-block mb-4">Pesquisa pelo nome do modelo, categoria, tipo, até mesmo o nome do autor caso conheça um.</span>
                            <form action="" class="" id="searchTemplateInList">
                                <div class="input-group">
                                    <input type="text" id="searchTemplateInput" class="form-input pl-4 input-orange" placeholder="Pesquise por template, autor...">
                                    <small class="bi bi-search d-flex mr-3"></small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <small class="d-block card-heading mb-3">Tipo template</small>
                            <ul>
                                <?php foreach ($tipo as $t) : ?>
                                    <li>
                                        <label for="tipo-<?= $t->tipo_template_id ?>">
                                            <input type="checkbox" name="" id="tipo-<?= $t->tipo_template_id ?>">
                                            <small><?= $t->tipo_template ?></small>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3">
                            <small class="d-block card-heading mb-3">Categoria</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key > 7) break; ?>
                                    <li>
                                        <a href="<?= route('browse', 'categoria/' . $categoria->categoria) ?>"><small><?= $categoria->categoria ?></small></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3">
                            <small class="d-flex card-heading mb-3" style="color: transparent">somt</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key < 7) continue;
                                    if ($key > 14) break; ?>
                                    <li>
                                        <a href="<?= route('browse', 'categoria/' . $categoria->categoria) ?>"><small><?= $categoria->categoria ?></small></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3">
                            <small class="d-flex card-heading mb-3" style="color: transparent">somt</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key < 14) continue; ?>
                                    <li>
                                        <a href="<?= route('browse', 'categoria/' . $categoria->categoria) ?>"><small><?= $categoria->categoria ?></small></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
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