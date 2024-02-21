<?php $user_id = \Sienekib\Mehael\Support\Auth::user()->id; ?>
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
</head>

<body>

    <div class="extended">
        <!-- sidenav -->
        <?= parts('user.aside') ?>
        <!-- navbar -->
        <?= parts('user.topnav') ?>

        <div class="contain-extends mt-1">
            
            <div class="ex-card">
                <div class="container-sm mt-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="bold">As Minhas Campanhas </h5>
                            <span class="text-muted">Veja como os teus clientes estão interagindo contigo, saiba como evoluem os teus negócios</span>
                        </div>
                    </div>
                </div>

                <div class="container-sm mt-3 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="inbox-contain">
                                <div class="inbox-top row align-items-center pb-3">
                                    <span class="col-12 col-md-2 text-muted">Mensagens</span>
                                    <div class="d-flex col-12 col-md-10 align-items-center justify-content-flex-start justify-content-md-flex-end">
                                        <a href="">recarregar</a>
                                        <input type="text" placeholder="Pesquisar email" class="form-input ml-3">
                                    </div>
                                </div>
                                <div class="inbox-body">
                                    <?php for($i = 0; $i < 3; $i++): ?>
                                        <div id="open-msm-<?=$i?>" class="row mx-0 align-items-center inbox">
                                            <span class="col-1"> <small class="box"></small> </span>
                                            <span class="col-3">
                                                <a href="<?= route('user', $user_id . '/campanhas/mail') ?>">nome do cliente</a>
                                            </span>
                                            <div class="col-4">
                                                <span class="bold">Titulo mensagem</span>
                                                <span class="omit">Lorem ipsum dolor sit, amet consectetur adipisicing, elit. Culpa officia dolores quaerat et</span>
                                            </div>
                                            <div class="col-2"></div>
                                            <span class="col-2">Há 5 mins</span>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>

</html>