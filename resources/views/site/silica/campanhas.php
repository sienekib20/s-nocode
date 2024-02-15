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
                                <span class="bold-title">Minhas campanhas</span>
                                <span class="text-muted d-block">Uma forma de interagir com o teus clientes</span>
                            </div>
                            <div class="title-brand ml-auto">
                                <a href="<?= route('home') ?>"><small>Home</small></a>
                                <small>/</small>
                                <small>Minhas campanhas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.board-header-->
        <?php $about = [['Templates', 'layers-fill'], ['Websites', 'grid-fill'], ['Campanhas', 'megaphone', '0']]; ?>
        <div class="contain-board mt-4">
            <div class="card-body">
                <?php $campanha = true; if(!$campanha):?>
                    <div class="row">
                        <div class="col-12 flex-direction-column d-flex align-items-center justify-content-center vh-50 sem-interacao">
                            <span class="bi bi-chat"></span>
                            <small class="text-muted">Não houve nenhuma interação até aqui</small>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row px-4 data-campanhas justify-content-space-between">
                        <?php for($i = 0; $i < 4; $i++):  ?>
                            <div class="ld-item col-12 col-md-12">
                                <div class="d-flex align-items-center">
                                    <span class="letter">C</span> <!--/.Primeira letra de email do usuario: por enquanto c de cliente-->
                                    <div class="username ml-3">
                                        <span>Fulano de tal</span>
                                        <small class="text-muted d-block">email@dominio.com</small>
                                    </div>
                                </div>
                                <small>Enviado há <?= '2' ?> dias</small>
                                <small class="text-muted"><?= '0' ?> resposta</small>
                                <a href=""> <small>responder</small> </a>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
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
