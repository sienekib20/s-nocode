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

        <div class="wallpaper vh-80">
            <div class="container-sm" style="z-index: 1080">
                <div class="row align-items-center h-100">
                    <div class="text-center text-md-center col-12 col-md-12 col-xxs-12">
                        <span class="h3 w-100 d-block text-bold text-white d-block mb-md-0 mb-xxs-5">Torne-se independente <br> Crie a sua lógica de negócios</span>
                        <span class="d-xxs-none d-md-block my-3 mb-5 text-muted">Conheça a nova ferramenta dos Sílica, um criador de landing pages sem
                            necessidade de <br> mexer no código, apenas com um click e já está!</span>
                        <div class="d-flex align-items-center justify-content-center col-12 col-md-12 col-xxs-12">
                            <a href="" class="btn btn-outline-orange">Experimente</a>
                            <a href="<?= route('browse') ?>" class="btn btn-white ml-2">Explorar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- wallpaper -->

        <div class="container-sm w-80">
            <div class="row">
                <div class="col-xxs-12 col-lg-8">
                    <div class="card-leading pt-4">
                        <span class="card-leading-title">Uma plataforma satisfazendo muitas necessidades dos seus usuários. Já temos mais de 4.5K de clientes a beneficiarem da nossa plataforma.</span>
                        <small class="card-leading-text text-muted mt-4">Estamos crescendo cada vez mais, faça parte da nossa comunidade</small>
                    </div>
                </div>
                <div class="col-xxs-12 col-lg-4 vh-40">
                    <div class="card-leading bg-orange h-100">
                        <span class="card-leading-title">Temos em massa 5M Site prontos para oferecer</span>
                        <small class="card-leading-text text-muted">Para você que quer negócios rápidos, temos sites disponíveis para ti.</small>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-block mt-5"></small>
        <small class="d-block mt-5"></small>

        <?php $about = [
            ['School', 'https://silicaweb.ao/sfront/ServicesSchool.php'],
            ['ERP', 'https://silicaweb.ao/sfront/ServicesErp.php'],
            ['Univ', 'https://silicaweb.ao/sfront/ServicesUniv.php'],
            ['Aqua', 'https://silicaweb.ao/sfront/ServicesAqua.php'],
            ['Health', 'https://silicaweb.ao/sfront/ServicesHealth.php'],
            ['RH', 'https://silicaweb.ao/sfront/ServicesRH.php'],
            ['Work', 'https://silicaweb.ao/sfront/ServicesWork.php']
        ]
        ?>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-12 text-center">
                            <span class="title d-block"> Conheça os nosso serviços</span>
                            <small class="text-muted">Somos parte do <a href="<?= 'https://silicaweb.ao/sfront' ?>" style="color:#f71">Universo sílica</a></small>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <div class="card-body">
                <div class="container-sm">
                    <div class="row align-items-center justify-content-center">
                        <?php foreach ($about as $item) : ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <div class="d-flex align-items-baseline"> <small>Sílica <?= $item[0] ?></small></div>
                                    </div>
                                    <div class="card-plan-body mt-2">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi"></small>
                                                <a href="<?= $item[1] ?>"><small class="text-muted">Saber mais</small></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div> <!--/.silica-->

        <?php
        $asked = [
            ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="">contacto</a>', 'active'],
            ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', ''],
            ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', ''],
            ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', ''],
            ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '']
        ];
        ?>

        <small class="d-block my-4"></small>
        <small class="d-block my-4"></small>
        <small class="d-block my-4"></small>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title text-center col-12">
                            <span class="d-block bold">Planos de uso</span>
                            <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> Escolha única trazendo uma poupança múltipla</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body mt-5">
                <div class="container-sm">
                    <div class="row align-items-start justify-content-center">
                        <?php $type = ['Plano experimental', 'Plano de negócios', 'Plano empresarial'];
                        $xx = 0;
                        foreach ($enviar as $planos) :  ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <span class="title d-block"><?= $planos['pacote'] ?></span>
                                        <div class="d-flex align-items-baseline"> <small>Preço oficial</small> <span class="d-block">0,00KZ</span></div>
                                    </div>
                                    <div class="card-plan-body">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted"><?= $type[$xx]; ?></small>
                                            </div>
                                        </div>
                                        <?php if ($planos['pacote'] == 'Básico') : ?>
                                            <a href="{{ route('aderir', 1) }}" class="btn btn-orange w-20 input-block my-3 d-block btn-rounded"> <span class="bi bi-chevron-right"></span> </a>
                                        <?php else : ?>
                                            <a href="{{ route('aderir', 1) }}" class="btn btn-outline-orange w-20 input-block my-3 d-block btn-rounded"> <span class="bi bi-chevron-right"></span> </a>
                                        <?php endif; ?>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php $xx++;
                        endforeach; ?>
                    </div>
                </div>
            </div>
        </div> <!--/.card-->

        <small class="d-block mt-5"></small>

        <div class="card">
            <div class="card-body">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-xxs-12 col-lg-5 mb-xxs-4 mb-lg-0">
                            <span class="title">Respostas de Perguntas mais frequentes</span>
                            <small class="text-muted d-block">Tire todas as tuas dúvidas</small>
                        </div>
                        <div class="col-xxs-12 mt-xxs-3 mt-lg-0 col-lg-7">
                            <div class="faqs">
                                <?php foreach ($asked as $ask) : ?>
                                    <div class="faqItem">
                                        <div class="faqItem-top <?= $ask[2] ?>"> <span class="bi bi-dot"><?= $ask[0] ?></span></div>
                                        <div class="faqItem-body"><?= nl2br($ask[1]) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div> <!-- faqs -->
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.card-->


        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>


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