<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/wr-table.css') ?>">
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
                                        <h4 class="title d-block">Notificações</h4>
                                        <small class="ff">Fique ligado, accompanhe a evolução do teu negócio</small>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->
                        <div class="card-body mt-5">
                            <div class="row mb-4">
                                <form action="" class="col-md-6">
                                    <div class="input-group">
                                        <select name="" class="form-select">
                                            <option value="">Filtrar dados</option>
                                            <option value="">Todas</option>
                                            <option value="">Apenas lidas</option>
                                            <option value="">Não lidas</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="smslead col-12 col-md-6">
                                    <div class="sms-basic">
                                        <div class="remeter">
                                            <span class="client">S</span>
                                            <div class="name">
                                                <span>Aviso</span>
                                                <small class="d-block">Sujeito: gestão de website</small>
                                                <small class="text-muted">enviado aos 01 de Março | 17h</small>
                                            </div>
                                        </div>
                                        <div class="contain">
                                            <p>O teu website vai expirar em breve, Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis, sint? Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aliquam eos similique, dignissimos nostrum eveniet amet.</p>
                                            <a href="" class="btn btn removeCurrent" title="remover"> <span class="bi bi-trash"></span></a>
                                            <a href="" class="btn removeCurrent" title="marcar como lido"> <span class="bi bi-eye"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="smslead col-12 col-md-6">
                                    <div class="sms-basic">
                                        <div class="remeter">
                                            <span class="client">S</span>
                                            <div class="name">
                                                <span>Aviso</span>
                                                <small class="d-block">Sujeito: gestão de website</small>
                                                <small class="text-muted">enviado aos 01 de Março | 17h</small>
                                            </div>
                                        </div>
                                        <div class="contain">
                                            <p>O teu website vai expirar em breve</p>
                                            <a href="" class="btn btn removeCurrent" title="remover"> <span class="bi bi-trash"></span></a>
                                            <a href="" class="btn removeCurrent" title="marcar como lido"> <span class="bi bi-eye"></span></a>
                                        </div>
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