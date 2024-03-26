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
</head>

<body>

    <div class="wrapper">
        <?= parts('nav.header') ?>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="step">
                                <h5 class="card-heading my-4 ai-center" style="color: #000">Crie um site</h5>
                                <span>Escolha um dos nossos premiados templates, fontes de designer e paletas de cores.</span>
                            </div>
                            <div class="step">
                                <h5 class="card-heading my-4 ai-center" style="color: #000">Venda produtos e serviços</h5>
                                <span>Abra uma loja de eCommerce, marque horários ou cobre pelos seus conhecimentos - tudo em uma plataforma criada só para você.</span>
                            </div>
                            <div class="step">
                                <h5 class="card-heading my-4 ai-center" style="color: #000">Divulgue a sua empresa</h5>
                                <span>As campanhas por e-mail e as ferramentas de redes sociais com a sua marca facilitam a retenção de clientes e o aumento da sua base.</span>
                            </div>
                        </div>
                        <div class="d-none d-lg-block col-md-12 col-lg-6 col-12">
                            <div class="step-img w-100 w-lg-70 w-md-90 mx-auto">
                                <img src="<?= asset('img/shutterstock_411902782.jpg') ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= parts('services.sotfwares') ?>

        <div class="my-5"></div>

        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h3 class="card-heading text-black">Temos websites prontos <br> para todo tipo de negócio</h3>
                </div>
                <div class="my-5"></div>
                <div class="thinked-dir">
                    <div class="container">
                        <div class="row" style="justify-content: flex-end">
                            <span class="fas fa-arrow-left" id="left"></span>
                            <span class="fas fa-arrow-right active" id="right"></span>
                        </div>
                    </div>
                </div>
                <div class="my-4"></div>
                <div class="thinked">
                    <a href="<?= route('browse') ?>" class="thinked-item">
                        <div class="thinked-overlay"></div>
                        <img src="<?= asset('img/1675138925_107640.webp') ?>" alt="">
                        <span class="text-white">Restaurante</span>
                    </a>
                    <a href="<?= route('browse') ?>" class="thinked-item">
                        <div class="thinked-overlay"></div>
                        <img src="<?= asset('img/826864-Fitness-Brown-haired-Dumbbells-Workout.jpg') ?>" alt="">
                        <span class="text-white">Saúde</span>
                    </a>
                    <a href="<?= route('browse') ?>" class="thinked-item">
                        <div class="thinked-overlay"></div>
                        <img src="<?= asset('img/woman-shopping-store.webp') ?>" alt="">
                        <span class="text-white">E-commerce</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="my-5"></div>

        <?php
        $how_to = [
            'Escolha um template e inicie uma avaliação gratuita',
            'Obtenha um nomde de domíno personalizado no primeiro ano ao assinar o plano anual do site',
            'Use o nosso constructor de websites para adicionar textos e fotos',
            'Personalize o site de acordo com a sua marca: são centenas de fontes, cores e fotos de acervo.',
            'Não tem logotipo? Faça um com a nossa ferramenta on-line grátis.',
            'Publique seu site e divulgue usando mídias sociais e ferramentas de e-mail marketing .'
        ];
        ?>

        <div class="card py-5">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h3 class="card-heading" style="color: #000">Como trabalhar com essa ferramenta</h3>
                            <span>Ensimanos você a trabalhar com a nossa ferramenta de criação de website do jeito certo</span>
                        </div>
                        <div class="col-lg-6 col-12 mt-lg-0 mt-5">
                            <div class="asks how_to">
                                <div class="ask-header">
                                    <span class="ask-header-title">Como criar website</span>
                                    <span class="ml-auto fas fa-minus ask-icon"></span>
                                </div>
                                <div class="ask-contain active">
                                    <?php foreach ($how_to as $key => $value) : ?>
                                        <div class="d-flex ai-center">
                                            <span><?= $key + 1 . '.' ?></span>
                                            <span><?= $value ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="asks how_to">
                                <div class="ask-header">
                                    <span class="ask-header-title">Como divulgar o teu negócio</span>
                                    <span class="ml-auto fas fa-plus ask-icon"></span>
                                </div>
                                <div class="ask-contain">
                                    <?php foreach ($how_to as $key => $value) : ?>
                                        <div class="d-flex ai-center">
                                            <span><?= $key + 1 . '.' ?></span>
                                            <span><?= $value ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="asks how_to">
                                <div class="ask-header">
                                    <span class="ask-header-title">Como ter um site personalizado</span>
                                    <span class="ml-auto fas fa-plus ask-icon"></span>
                                </div>
                                <div class="ask-contain">
                                    <?php foreach ($how_to as $key => $value) : ?>
                                        <div class="d-flex ai-center">
                                            <span><?= $key + 1 . '.' ?></span>
                                            <span><?= $value ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?= parts('faqs.faq') ?>


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper-->


</body>

</html>
<script src="<?= asset('js/ask.js') ?>"></script>
<?php

use \Sienekib\Mehael\Support\Auth;

if (Auth::check()) :  ?>
    <input type="hidden" id="activeUserId" value="<?= Auth::user()->id ?>">
<?php endif; ?>
<script>
    $('#introMailForm').submit((e) => {
        e.preventDefault();
        var activeUser = $('#activeUserId').val();
        if (activeUser) {
            const formData = new FormData(e.target);
            formData.append('account', activeUser);
            $.ajax({
                url: '/introMail',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res)
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
        alert('Entre com a sua conta!');
    });

    const thinked = document.querySelector('.thinked');
    const spanRight = document.querySelector('#right');
    const spanLeft = document.querySelector('#left');
    spanRight.addEventListener('click', function(event) {
        var height = $('.thinked .thinked-item').height();
        $('.thinked').animate({
            scrollLeft: `+=${height}`
        }, 500);
        spanRight.classList.remove('active');
        spanLeft.classList.add('active');
        //event.preventDefault();
    });
    spanLeft.addEventListener('click', function(event) {
        var height = $('.thinked .thinked-item').height();
        $('.thinked').animate({
            scrollLeft: `-=${height}`
        }, 500);
        spanRight.classList.add('active');
        spanLeft.classList.remove('active');
        //event.preventDefault();
    });
</script>