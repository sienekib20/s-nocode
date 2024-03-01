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

        <div class="card-top">
            <div class="container-sm">
                <form action="" class="row" id="searchTemplateInList">
                    <div class="input-group col-md-6">
                        <input type="text" class="form-input" placeholder="Pesquise por template, autor...">
                        <small class="bi bi-search d-flex mr-3"></small>
                    </div>
                    <div class="input-group col-md-3">
                        <select id="" class="form-select">
                            <option value="">Categorias</option>
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?= $categoria->categoria_id ?>"><?= $categoria->categoria ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group col-md-3">
                        <select id="" class="form-select">
                            <option value="">Tipo template</option>
                            <?php foreach ($tipo as $t) : ?>
                                <option value="<?= $t->tipo_template_id ?>"><?= $t->tipo_template ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="container-sm">
                <div class="row">
                    <div class="col-md-12 px-0">
                        <div class="row">
                            <?php foreach ($templates as $template) : ?>
                                <div class="col-12 col-md-5 col-lg-3">
                                    <a href="<?= route('view', explode('-', $template->uuid)[0]) ?>" class="model">
                                        <div class="model-img">
                                            <img src="<?= __template("defaults/{$template->referencia}/cover/{$template->capa}") ?>" alt="">
                                        </div>
                                        <span class="title"><?= $template->titulo ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
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

<script>
    applyDarkNavbar();
</script>