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
                                        <h4 class="title d-block">Meus websites</h4>
                                        <small class="ff">Todos os websites que já adquiriste.</small>
                                        <small class="d-block text-muted"> <span class="bi bi-arrow-right"></span> Só podes ter 2 websites no máximo por enquanto</small>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->
                        <div class="card-body">

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