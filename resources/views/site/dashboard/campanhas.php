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
        <div class="contain-wrapper">
            <div class="container">
                <?php parts('nav.wr-sidebar') ?>

                <div class="contain-pages">
                    <div class="card">
                        <div class="card-top">
                            <div class="container">
                                <div class="row">
                                    <div class="card-title col-10">
                                        <h4 class="title d-block">Minhas campanhas</h4>
                                        <small class="ff">Veja como os teus clientes reagiram nos teus websites.</small>
                                    </div>
                                    <div class="d-flex justify-content-flex-end px-0 col-2">
                                        <?= parts('nav.wr-hamburguer') ?>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->
                        <div class="card-body mt-5">
                            <div class="row mb-4">
                                <form action="" class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-input" placeholder="Busque por nome, email...">
                                    </div>
                                </form>
                            </div>
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
                                            <form action="">
                                                <div class="input-group">
                                                    <textarea name="" class="form-input" placeholder="Escreva a tua resposta aqui..." cols="30"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-orange"><span class="bi bi-send"></span></button>
                                            </form>

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