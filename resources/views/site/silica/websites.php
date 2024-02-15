<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/uboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>

</head>

<body class="uboard contain-aside">

    <?= parts('aside') ?>
    <?= parts('bonavbar') ?>


    <div class="board mt-4">
        <div class="board-header">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="contain-title align-items-baseline d-flex w-100">
                            <div>
                                <span class="bold-title">Meus websites</span>
                                <span class="text-muted d-block">Os websites que já obeteve até aqui</span>
                            </div>
                            <div class="title-brand ml-auto">
                                <a href="<?= route('home') ?>"><small>Home</small></a>
                                <small>/</small>
                                <small>Meus websites</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.board-header-->
        <?php $about = [['Templates', 'layers-fill'], ['Websites', 'grid-fill'], ['Campanhas', 'megaphone', '0']]; ?>
        <div class="contain-board mt-4">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row">
                        <?php for($i = 0; $i < 2; $i++): ?>
                            <div class="col-12 has-dt-item">
                                <div class="row dt-item align-items-center">
                                    <div class="dt-contain col-12 col-md-1">
                                        <small class="text-muted d-block">#</small>
                                        <span class="title">1</span>
                                    </div>
                                    <div class="dt-contain col-12 col-md-3">
                                        <small class="text-muted d-block">Dóminio</small>
                                        <span class="title">meudominio.silica.ao</span>
                                    </div>
                                    <div class="dt-contain col-12 col-md-3">
                                        <small class="text-muted d-block">Validade</small>
                                        <span>Licença expira em <?= '24' ?> dias </span>
                                    </div>
                                    <div class="dt-contain col-12 col-md-2">
                                        <small class="text-muted d-block">Visitas</small>
                                        <span>Teve <?= '0' ?> acessos </span>
                                    </div>
                                    <div class="dt-contain col-12 col-md-2">
                                        <small class="text-muted d-block">Data de criação</small>
                                        <span><?= '2024-12-05' ?> </span>
                                    </div>
                                    <div class="dt-contain col-12 col-md-1">
                                        <small class="text-muted d-block"></small>
                                        <span class="bi bi-chevron-down open-actions"></span>
                                    </div>
                                </div>
                                <div class="row dt-item-actions">
                                    <div class="col-12 col-md-2">
                                        <a href=""><span class="bi bi-pencil-square">Editar</span></a>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a href=""><span class="bi bi-eye">Liberar acesso</span></a>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a href=""><span class="bi bi-arrow-down">Tirar do ár</span></a>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a href=""><span class="bi bi-trash-fill">Deletar</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <span class="d-flex my-4"></span>
        <span class="d-flex my-4"></span>
        <span class="d-flex my-4"></span>
        <span class="d-flex my-4"></span>
        <span class="d-flex my-4"></span>
        </div><!--/.contain-board-->
    </div>



</body>

</html>

<script>
    const btns_open_actions = document.querySelectorAll('.open-actions');
    const all_them = document.querySelectorAll('.has-dt-item');

    btns_open_actions.forEach((item) => {
        item.addEventListener('click', (e) => {
            all_them.forEach((all) => {
                if (all !== item.parentNode.parentNode.parentNode) {
                    all.classList.remove('active');
                }
            });

            if (item.parentNode.parentNode.parentNode.classList.contains('active')) {
                item.parentNode.parentNode.parentNode.classList.remove('active');
            } else {
                item.parentNode.parentNode.parentNode.classList.add('active');
            }
        });
    });

</script>