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
        <div class="auths d-flex w-100 vh-100 my-xxs-4 my-lg-0 align-items-xxs-start align-items-lg-center justify-content-center">
            <form action="<?= route('registe') ?>" class="w-100" method="POST">
                <div class="container-sm d-flex align-items-center justify-content-center">
                    <div class="col-11 col-sm-9 col-md-7 col-lg-5 py-4 px-4">
                        <div class="form-title">
                            <span class="title">Nocode</span>
                            <small class="text-muted d-block mb-3">Cria a sua conta</small>
                        </div>
                        <small class="form-line-divider"></small>
                        <div class="form-group">
                            <small class="input-label">Nome & Apelido</small>
                            <div class="content-input">
                                <input type="text" name="nome" class="form-input input-block" placeholder="Nome">
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <small class="input-label">Telefone</small>
                            <div class="content-input">
                                <input type="text" name="telefone" class="form-input input-block" id="input-phone" placeholder="Telefone">
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <small class="input-label">Seu endereço e-mail</small>
                            <div class="content-input">
                                <input type="text" name="email" class="form-input input-block" id="input-mail" placeholder="Email">
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <small class="input-label">Senha</small>
                            <div class="content-input">
                                <input type="password" name="password" class="form-input input-block" placeholder="Palavra-passe">
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <small class="input-label">Confirmar senha</small>
                            <div class="content-input">
                                <input type="password" name="re_password" class="form-input input-block" placeholder="Reescrever Palavra-passe">
                                <small class="invalid-feedback"></small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button class="btn btn-orange input-block">Cadastrar</button>
                        </div>
                        <div class="form-group">
                            <span class="d-block mt-3">Já tens conta.
                                <a href="<?= route('entrar') ?>" style="color: #f71">Entrar</a>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div> <!--/.auths-->

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