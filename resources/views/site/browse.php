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
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <form action="" class="" id="searchTemplateInList">
                                <div class="input-group">
                                    <input type="text" id="searchTemplateInput" class="form-input pl-4 input-orange" placeholder="Pesquise por template, autor...">
                                    <small class="bi bi-search d-flex mr-3"></small>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 col-12 ai-flex-start d-flex flex-wrap" style="gap: 10px;">
                            <a href="" class="btn btn-dark d-block d-lg-none w-35 w-sm-20" id="open-bottom-filter"> <span class="bi bi-sliders"></span> Filtrar </a>
                            <a href="#" class="btn btn-orange"> <span class="bi bi-heart"></span> Meus favoritos </a>
                            <a href="<?= route('site.intro') ?>" class="btn btn-outline-orange"> <span class="bi bi-plus"></span> Criar um website agora</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card d-none d-lg-block">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3" id="colLg3">
                            <small class="d-block card-heading mb-3">Tipo template</small>
                            <ul>
                                <?php

                                use Sienekib\Mehael\Support\Auth;

                                foreach ($tipo as $t) : ?>
                                    <li>
                                        <label for="tipo-<?= $t->tipo_template_id ?>">
                                            <input type="checkbox" name="type-filter" id="tipo-<?= $t->tipo_template_id ?>" class="filter-type-id">
                                            <small><?= $t->tipo_template ?></small>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3 colLg3">
                            <small class="d-block card-heading mb-3">Categoria</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key > 7) break; ?>
                                    <li>
                                        <label for="category-<?= $categoria->categoria_id ?>">
                                            <input type="checkbox" name="type-filter" id="category-<?= $categoria->categoria_id ?>" class="category_filter_id" style="border-radius: 2px;">
                                            <small><?= $categoria->categoria ?></small>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3 colLg3">
                            <small class="d-flex card-heading mb-3" style="color: transparent">somt</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key < 7) continue;
                                    if ($key > 14) break; ?>
                                    <li>
                                        <label for="category-<?= $categoria->categoria_id ?>">
                                            <input type="checkbox" name="type-filter" id="category-<?= $categoria->categoria_id ?>" class="category_filter_id" style="border-radius: 2px;">
                                            <small><?= $categoria->categoria ?></small>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-lg-3 colLg3">
                            <small class="d-flex card-heading mb-3" style="color: transparent">sr-only</small>
                            <ul>
                                <?php foreach ($categorias as $key => $categoria) : if ($key < 14) continue; ?>
                                    <li>
                                        <label for="category-<?= $categoria->categoria_id ?>">
                                            <input type="checkbox" name="type-filter" id="category-<?= $categoria->categoria_id ?>" class="category_filter_id" style="border-radius: 2px;">
                                            <small><?= $categoria->categoria ?></small>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-top">
                <div class="container">
                    <div class="row no-spacing">
                        <?php $t = count($templates); ?>
                        <h5 class="card-heading text-black">Templates encontrados
                            <span id="loaded-templates-results"> (<?= $t <= 9 ? "0$t" : $t  ?>) </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: -4rem;">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row" id="templateModelContainer">
                        <?php foreach ($templates as $template) : ?>
                            <div class="col-12 mt-5 col-md-4 col-lg-3">
                                <a href="<?= route('view', explode('-', $template->uuid)[0]) ?>" class="model">
                                    <div class="model-img">
                                        <img src="<?= "/html-templates/{$template->capa}" ?>" alt="">
                                    </div>
                                    <span class="title"><?= $template->titulo ?></span>
                                </a>
                                <?php if (Auth::check()) : ?>
                                    <a href="" class="text-black d-flex ml-3 mt-2" name="add-to-favorite" id="favorito|<?= $template->template_id ?>|<?= Auth::user()->id ?>" style="text-decoration: underline;">
                                        <small>+ favorito</small>
                                    </a>
                                <?php endif; ?>
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
        <small class="d-flex mt-5"></small>
        <small class="d-flex mt-5"></small>

        <div class="card py-5" style="background-color: #000; color: #fff">
            <div class="card-body my-4">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h3 class="card-heading" style="color: #fff">Crie um website personalizado</h3>
                            <span>Podes criar um website personalizado caso não consiga achar um que te agrade na lista apresentado</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <a href="" class="btn-call-center">
                                <span class="t">Abrir o criador de website</span>
                                <span>Conheça o Webcreator de sílicapages</span>
                                <span class="bi bi-arrow-right"></span>
                            </a>
                        </div>
                        <div class="col-lg-6 col-12">
                            <a href="" class="btn-call-center">
                                <span class="t">Encomendar website</span>
                                <span>Podes deixar que fazemos pra você</span>
                                <span class="bi bi-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex my-1"></div>
        </div>


        <?= parts('nav.footer') ?>


        <div class="nav-on-bottom-overlay"></div>
        <div class="nav-on-bottom pt-3 px-4">
            <div class="asks">
                <div class="ask-header">
                    <span class="ask-header-title">Tipo template</span>
                    <span class="ml-auto fas fa-plus ask-icon"></span>
                </div>
                <div class="ask-contain">
                    <ul>
                        <?php foreach ($tipo as $t) : ?>
                            <li>
                                <label for="ttype-<?= $t->tipo_template_id ?>">
                                    <input type="checkbox" name="" id="ttype-<?= $t->tipo_template_id ?>" class="filter-type-id">
                                    <small class="close-it-after-chossen op-0 w-0"></small>
                                    <small><?= $t->tipo_template ?></small>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="asks">
                <div class="ask-header">
                    <span class="ask-header-title">Categoria</span>
                    <span class="ml-auto fas fa-plus ask-icon"></span>
                </div>
                <div class="ask-contain">
                    <ul>
                        <?php foreach ($categorias as $key => $categoria) : ?>
                            <li>
                                <label for="ccategory-<?= $categoria->categoria_id ?>">
                                    <input type="checkbox" name="type-filter" id="ccategory-<?= $categoria->categoria_id ?>" class="category_filter_id" style="border-radius: 2px;">
                                    <small><?= $categoria->categoria ?></small>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- /.wrapper -->

</body>

</html>
<input type="hidden" id="browse-route" value="<?= route('browse-get') ?>">
<script src="<?= asset('js/browse/index.js') ?>"></script>
<script>
    applyDarkNavbar();


    $('.ask-header').click(function(e) {
        e.preventDefault();
        var icon = $(this).find('.ask-icon');
        if (icon.hasClass('fa-plus')) {
            icon.removeClass('fa-plus');
            icon.addClass('fa-minus');
        } else {
            icon.addClass('fa-plus');
            icon.removeClass('fa-minus');
        }
        $(this).next('.ask-contain').toggleClass('active');
    });

    $(document).ready(function() {
        $('#open-bottom-filter').click(function(e) {
            e.preventDefault();
            $('body').css('overflow', 'hidden');
            $('.nav-on-bottom-overlay').addClass('active');
            $('.nav-on-bottom').addClass('active');
        });
        $('.nav-on-bottom-overlay').click(function(e) {
            $('body').css('overflow', 'auto');
            $('.nav-on-bottom').removeClass('active');
            $('.nav-on-bottom-overlay').removeClass('active');
        });
    });

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