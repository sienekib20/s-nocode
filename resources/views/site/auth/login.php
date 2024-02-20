<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%title%</title>
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/ui-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style-bs.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body class="secondary">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon bg-orange d-flex align-items-center justify-content-center">
                            <span class="fa fa-user"></span>
                        </div>
                        <h3 class="text-center mb-4">Silcia Page</h3>
                        <form action="#" class="login-form">
                            <div class="form-group">
                                <input type="text" class="form-control rounded-left" placeholder="Username" required>
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" class="form-control rounded-left" placeholder="Password" required>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary text-muted">Lembrar de mim
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="#" style="color: #000">Esqueceu a senha?</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-orange rounded submit p-3 px-5">Vamos começar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?= parts('ui.ui-alert') ?>
    <!-- <div class="wrapper">

        <div class="auths d-flex w-100 vh-100 align-items-center justify-content-center">
            <form action="<?= route('entrar') ?>" class="w-100" method="post">
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
        </div> /.auths-

    </div> /.wrapper-->

    <input type="hidden" class="session_message_s" value="<?= session()->getFlashMessage('success') ?? '' ?>">
    <input type="hidden" class="session_message" value="<?= session()->getFlashMessage('erro') ?? '' ?>">
</body>

</html>

<script>
    $(document).ready(() => {
        $('.ui-alert-icon').removeClass('bi bi-x');
        $('.ui-alert-icon').removeClass('bi bi-check');

        if ($('.session_message').val() != '') {
            $('.ui-alert-title').text('Erro');
            $('.ui-alert-icon').addClass('bi bi-x').css('background-color', '#c00');
            $('.ui-alert-text').text($('.session_message').val());
            var elementContainer = document.querySelector('.ui-alert');
            var element = document.getElementById('uialert');
            element.classList.add('pulse');
            elementContainer.classList.add('active');

            // Remova a classe de bounce após a animação terminar
            setTimeout(function() {
                element.classList.remove('pulse');
                //elementContainer.classList.remove('active');
            }, 700); // A duração da animação é de 0.5s (500ms)
        }
        if ($('.session_message_s').val() != '') {
            console.log($('.session_message_s').val())
            $('.ui-alert-title').text('Sucesso');
            $('.ui-alert-icon').addClass('bi bi-check').css('background-color', 'green');
            $('.ui-alert-text').text($('.session_message').val());
            var elementContainer = document.querySelector('.ui-alert');
            var element = document.getElementById('uialert');
            element.classList.add('pulse');
            elementContainer.classList.add('active');

            // Remova a classe de bounce após a animação terminar
            setTimeout(function() {
                element.classList.remove('pulse');
                //elementContainer.classList.remove('active');
            }, 700); // A duração da animação é de 0.5s (500ms)
        }
        $('.bg-secondary').css('backgroundColor', '#f1f1f1');
    });
</script>