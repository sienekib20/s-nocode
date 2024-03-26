<?php use Sienekib\Mehael\Support\Auth; ?>
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
        <?php parts('nav.wr-sidebar') ?>

        <div class="card mb-3">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 card-top">
                            <div class="card-title">
                                <h3 class="card-heading text-black d-block">Painel do usuário</h3>
                                <span>Neste painel verás todos os teus dados e poderás fazer uma análise contínua da evolução dos teus negócios.</span>
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
                            <?= parts('nav.wr-open-dashboard-menu') ?>
                            <a href="<?= route('browse') ?>" class="btn btn-outline-orange"> <span class="bi bi-plus"></span> Adquirir um novo template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex mt-4"></small>

        <div class="card pb-5">
            <div class="card-body mt-4 mb-1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="dashitem-info pb-4">
                                <?php $total = $templateUsuario->total; ?>
                                <span class="card-heading">Tens no total <?= $total < 9 ? "0{$total}" : $total;  ?></span>
                                <span>Websites registados na sua conta.</span>
                                <small class="text-muted d-block">De momento podes ter até 2 templates no máximo.</small>
                                <input type="hidden" id="___" value="<?= Auth::user()->id ?>">
                                <a href="<?= route('user') ?>" class="d-flex w-100 mt-2 text-black text-underline dash-llink"> <small>Ver agora</small></a>
                            </div>
                        </div>
                        <div class="col-lg-4 my-4 my-lg-0">
                            <div class="dashitem-info pb-4">
                                <span class="card-heading">Tens no total 0</span>
                                <span>Encomendas feita por você.</span>
                                <small class="text-muted d-block op-0">a</small>
                                <a href="<?= route('user') ?>" class="d-flex w-100 mt-2 text-black text-underline dash-llink"> <small>Ver agora</small></a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashitem-info pb-4">
                                <span class="card-heading">Tens no total 0</span>
                                <span>Em execução</span>
                                <small class="text-muted d-block op-0">a</small>
                                <a href="#" class="d-flex w-100 mt-2 text-black text-underline"> <small>Ver agora</small></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <span class="card-heading">Algumas interações dos clientes</span>
                            <small class="text-muted d-block">Veja a reação dos teus clientes.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <?php for ($i = 0; $i < 3; $i++) : ?>
                                <a href="" class="cliente-lead">
                                    <div class="client-infoname">
                                        <span class="client_id">S</span>
                                        <div class="info-name">
                                            <span>Cliente username</span>
                                            <small class="text-muted d-block"> <span class="bi bi-envelope"></span> cliente@dominio.com</small>
                                        </div>
                                        <small class="abs">Enviado há 2d</small>
                                    </div>
                                </a>
                            <?php endfor; ?>
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
        <?= parts('nav.footer') ?>
    </div> <!--/.contain-wrapper-->






</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script>
    applyDarkNavbar();
    $(document).ready(() => {
        var menu = ['websites', 'encomendas'];
        var iterator = 0;
        $.ajax({
            url: '/userId',
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: $('#___').val()
            },
            success: function(res) {
                $.each(res, (key, val) => {
                    var uuid = res.uuid.split('-')[3];
                    var a = document.querySelector('#myDash')
                    $('.dash-llink').each(function() {
                        var href = $(this).attr('href');
                        if (href != '#') {
                            $(this).attr('href', `${href}/${uuid}/${menu[iterator]}`);
                        }
                        iterator++;
                    });
                });
            },
            error: function(err) {
                //alert('Erro ao carregar o menu');
            }
        });
    });
</script>