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
                                        <h4 class="title d-block">Meus websites</h4>
                                        <small class="ff">Todos os websites que já adquiriste.</small>
                                        <small class="d-block text-muted"> <span class="bi bi-arrow-right"></span> Só podes ter 2 websites no máximo por enquanto</small>
                                    </div>
                                    <div class="d-flex justify-content-flex-end px-0 col-2">
                                        <?= parts('nav.wr-hamburguer') ?>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->
                        <div class="card-body mt-4">
                            <div class="row">
                                <form action="" class="col-12 col-lg-7">
                                    <div class="input-group">
                                        <input type="text" id="liveSearch" class="form-input" placeholder="Pesquisar template...">
                                        <span class="bi bi-search"></span>
                                    </div>
                                </form>
                                <div class="col-12 col-lg-5 mt-2 mt-lg-0 contain-websites-links">
                                    <a href="" class="btn btn-white" title="pausar">
                                        <span class="fas fa-play"></span>
                                    </a>
                                    <a href="" class="btn btn-primary" title="editar">
                                        <span class="fas fa-edit"></span>
                                    </a>
                                    <a href="" class="btn btn-orange" title="deletar">
                                        <span class="fas fa-trash"></span>
                                    </a>
                                    <a href="" class="btn btn-white" title="abrir" id="aa">
                                        <span class="fas fa-arrow-up"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="wr-table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Domínio</th>
                                            <th>Status</th>
                                            <th>Validade</th>
                                            <th>Criação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data)) : ?>
                                            <?php foreach ($data as $d) : ?>
                                                <tr>
                                                    <td><input type="checkbox" id="webcheck-<?= $d->temp_parceiro_id ?>" class="currentWebsiteCheck"></td>
                                                    <td><?= $d->temp_parceiro_id ?></td>
                                                    <td><?= $d->titulo ?></td>
                                                    <td><?= $d->dominio ?></td>
                                                    <td> <span class="statusText"><?= 'Em execuçaõ' ?></span> <a href="" class="changeStatus"><span class="fas fa-play" style="pointer-events: none;"></span></a> </td>
                                                    <td><?= '' ?></td>
                                                    <td><?= $d->created_at ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="7">Sem nenhum dado</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
                        alert('Tens a certeza de que precisas deletar esse website ?');
                    }
                    return;
                }
            });
            if (countChecked == -1) {
                alert('Por favor seleciona um item');
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