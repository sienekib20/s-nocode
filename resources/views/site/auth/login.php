<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%title%</title>
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body class="bg-secondary">

    <?= parts('ui.cool-alert') ?>
    <div class="wrapper">

        <div class="auths d-flex w-100 vh-100 align-items-center justify-content-center">
            <form action="<?= route('autenticar') ?>" class="w-100" method="POST">
                <div class="container-sm d-flex align-items-center justify-content-center">
                    <div class="col-xxs-12 col-sm-8 col-lg-4 py-4">
                        <div class="form-group">
                            <span class="form-title card-title">Nocode</span>
                            <small class="text-muted d-block mb-3">Faça o login para começar</small>
                        </div>
                        <div class="form-group">
                            <input type="text" name="username" class="form-input input-block" placeholder="Usuario">
                            <small class="invalid-feeback"></small>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-input input-block" placeholder="Palavra-passe">
                            <small class="invalid-feeback"></small>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-orange input-block">Entrar</button>
                        </div>
                        <div class="form-group">
                            <span class="d-block mt-3">Podes criar uma conta.
                                <a href="<?= route('register') ?>" style="color: #8a8a8a">clique aqui</a>
                            </span>
                            <small class="text-muted d-block">Esqueceu a sua senha?
                                <a href="" style="color: #000">recupere</a>
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div> <!--/.auths-->

    </div> <!--/.wrapper-->

    <input type="hidden" class="session_message_s" value="<?= session()->getFlashMessage('success') ?? '' ?>">
    <input type="hidden" class="session_message" value="<?= session()->getFlashMessage('erro') ?? '' ?>">
</body>

</html>

<script>
    $(document).ready(() => {
        if ($('.session_message').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('Erro');
            $('.cool-alert-text').text($('.session_message').val());
        }
        if ($('.session_message_s').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('success');
            $('.cool-alert-text').text($('.session_message_s').val());
        }
        $('.bg-secondary').css('backgroundColor', '#f1f1f1');
    });
</script>