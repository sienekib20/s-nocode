<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/nav/nav.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/owl-carousel.min.css') ?>">
    <link href="https://fonts.cdnfonts.com/css/icomoon" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>

</head>

<body>
    <div class="sx">
        <?= parts('nav.navbar') ?>
        <div class="sx-card-section">
            <div class="sx-card-section-header p-0">
                <div class="box-chooser d-flex">
                    <?php foreach ($tipo as $t) : ?>
                        <a href="<?= route('browse', $t->tipo_template_id) ?>" class="chooser-item">
                            <?= $t->tipo_template ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="sx-card-section">
            <div class="sx-card-section-contain">
                <div class="sx-container d-flex">
                    <button type="button"><span class="fal fa-sliders"></span> Filtrar</button>
                    <div class="input">
                        <input type="text" placeholder="Buscar template, autor, tipo...">
                        <span class="fas fa-search"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sx-card-section">
            <div class="sx-card-section-contain">
                <div class="container">
                    <?php for ($a = 0; $a < 1; $a++) : ?>
                        <div class="row" style="gap: 1rem">
                            <?php foreach ($templates as $template) : ?>
                                <div class="browse-item">
                                    <div class="cover">
                                        <img src="<?= storage() . "templates/defaults/{$template->referencia}/cover/{$template->capa}" ?>" alt="">
                                        <a href="<?= route('preview', $template->referencia) ?>" target="_blank" class="abs">Previsualizar</a>
                                    </div>
                                    <a href="" class="name"><?= ucfirst($template->titulo) ?></a>
                                    <div href="" class="d-flex">
                                        <small class="status">Template <?= $template->status ?></small>
                                        <small class="preco">0,00KZ</small>
                                    </div>
                                    <div class="actions d-flex">
                                        <a href="#" class="rating" title="editar">
                                            <span class="bi bi-pencil-square"></span> Editável
                                        </a>
                                        <a href="<?= route('usar', $template->uuid) ?>" class="rating" title="usar">
                                            <span class="bi bi-upload"></span> Usar
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>

        <div class="sx-card-section">
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="sx-paginator">
                        <span class="bi bi-arrow-left"></span>
                        <span class="bi bi-arrow-right"></span>
                    </div>
                </div>
            </div>
        </div>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>