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
                    <div class="col-md-3">
                        <div class="model-category">
                            <span class="d-block text-muted mb-3">CATEGORIAS</span>
                            <ul class="list-unstyled f_list">
                                <?php foreach($categorias as $categoria): ?>
                                    <li class="my-3"><a href="<?= route('browse', $categoria->categoria_id) ?>"><?= $categoria->categoria ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <?php $templates = [[], [], [], []]; for ($i = 0; $i < count($templates); $i++) :  ?>
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