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
    <!--<?= parts('ui.ui-alert') ?> -->

    <div class="wrapper">
        <div class="card">
            <div class="card-body vh-100">
                <div class="container h-100">
                    <div class="row ai-center jc-center h-100">
                        <div class="col-lg-4 col-md-6 col-sm-8 col-11">
                            <h5 class="card-heading text-black">Faça o login para começar</h5>
                            <span class="d-block mb-4">Entre com os dados da sua conta para ter acesso e começar a tua jornada</span>
                            <form action="<?= route('entrar') ?>" method="post">
                                <div class="input-group">
                                    <input type="text" name="username" class="form-input input-orange" placeholder="Endereço email">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-input input-orange" placeholder="Senha">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="input-group">
                                    <button type="submit" class="btn btn-orange btn-block">Faça Login</button>
                                </div>
                                <div class="input-group flex-column">
                                    <span class="d-block">Podes criar uma conta.
                                        <a href="<?= route('register') ?>" style="color: #f71">clique aqui</a>
                                    </span>
                                    <small class="text-muted d-block">Esqueceu a sua senha?
                                        <a href="" style="color: #000; text-decoration: underline;">recupere</a>
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!--/.wrapper-->

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