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
                                <h3 class="card-heading text-black d-block">Feedback de clientes</h3>
                                <span>Veja como os teus clientes interagem com os teus websites, e interaja com eles para manter o dinamismo dos teus negócios.</span>
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
        <small class="d-flex mt-4"></small>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="smslead">
                                <div class="sms-basic">
                                    <div class="remeter">
                                        <span class="client">C</span>
                                        <div class="name">
                                            <span>Fulano de tal</span>
                                            <small class="d-block">c@dominio.com | 9xx xxx xxx</small>
                                            <small class="text-muted">enviado aos 01 de Março | 17h</small>
                                        </div>
                                    </div>
                                    <div class="contain">
                                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nemo, est fugit. Autem, praesentium quos pariatur numquam ipsum debitis distinctio officiis ratione dolorem nemo! Labore, iure.</p>
                                        <a href="" class="btn btn-primary expandReply" title="responder"> <span class="bi bi-reply"></span> </a>
                                        <a href="" class="btn btn-orange removeCurrent" title="remover"> <span class="bi bi-trash"></span></a>
                                    </div>
                                </div>
                                <div class="sms-replications">
                                    <form action="" class="pb-4" method="post">
                                        <div class="input-group">
                                            <input type="text" class="form-input input-block" placeholder="Escreve uma respota pra este cliente...">
                                        </div>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-orange">Enviar</button>
                                        </div>
                                    </form>

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


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script>
    applyDarkNavbar();
    var replyContainer = $('.sms-replications');

    $('.expandReply').click(function(e) {
        e.preventDefault();
        var elem = $(this).parent().parent().next();
        var isExpanded = elem.height() > 0;
        if (isExpanded) {
            elem.css('height', '0');
        } else {
            var autoHeight = elem.css('height', 'auto').height();

            elem.height(0).animate({
                height: autoHeight
            }, 300);
        }
    });
</script>