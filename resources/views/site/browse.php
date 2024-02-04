<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>

</head>

<body>

    <div class="wrapper">
        <?= parts('nav.navbar') ?>

        <div class="card">
            <div class="container-sm">
                <div class="row">
                    <div class="col-12 card-top">
                        <div class="card-title text-center">
                            <span class="title d-block">+100 Landing Pages prontas</span>
                            <small class="text-muted">Faça a tua escolha e defina a cor do teu negócio</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="container-sm">
                <div class="row">
                    <div class="col-12 card-body">
                        <div class="row has_mini">
                            <div class="col-md-2 d-xxs-none d-md-block"></div>
                            <div class="col-md-2">
                                <select name="type" id="typeSearch" class="form-input">
                                    <option value="">-- Cor do template --</option>
                                    <option value="">Cor do template</option>
                                </select>
                            </div> <!--/.col-md-2-->
                            <div class="col-md-3">
                                <select name="type" id="typeSearch" class="form-input input-block">
                                    <option value="">-- Tipo template --</option>
                                    <?php foreach ($tipo as $type) : ?>
                                        <option value="<?= $type->tipo_template_id ?>" class="form-input"><?= $type->tipo_template ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> <!--/.col-md-2-->
                            <div class="col-md-3 pesquise_">
                                <input type="text" class="form-input input-block" placeholder="Pesquise">
                                <small class="bi bi-search"></small>
                            </div> <!--/.col-md-2-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2"></div>

        <div class="card">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row">
                        <?php for ($i = 0; $i < count($templates); $i++) :  ?>
                            <div class="col-8 col-md-5 col-lg-4 browse-item mt-xxs-4 mt-md-5">
                                <div class="__item">
                                    <div class="contain-img">
                                        <img src="<?= storage() . 'templates/defaults/' . $templates[$i]->referencia . '/cover/' . $templates[$i]->capa ?>" alt="template-cover">
                                        <div class="actions d-flex">
                                            <a href="<?= route('preview', $templates[$i]->uuid ?? '') ?>" target="_blank"> <span class="bi bi-eye"></span> </a>
                                            <a href="<?= route('usar', $templates[$i]->uuid ?? '') ?>" target="_blank"> <span class="bi bi-cart"></span> </a>
                                        </div>
                                        <div class="d-flex align-items-center ratings">
                                            <small class="bi bi-star-fill"></small>
                                            <small class="bi bi-star-fill"></small>
                                            <small class="bi bi-star"></small>
                                            <small class="bi bi-star"></small>
                                            <small class="bi bi-star"></small>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <span class="title d-block"><?= ucfirst($templates[$i]->titulo ?? '') ?> Landing Page </span>
                                        <small class="text-muted">Criado por: <?= $templates[$i]->autor ?? '' ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>



        <div class="my-5"></div>
        <div class="my-5"></div>
        <div class="my-5"></div>

        <?= parts('nav.footer') ?>
    </div> <!-- /.wrapper -->


</body>

</html>