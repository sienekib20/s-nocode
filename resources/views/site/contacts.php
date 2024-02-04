<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="sx">
        <?= parts('nav.navbar') ?>
        <?= parts('ui.cool-alert') ?>

        <div class="wallpaper vh-45">
            <div class="container-sm" style="z-index: 1080">
                <div class="row align-items-center h-100">
                    <div class="col-12">
                        <small class="text-muted" style="color: rgba(255, 255, 255, 0.7) !important">Seja bemvindo a Sílica Partner</small>
                        <span class="card-title text-white">Entre em contacto</span>
                    </div>
                </div>
            </div>
        </div> <!-- wallpaper -->

        <small class="d-block mt-5"></small>

        <?php
        $contacts = [
            ['Endereço email', 'email@dominio.com', 'outro@gmail.com', 'bi bi-envelope'],
            ['Telefone', '+244 9xx xxx xxx', '+244 9xx xxx xxx', 'bi bi-telephone'],
            ['Escritório', 'Bº Azul, Ref: Memorial Dr. A. Neto', '', 'bi bi-geo']
        ];
        ?>

        <div class="card-body">
            <div class="container-sm">
                <div class="row align-items-center justify-content-center">
                    <?php foreach ($contacts as $item) : ?>
                        <div class="col-md-3">
                            <div class="card-plan mt-xxs-3">
                                <div class="card-plan-top">
                                    <div class="d-flex align-items-center flex-direction-column" style="font-size: 20px;">
                                        <small class="<?= $item[3] ?>"></small>
                                        <small><?= $item[0] ?></small>
                                    </div>
                                </div>
                                <div class="card-plan-body mt-2">
                                    <div class="d-flex flex-direction-column">
                                        <div class="d-flex align-items-center justify-content-center card-plan-item">
                                            <small class="text-muted d-block text-center"><?= $item[1] ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div> <!--/.card-plan-->
                        </div> <!--/.col-md-3-->
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <small class="d-block my-4"></small>

        <div class="card-body">
            <div class="container-sm">
                <div class="w-xxs-100 w-lg-75 mx-auto mt-5">
                    <form action="<?= route('contactar') ?>" method="POST" class="w-100">
                        <div class="row">
                            <div class="col-xxs-12 col-lg-6">
                                <div class="form-group">
                                    <small class="input-label">Seu nome</small>
                                    <input type="text" value="<?= $data->username ?>" name="username" class="form-input input-block input-warning" placeholder="Introduz o seu nome" required>
                                    <small class="text-orange form-icon icon-right fas fa-user"></small>
                                </div>
                            </div>
                            <div class="col-xxs-12 col-lg-6 mt-xxs-3 mt-lg-0">
                                <div class="form-group has-icon">
                                    <small class="input-label">O email informativo</small>
                                    <input type="text" value="<?= $data->email ?>" name="email" class="form-input input-block input-warning" placeholder="O seu endereço email" required>
                                    <small class="text-orange form-icon icon-right fas fa-envelope"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xxs-12 col-lg-6">
                            </div>
                            <div class="col-xxs-12 col-lg-6">
                                <div class="form-group has-icon">
                                    <small class="input-label">Telefone</small>
                                    <input type="text" name="telefone" class="form-input input-block input-warning" placeholder="Nº de telefone" required>
                                    <small class="text-orange form-icon icon-right fas fa-phone"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group has-icon">
                                    <textarea name="mensagem" rows="5" class="input-block form-input input-warning" placeholder="O que pretende ?" required></textarea>
                                    <small class="text-orange form-icon icon-right fas fa-pencil" style="top: 12%"></small>
                                </div>
                            </div>
                        </div>

                        <small class="d-flex mt-3"></small>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="remember">
                                        <input type="checkbox" name="" id="remember" class="form-input">
                                        <span>Salvar o meu nome, email e telefone</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <small class="d-flex mt-3"></small>

                        <div class="row">
                            <div class="col-xxs-12 col-lg-6">
                                <div class="form-group">
                                    <button class="btn btn-orange input-block">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <small class="d-block my-4"></small>
        <small class="d-block my-4"></small>



        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>
<input type="hidden" class="sms" value="<?= session()->getFlashMessage('success') ?? '__aor' ?>">
<input type="hidden" class="sm" value="<?= session()->getFlashMessage('erro') ?? '' ?>">

<script>
    $(document).ready(function() {
        const asked_top = document.querySelectorAll('.asked-top');
        asked_top.forEach((item) => {
            item.addEventListener('click', (e) => {
                e.target.parentNode.classList.toggle('active');
            })
        });
        if ($('.sm').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('Erro');
            $('.cool-alert-text').text($('.sm').val());
        } else if ($('.sms').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('success');
            $('.cool-alert-text').text($('.sms').val());
        }
    });
    $('.faqItem-top').click((e) => {
        e.preventDefault();
        $(e.target).toggleClass('active');
    });
</script>