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

        <div class="card mb-0 mb-lg-3">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 card-top">
                            <div class="card-title">
                                <h3 class="card-heading text-black d-block">Meus websites</h3>
                                <span>São os websites que já adquiriste até aqui. Faça o monitoramento desses websites e veja como os teus clientes estão interangindo.</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 card-top">
                            <h3 class="card-heading op-0"></h3>
                            <h3 class="card-heading op-0"></h3>
                            <h3 class="card-heading op-0"></h3>
                            <span class="d-block">Por enquanto, só podes ter no máximo até 2 websites.</span>
                            <small class="text-muted">Podes editar, eliminar os teus websites adquiridos.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.card-top-->
        <div class="card mt-4 mt-lg-4">
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

        <div class="card mt-5">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <?php if (!empty($temp_parc)) : ?>
                            <?php foreach ($temp_parc as $key => $d) : ?>
                                <div class="col-lg-4 my-4 my-lg-0">
                                    <div class="dashitem-info pb-4">
                                        <label for="webcheck-<?= $d->temp_parceiro_id ?>">
                                            <input type="checkbox" id="webcheck-<?= $d->temp_parceiro_id ?>" class="currentWebsiteCheck" style="border-radius: 2px">
                                            <small>Selecionar ítem</small>
                                        </label>
                                        <input type="hidden" id="website-dominio-<?= $d->temp_parceiro_id ?>" value="<?= $d->dominio ?>">
                                        <span class="card-heading"><?= $d->dominio ?>.silicapages.com</span>
                                        <span>Website em execução</span>
                                        <small class="text-muted d-block">
                                            Data adesão: <?= $d->created_at ?> | Válido por 30 dias
                                        </small>
                                        <small class="text-muted d-block op-0">a</small>
                                        <div class="d-flex">
                                            <a href="<?= route('live', $d->referencia) ?>" class="d-flex  text-black text-underline dash-website-action" target="_blank">
                                                <small style="pointer-events: none">Ver</small>
                                            </a>
                                            <a href="#" class="d-flex ml-4 text-black text-underline dash-website-action">
                                                <small style="pointer-events: none">excluir</small>
                                            </a>
                                            <a href="<?= route('edit.' . $d->dominio, explode('-', $d->template_uuid)[0]) ?>" class="d-flex ml-4 text-black text-underline dash-website-action" target="_blank">
                                                <small style="pointer-events: none">editar </small>
                                            </a>
                                            <a href="#" class="d-flex ml-4 text-black text-underline dash-website-action">
                                                <small style="pointer-events: none">cancelar uso</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span>Oops não tens nenhum website... <a href="<?= route('browse') ?>" class="btn" style="text-decoration: underline;">carrege</a></span>
                        <?php endif; ?>
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
<script src="<?= asset('js/websites/index.js') ?>"></script>
<script>
    applyDarkNavbar();
    $('#aa').click(function(e) {
        e.preventDefault();
        var tab = window.open("", "_self", "");
        tab.close();
    });

    $(document).ready(function() {
        const currentWebsiteChecks = document.querySelectorAll('.currentWebsiteCheck');
        $('.contain-websites-links a').click(function(e) {
            e.preventDefault();
            var actionToExecute = $(this).attr('title');
            var countChecked = -1;
            currentWebsiteChecks.forEach(function(currentWebsiteCheck) {
                if (currentWebsiteCheck.checked) {
                    countChecked++;
                    var currentItemID = currentWebsiteCheck.id.split('-')[1];
                    if (actionToExecute == 'deletar') {
                        make_alert('Tens a certeza de que precisas deletar esse website ?');
                    }
                    return;
                }
            });
            if (countChecked == -1) {
                make_alert('Por favor seleciona um item');
            }
        });
        $('.changeStatus').click(function(e) {
            e.preventDefault();
            var iconText = $(this).find('span');
            if (iconText[0].classList[1] == 'fa-play') {
                iconText[0].className = 'fas fa-pause';
            } else {
                iconText[0].className = 'fas fa-play';
            }
        });
    });
</script>