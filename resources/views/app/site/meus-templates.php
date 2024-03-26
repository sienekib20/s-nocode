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

        <?= parts('labs.navbar-end') ?>

        <div class="template-universe">
            <div class="toolbar">
                <div class="toolbar-items">
                    <?php foreach ($tipo_templates as $tipo) : ?>
                        <small class="toolbar-item"><?= $tipo->tipo_template ?></small>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="__card-section-header">
            <div class="ncode-container">
                <div class="__title">
                    <small class="tw-muted">Categoria</small>
                    <span class="bold">Landing Pages</span>
                </div>
                <div class="filters">
                    <button> <span class="bi-filter"></span> filtros </button>
                    <div class="__filtros"></div>
                </div>
                <select name="" id="" class="initial">
                    <option value="">Mais Recentes</option>
                    <option value="">Mais usados</option>
                    <option value="">Alto classificados</option>
                </select>
            </div>
        </div>


        <div class="universe-templates">
            <div class="ncode-container">
                <?php for($a = 0; $a < 1; $a++): ?>
                    <div class="linetemplate">
                        <?php foreach ($templates as $template) : ?>
                            <div class="file-template">
                                <div class="cover">
                                    <a href="<?= route('editor.' . $template->uuid) ?>">
                                        <img src="<?= storage() . "templates/defaults/{$template->referencia}/cover/{$template->capa}" ?>" alt="">
                                    </a>
                                    <a href="<?= route('preview.'.$template->referencia) ?>" target="_blank" class="abs_prev">Prever</a>
                                </div>
                                <a href="" class="tempname"><span class="name"><?= ucfirst($template->titulo) ?></span><small class="status">Grátis</small></a>
                                <div class="tempinfo">
                                    <small class="classificated">Mais usado</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="card-section"></div>
        <div class="card-section"></div>
        <div class="card-section"></div>

        <div class="page-manager" style="display: none">
            <div class="ncode-container">
                <div class="positions">
                    <span class="pos active">1</span>
                    <?php for($i = 2; $i < 4; $i++): ?>
                        <span class="pos"><?= $i ?></span>
                    <?php endfor; ?>
                    <span class="pos">..</span>
                    <span class="pos">8</span>
                    <span class="pos bi bi-arrow-right"></span>
                </div>
            </div>
        </div>

        <div class="card-section"></div>
        <div class="card-section"></div>
        <div class="card-section"></div>
        <div class="card-section"></div>


        <?= parts('footer') ?>
    </div>



    <script src="<?= asset('js/site/index.js') ?>"></script>
</body>

</html>