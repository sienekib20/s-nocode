<!DOCTYPE html>
<html lang="en">

<head>
    <title>%title%</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finlandica:ital,wght@0,400..700;1,400..700&family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <style>
        .form-input,
        small,
        span,
        .btn {
            font-family: 'Finlandica' !important;
        }
    </style>
</head>

<body class="secondary">

    <div class="wrapper">
        <div class="card">
            <div class="card-body vh-100">
                <div class="container h-100">
                    <div class="row ai-center jc-center h-100">
                        <div class="col-lg-4 col-md-6 col-sm-8 col-11">
                            <h5 class="card-heading text-black">Crie uma conta</h5>
                            <span class="d-block mb-4">Antes demais crie uma conta na nossa plataforma para começar a tua jornada</span>
                            <form action="<?= route('registe') ?>" class="" method="POST">
                                <div class="input-group">
                                    <input type="text" name="nome" class="form-input input-orange" placeholder="Nome & apelido">
                                    <small class="invalid-feedback"></small>
                                </div>
                                <div class="input-group">
                                    <input type="text" name="telefone" class="form-input input-orange" id="input-phone" placeholder="Nº Telefone">
                                    <small class="invalid-feedback"></small>
                                </div>
                                <div class="input-group">
                                    <input type="text" name="email" class="form-input input-orange" id="input-mail" placeholder="Endereço email">
                                    <small class="invalid-feedback"></small>
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-input input-orange" placeholder="Senha">
                                    <small class="invalid-feedback"></small>
                                </div>
                                <div class="input-group">
                                    <input type="password" name="re_password" class="form-input input-orange" placeholder="Confirmar senha">
                                    <small class="invalid-feedback"></small>
                                </div>

                                <div class="input-group mt-4">
                                    <button class="btn btn-orange btn-block">Cadastrar</button>
                                </div>
                                <div class="input-group">
                                    <span class="d-block mt-3">Já tens conta.
                                        <a href="<?= route('entrar') ?>" style="color: #f71">Entrar</a>
                                    </span>
                                </div>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div> <!--/.wrapper-->

    <input type="hidden" class="session_message" value="<?= session()->getFlashMessage('erro') ?? '' ?>">

</body>

</html>
<script src="<?= asset('js/auths/index.js') ?>"></script>
<script src="<?= asset('js/ui/kib-ui.js') ?>"></script>
<script>
    $(document).ready(() => {
        if ($('.session_message').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('Aviso');
            $('.cool-alert-text').text($('.session_message').val());
        }
        $('.bg-secondary').css('backgroundColor', '#f1f1f1');
    });
</script>