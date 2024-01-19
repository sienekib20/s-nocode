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
        <div class="tinybar">
            <div class="container-sm d-flex align-items-center justify-content-lg-center justify-content-xxs-start">
                <?php foreach($tipo as $type): ?>
                    <div class="tiny-item">
                        <a href="" class="tiny-link"> <small><?= $type->tipo_template ?></small> </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="my-2"></div>

        <div class="filter-bar">
            <div class="container-sm">
                <div class="row">
                    <div class="col-lg-3">
                        <!--<button class="btn btn-block"> <small class="bi bi-sliders"></small> </button>-->
                    </div>
                    <div class="col-lg-9">
                        <div class="row">
                            <input type="text" class="form-input input-block" placeholder="Pesquise">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="browse-data">
            <div class="container-sm">
                <div class="row">
                    <div class="col-lg-3">
                        <span class="d-block"> <span class="bi bi-filter"></span> Filtrar resultados</span>
                        <div class="d-flex flex-direction-column">
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-1"> <label for="ch-1">Templates recentes</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-2"> <label for="ch-2">Mais usados</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-3"> <label for="ch-3">Sem estilos</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-3"> <label for="ch-3">Apenas Grátis</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-3"> <label for="ch-3">De menor custo</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-3"> <label for="ch-3">Menos usados</label></small></div>
                            <div class="item mt-3 d-flex align-items-center"><small><input type="checkbox" id="ch-3"> <label for="ch-3">Todos</label></small></div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="row align-items-center browse-items justify-content-xxs-center justify-content-md-start">
                            <div class="col-xxs-8 col-md-5 col-lg-4 browse-item mt-xxs-4 mt-md-5">
                                <div class="contain-img">
                                    <img src="<?= asset('img/download.png') ?>" alt="template-cover">
                                    <div class="actions d-flex">
                                        <a href="<?= route('usar', 'default') ?>" target="_blank"> <small class="bi bi-cart"></small> </a>
                                    </div>
                                    <div class="ratings">
                                        <small class="bi bi-star"></small>
                                        <small class="bi bi-star"></small>
                                        <small class="bi bi-star"></small>
                                        <small class="bi bi-star"></small>
                                        <small class="bi bi-star"></small>
                                    </div>
                                </div>
                                <div class="info">
                                    <span class="title d-block"><?= 'Vazio' ?></span>
                                    <small class="text-muted">Criado por: <?= 'Nocode' ?></small>
                                </div>
                            </div>
                            <?php for($i = 0; $i < count($templates); $i++):  ?>
                                <div class="col-xxs-8 col-md-5 col-lg-4 browse-item mt-xxs-4 mt-md-5">
                                    <div class="contain-img">
                                        <img src="<?= asset('img/ncode-3.jpg') ?>" alt="template-cover">
                                        <div class="actions d-flex">
                                            <a href="<?= route('preview', $templates[$i]->uuid) ?>" target="_blank"> <small class="bi bi-eye"></small> </a>
                                            <a href="<?= route('usar', $templates[$i]->uuid) ?>" target="_blank"> <small class="bi bi-cart"></small> </a>
                                        </div>
                                        <div class="ratings">
                                            <small class="bi bi-star-fill"></small>
                                            <small class="bi bi-star-fill"></small>
                                            <small class="bi bi-star"></small>
                                            <small class="bi bi-star"></small>
                                            <small class="bi bi-star"></small>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <span class="title d-block"><?= $templates[$i]->titulo ?></span>
                                        <small class="text-muted">Criado por: <?= $templates[$i]->autor ?></small>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.browse-data -->

        <div class="my-5"></div>
        <div class="my-5"></div>
        <div class="my-5"></div>

        <?= parts('nav.footer') ?>
    </div> <!-- /.wrapper -->


</body>

</html>