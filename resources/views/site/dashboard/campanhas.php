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

        <?php use Sienekib\Mehael\Support\Auth; parts('nav.wr-sidebar') ?>

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
                            <?php if (!empty($campanhas)) : ?>
                                <?php foreach ($campanhas as $lead) : ?>
                                    <div class="smslead mt-4">
                                        <input type="hidden" name="leaduser" value="<?= Auth::user()->id ?>">
                                        <div class="sms-basic">
                                            <div class="remeter">
                                                <span class="client">
                                                    <?= ucfirst($lead->username[0]) ?>
                                                </span>
                                                <div class="name">
                                                    <span><?= $lead->username ?></span>
                                                    <small class="d-block"><?= $lead->email ?> | <?= $lead->telefone ?></small>
                                                    <?php
                                                    $time = strtotime($lead->created_at);
                                                    ?>
                                                    <small class="text-muted"><?= estimated_time($time) ?></small>
                                                </div>
                                            </div>
                                            <div class="contain">
                                                <p><?= $lead->mensagem ?></p>
                                                <a href="" class="btn btn-dark"> <span class="bi bi-arrow-up"></span> marcar como lido</a>
                                                <a href="" class="btn btn-primary expandReply my-2 my-md-0" title="responder"> <span class="bi bi-reply"></span> responder </a>
                                                <a href="<?= route('remove.leads') ?>" class="btn btn-orange removeCurrent" target="<?= $lead->lead_id ?>" name="delete-current-msg" title="remover"> <span class="bi bi-trash"></span> remover</a>
                                            </div>
                                        </div>
                                        <div class="sms-replications px-3">
                                            <form action="<?= route('answer.leads') ?>" class="pb-4" method="post" id="send-response-to">
                                                <input type="hidden" name="client_mail" value="<?= $lead->email ?>">
                                                <div class="input-group">
                                                    <input type="text" name="msg" class="form-input input-block" placeholder="Escreve uma respota pra este cliente...">
                                                </div>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-orange" style="margin-right: -10px !important;">Enviar</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <span class="card-heading">
                                    <span class="bi bi-emoji-frown"></span>
                                    Estás sem mensagem de momento!
                                </span>
                            <?php endif; ?>
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
<script src="<?= asset('js/leads/index.js') ?>"></script>
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