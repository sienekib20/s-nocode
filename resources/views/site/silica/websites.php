<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%title%</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/ui-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style-bs.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <style>
        @font-face {
            font-family: 'MinhaFonte';
            src: url('minha-fonte.woff2') format('woff2');
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
    </style>
</head>

<body>

    <div class="extended">
            <!-- sidenav -->
            <?= parts('user.aside') ?>
            <!-- navbar -->
            <?= parts('user.topnav') ?>

            <div class="contain-extends mt-1">
                
                <div class="ex-card">
                    <div class="container-sm mt-3 ">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="bold">Meus websites</span> </h5>
                                <span class="text-muted">Os templates que você já aderiu até aqui, podes analisar o progresso, e fazer possíveis alterações</span>
                            </div>
                        </div>
                    </div>

                    <div class="container-sm mt-3 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <div class="d-flex align-items-baseline"> <small>Total de 0</small></div>
                                    </div>
                                    <div class="card-plan-body mt-2">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Websites ativos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <div class="d-flex align-items-baseline"> <small>Total de 0</small></div>
                                    </div>
                                    <div class="card-plan-body mt-2">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Websites expirados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        </div>
                    </div>

                    <div class="container-sm mt-2 mb-5">
                        <div class="row">
                            <div class="col-12 has-websites">
                                <?php foreach($data as $key => $d): ?>
                                    <div class="card-website">
                                        <div class="row card-website-contain px-2">
                                            <small class="col-12 col-md-1"><?= $key+1 ?></small>
                                            <small class="col-12 col-md-2"> 
                                                <a href=""><?= $d->dominio ?></a>
                                            </small>
                                            <small class="col-12 col-md-2">
                                                <a href="#">Template <?= $d->titulo ?></a>
                                            </small>
                                            <small class="col-12 col-md-2 d-flex align-items-center"> 
                                                <span class="d-block mr-2">Ativo</span> 
                                                <a href=""><bi class="fas fa-pause"></bi></a>  
                                            </small>
                                            <small class="col-12 col-md-2">Exp em <?= $d->prazo ?></small>
                                            <span class="col-12 col-md-3 d-flex align-items-center has-items">
                                                <a href=""><small class="bi bi-eye-fill"></small></a>
                                                <a href=""><small class="fas fa-edit"></small></a>
                                                <a href=""><small class="fas fa-trash"></small></a>
                                            </span>
                                        </div>
                                        <div class="card-website-opt"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

    </div>

</body>

</html>