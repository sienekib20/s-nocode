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
                    <form action="" method="POST" class="w-100">
                        <div class="row">
                            <div class="col-xxs-12 col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="username" class="form-input input-block input-warning" placeholder="Introduz o seu nome">
                                    <small class="text-orange form-icon icon-right fas fa-user"></small>
                                </div>
                            </div>
                            <div class="col-xxs-12 col-lg-6 mt-xxs-3 mt-lg-0">
                                <div class="form-group has-icon">
                                    <input type="text" name="username" class="form-input input-block input-warning" placeholder="O seu endereço email">
                                    <small class="text-orange form-icon icon-right fas fa-envelope"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xxs-12 col-lg-6">
                            </div>
                            <div class="col-xxs-12 col-lg-6">
                                <div class="form-group has-icon">
                                    <input type="text" name="username" class="form-input input-block input-warning" placeholder="Nº de telefone">
                                    <small class="text-orange form-icon icon-right fas fa-phone"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group has-icon">
                                    <textarea name="" rows="5" class="input-block form-input input-warning" placeholder="O que pretende ?"></textarea>
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

<script>
    $(document).ready(function() {
        const asked_top = document.querySelectorAll('.asked-top');
        asked_top.forEach((item) => {
            item.addEventListener('click', (e) => {
                e.target.parentNode.classList.toggle('active');
            })
        });
    });
    $('.faqItem-top').click((e) => {
        e.preventDefault();
        $(e.target).toggleClass('active');
    });
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317" integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA==" data-cf-beacon='{"rayId":"842ce7672b5f304a","version":"2023.10.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>