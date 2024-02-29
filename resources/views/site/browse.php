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
        <?= parts('nav.header-search') ?>

        <header class="wr-header vh-55">
            <div class="wr-overlay"></div>
            <img src="<?= asset('img/pexels-jopwell-2422293.jpg') ?>" alt="">
            <div class="d-flex mt-5"></div>
            <div class="wr-header-text">
                <div class="container-sm">
                    <div class="row">
                        <form action="" class="col-12 mx-auto col-md-10 col-lg-7" id="searchTemplateInList">
                            <div class="card-title text-center">
                                <h4 class="title d-block text-white">Filtre a sua busca</h4>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-input" placeholder="Pesquise por template, autor...">
                                <small class="bi bi-search"></small>
                            </div>
                            <div class="row row-no-margin mt-4">
                                <select id="" class="col-md-6 form-input">
                                    <option value="">Categorias</option>
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option value="<?= $categoria->categoria_id ?>"><?= $categoria->categoria ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select id="" class="mt-3 mt-md-0 col-md-6 form-input">
                                    <option value="">Tipo template</option>
                                    <?php foreach($tipo as $t): ?>
                                        <option value="<?= $t->tipo_template_id ?>"><?= $t->tipo_template ?></option>
                                    <?php endforeach; ?>
                                </select>«
                            </div>
                        </form>
                    </div>
                </div>
            </div>




        </header>

        <div class="card">
            <div class="container-sm">
                <div class="row">
                    <div class="col-12 card-top">
                        <div class="card-title">
                            <h2 class="title d-block">+100 de Modelos disponívels</h2>
                            <span>Explora e encontre o template de acordo com a tua lógica de negócio</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="container-sm">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php $templates = [[], [], [], []];
                            for ($i = 0; $i < count($templates); $i++) :  ?>
                                <div class="col-4">
                                    <a href="" class="model">
                                        <div class="model-img">
                                            <img src="<?= storage() ?>" alt="">
                                        </div>
                                        <span class="title">Titulo</span>
                                    </a>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2"></div>


        <div class="my-5"></div>
        <div class="my-5"></div>
        <div class="my-5"></div>

        <?= parts('nav.footer') ?>
    </div> <!-- /.wrapper -->


</body>

</html>