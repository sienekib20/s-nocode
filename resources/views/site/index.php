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
                        <span class="h3 w-100 d-block text-bold text-white d-block">Torne-se independente <br> Crie a sua lógica de negócios</span>
                        <span class="d-xxs-none d-md-block my-3 mb-5 text-muted">Conheça a nova ferramenta dos Sílica, um criador de landing pages sem
                            necessidade de <br> mexer no código, apenas com um click e já está!</span>
                        <div class="d-flex align-items-center justify-content-center col-12 col-md-12 col-xxs-12">
                            <a href="" class="btn btn-outline-warning">Experimente</a>
                            <a href="<?= route('browse') ?>" class="btn btn-warning ml-2">Explorar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- wallpaper -->

        <div class="overview-items">
            <div class="sx-container">
                <span></span>
                <div class="overview-item">
                    <span class="qtd">+4.5K</span>
                    <small class="">Usuários aderiram</small>
                </div>
                <span></span>
                <div class="overview-item">
                    <span class="qtd">+1.5K</span>
                    <small class="">Landing pages</small>
                </div>
                <span></span>
                <div class="overview-item">
                    <span class="qtd">+1K</span>
                    <small class="">Dashboard</small>
                </div>
                <span></span>
                <div class="overview-item">
                    <span class="qtd">5K</span>
                    <small class="">Sites prontos</small>
                </div>
            </div>
        </div>

        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>

        <!-- About Sílica -->
        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="">
                            <span class="tiny-bold title-q">Quem somos nós, e como lembrar de nós</span>
                            <span class="mt-2">Somos o Universo Sílica Líder de facturação, e podemos fornecer muito mais do que você pode imaginar. Explora alguns dos nossos ítens </span>
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="box">
                        <?php foreach ($about as $item) : ?>
                            <div class="box-item">
                                <span class="name">Sílica <?= $item[0] ?></span>
                                <small class="tw-muted">Lorem ipsum dolor sit amet.</small>
                                <a href="<?= $item[1] ?>" target="_blank"> <small>saber mais...</small> </a>
                            </div>
                        <?php endforeach; ?>
                        <a href="<?= 'https://silicaweb.ao/sfront/services.php' ?>" target="_blank" class="box-item">
                            <span class="name">Saiba mais...</span>
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- about -->

        <div class="sx-card-section"></div>

        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="">
                            <span class="mb-2"> <span class="bi bi-arrow-right"></span> Conheça as respostas das perguntas e dúvidas muito mais frequentes para esse tipo de negócio.</span>
                            <span class="tiny-bold title-q">Perguntas frequentes</span>
                            <span class="mt-0">Não continue enganado</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $asked = [
                ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="">contacto</a>', 'active'],
                ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', ''],
                ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', ''],
                ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', ''],
                ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '']
            ];
            ?>
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="asked-frequently d-flex">
                        <div class="contain">
                            <?php foreach ($asked as $value) : ?>
                                <div class="asked-item <?= $value[2] ?>">
                                    <div class="asked-top">
                                        <span><?= $value[0] ?></span>
                                    </div>
                                    <div class="asked-body"><?= nl2br($value[1]) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- faqs-->

        <div class="sx-card-section"></div>

        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="" style="text-align: center; width: 100%">
                            <span class="tiny-bold title-q">Adesão de pacote</span>
                            <span class="mt-0">Esse é um jeito mais facil pra começar com o nosso serviço</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="packages">
                        <div class="package-item">
                            <div class="package-top">
                                <span class="title">Free pack</span>
                                <small class="tw-muted">Pacote padrão</small>
                                <small class="tw-muted mt-1">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Fugiat, fuga.</small>
                            </div>
                            <div class="sx-card-section"></div>
                            <div class="line-divider-horizontal"></div>
                            <span class="bold">0,00KZ <small class="line-through">1.000,00KZ</small> </span>
                            <div class="package-includes">
                                <small class="bi bi-arrow-right">2 Templates no máximo</small>
                                <small class="bi bi-arrow-right">Validade de domínio por 30 dias</small>
                                <small class="bi bi-arrow-right">Sem suporte</small>
                            </div>
                            <a href="">Aderir <small class="bi bi-arrow-right"></small> </a>
                        </div>
                        <div class="package-item">
                            <div class="package-top">
                                <span class="title">Basic pack</span>
                                <small class="tw-muted">Pacote Inicial</small>
                                <small class="tw-muted mt-1">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Fugiat, fuga.</small>
                            </div>
                            <div class="sx-card-section"></div>
                            <div class="line-divider-horizontal"></div>
                            <span class="bold">1.000,00KZ <small class="line-through">1.900,00KZ</small> </span>
                            <div class="package-includes">
                                <small class="bi bi-arrow-right">3 Templates no máximo</small>
                                <small class="bi bi-arrow-right">Validade de domínio por 50 dias</small>
                                <small class="bi bi-arrow-right">Sem suporte</small>
                            </div>
                            <a href="">Aderir <small class="bi bi-arrow-right"></small> </a>
                        </div>
                        <div class="package-item">
                            <div class="package-top">
                                <span class="title">Big pack</span>
                                <small class="tw-muted">Personalizado</small>
                                <small class="tw-muted mt-1">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Fugiat</small>
                            </div>
                            <div class="sx-card-section"></div>
                            <div class="line-divider-horizontal"></div>
                            <span class="bold">5.000,00KZ <small class="line-through">7.500,00KZ</small> </span>
                            <div class="package-includes">
                                <small class="bi bi-arrow-right">5 Templates no máximo</small>
                                <small class="bi bi-arrow-right">Validade de domínio por 90 dias</small>
                                <small class="bi bi-arrow-right">Suporte online</small>
                            </div>
                            <a href="">Aderir <small class="bi bi-arrow-right"></small> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- planos -->


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
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317" integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA==" data-cf-beacon='{"rayId":"842ce7672b5f304a","version":"2023.10.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>