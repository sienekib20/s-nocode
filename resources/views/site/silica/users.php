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
                                <span class="bold-title">Meus templates</span>
                                <small class="tw-muted d-block">Os templates a tua vista</small>
                            </div>
                            <div class="title-brand ml-auto">
                                <a href=""><small>Home</small></a>
                                <small>/</small>
                                <small>Visão geral</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.board-header-->
        <?php $about = [['Templates', 'layers-fill'], ['Websites', 'grid-fill'], ['Campanhas', 'megaphone', '0']]; ?>
        <div class="contain-board">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row align-items-center justify-content-flex-start">
                        <?php foreach ($about as $item) : ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <div class="d-flex align-items-baseline"> 
                                            <span class="bi bi-<?= $item[1] ?> d-block mr-2"></span>
                                            <span><?= $item[0] ?></span>
                                            <?php if(isset($item[2])): ?>
                                                <div class="ml-auto aside-brand"><?= $item[2] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-plan-body mt-2">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi"></small>
                                                <a href=""><small class="text-muted">Explorar</small></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div><!--/.contain-board-->
    </div>


</body>

</html>

<script></script>