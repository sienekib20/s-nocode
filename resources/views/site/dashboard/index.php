<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="wrapper">
        <?= parts('nav.wr-navbar-alt') ?>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <div class="contain-wrapper">
            <div class="container-sm">
                <?php parts('nav.wr-sidebar') ?>

                <div class="contain-pages">
                    <div class="card">
                        <div class="card-top">
                            <div class="container-sm">
                                <div class="row">
                                    <div class="card-title col-12">
                                        <h4 class="title d-block">Visão geral</h4>
                                        <small class="ff">Controle geral dos teus dados.</small>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->

                        <div class="card-body">
                            <div class="container-sm">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4 col-lg-3">
                                        <a href="#" class="service">
                                            <span class="bi bi-grid-fill"></span>
                                            <span class="text-muted">10</span>
                                            <small class="text-muted">Websites</small>
                                        </a>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-3">
                                        <a href="#" class="service">
                                            <span class="bi bi-megaphone"></span>
                                            <span class="text-muted">0</span>
                                            <small class="text-muted">Leads</small>
                                        </a>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-3">
                                        <a href="#" class="service">
                                            <span class="bi bi-layers-fill"></span>
                                            <span class="text-muted">0</span>
                                            <small class="text-muted">Encomendas</small>
                                        </a>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-3">
                                        <a href="#" class="service">
                                            <span class="bi bi-cloud-haze"></span>
                                            <span class="text-muted">0</span>
                                            <small class="text-muted">Websites ativos</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--/.contain-pages-->
            </div>
        </div> <!--/.contain-wrapper-->

        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script>
</script>