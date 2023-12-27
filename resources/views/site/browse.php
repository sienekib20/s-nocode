<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">

</head>

<body>
    <div class="sx">
        <?= parts('nav.navbar') ?>
        <div class="sx-card-section">
            <div class="sx-card-section-header no-padding">
                <div class="box-chooser d-flex">
                    <?php foreach ($tipo as $t) : ?>
                        <a href="<?= route('browse', $t->tipo_template_id) ?>" class="chooser-item"><?= $t->tipo_template ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="sx-card-section">
            <div class="sx-card-section-contain">
                <div class="sx-container d-flex">
                    <button type="button"> <span class="fal fa-sliders"></span> Filtrar </button>
                    <div class="input">
                        <input type="text" placeholder="Buscar template, autor, tipo...">
                        <span class="fas fa-search"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sx-card-section">
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <?php for ($a = 0; $a < 2; $a++) : ?>
                        <div class="browse-row">
                            <?php for ($i = 0; $i < 3; $i++) : ?>
                                <div class="browse-item">
                                    <div class="cover">
                                        <img src="<?= asset('img/ncode-2.jpg') ?>" alt="">
                                        <a href="">Previsualizar</a>
                                    </div>
                                    <div class="browse-name">
                                        <span class="name">Template title</span>
                                        <small class="tw-muted">Autor: name here</small>
                                    </div>
                                    <div class="d-flex">
                                        <span class="status">Grátis</span>
                                        <span class="preco">0,00KZ</span>
                                    </div>
                                    <span class="rating">Mais usado</span>
                                </div>
                            <?php endfor; ?>
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

    </div> <!-- sx-wrapper ->


    
</body>
</html>