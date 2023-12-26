<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%title%</title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/editor.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
</head>

<body class="white-bg">

    <div class="wrapper">

        <?= parts('labs.navbar') ?>

        <div class="card-section">
            <div class="card-section-header">
                <div class="ncode-container">
                    <div class="title">
                        <span class="dark-bold">Templates</span>
                        <small class="tw-muted">Todos os templates</small>
                    </div>
                    <small>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta expedita deserunt dicta ducimus, harum corporis accusamus accusantium adipisci voluptas maiores explicabo odio fugit in quidem.</small>
                </div>
            </div>
        </div>

        <div class="contain-has-filter">
            <div class="ncode-container">
                <div class="has-filter">
                    <small class="title">Especificar a busca</small>
                    <div class="contain-filters">
                        <div class="fitler-item">
                            <div class="search">
                                <input type="text" name="" placeholder="Pesquisar">
                                <small class="bi bi-search"></small>
                            </div>
                        </div>
                        <div class="fitler-item">
                            <small class="fil-title">Categoria</small>
                            <div class="fil-items">
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>E-commerce</small></label>
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>Políticas</small></label>
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>Outro</small></label>
                            </div>
                        </div>
                        <div class="fitler-item">
                            <small class="fil-title">Preco</small>
                            <div class="fil-items">
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>Entre 1K a 5K</small></label>
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>Entre 6K a 15K</small></label>
                            </div>
                        </div>
                        <div class="fitler-item">
                            <small class="fil-title">Qtd. Páginas</small>
                            <div class="fil-items">
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>3 Páginas</small></label>
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>Single Page</small></label>
                                <label for="item-1"><input type="checkbox" name="" id="item-1"><small>+3 Páginas</small></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contain-templates">
                    <div class="toolbar">
                        <span class="bi bi-chevron-left"></span>
                        <div class="toolbar-items">
                            <?php foreach ($tipo_templates as $tipo) : ?>
                                <small class="toolbar-item"><?= $tipo->tipo_template ?></small>
                            <?php endforeach; ?>
                        </div>
                        <span class="bi bi-chevron-right"></span>
                    </div>

                    <div class="templates-items">
                        <?php for ($i = 0; $i < 2; $i++) : ?>
                            <a href="" class="template-item">
                                <div class="cover">
                                    <img src="<?= asset('images/ncode-1.jpg') ?>" alt="">
                                </div>
                                <div class="info">
                                    <span class="title">Nome do template</span>
                                    <small class="tw-muted">Desenvolvido por: <span class="author">Sílica</span> </small>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, explicabo! Lorem ipsum dolor sit amet.</p>
                                    <small class="type">Template editável</small>
                                    <div class="price">
                                        <span class="tilte">1.000,00 AO</span>
                                        <small class="tw-muted line-through">2.500,00 AO</small>
                                    </div>
                                </div>
                                <small class="absolute-status">Pago</small>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>



        <?= parts('footer') ?>
    </div>



    <script src="<?= asset('js/site/index.js') ?>"></script>
</body>

</html>