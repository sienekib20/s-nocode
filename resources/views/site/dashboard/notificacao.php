<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400..700;1,400..700&family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="wrapper">
        <?= parts('nav.wr-navbar') ?>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <?= parts('nav.wr-sidebar') ?>

        <div class="card mb-3">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 card-top">
                            <div class="card-title">
                                <h3 class="card-heading text-black d-block">Notificações</h3>
                                <span>Mantemos você informado sobre qualquer alteração na plataforma. Fique sempre ligado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.card-top-->
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 ai-flex-start d-flex flex-wrap" style="gap: 10px;">
                            <form action="">
                                <div class="input-group">
                                    <select name="" class="form-select">
                                        <option value="">Filtrar dados</option>
                                        <option value="">Todas</option>
                                        <option value="">Apenas lidas</option>
                                        <option value="">Não lidas</option>
                                    </select>
                                </div>
                            </form>
                            <?= parts('nav.wr-open-dashboard-menu') ?>
                            <a href="<?= route('browse') ?>" class="btn btn-outline-orange"> <span class="bi bi-plus"></span> Adquirir um novo template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex my-4"></small>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="smslead">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script>
    applyDarkNavbar();
</script>