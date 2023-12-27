<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">

</head>

<body>
    <div class="sx">
        <?= parts('nav.navbar') ?>

        <div class="wallpaper">
            <div class="sx-container">
                <div class="sx-col">
                    <span class="bold title">Torne-se independente <br> Crie a sua lógica de negócios</span>
                    <small class="td-muted">Conheça a nova ferramenta dos Sílica, um criador de landing pages sem necessidade de mexer no código, apenas com um click e já está!</small>
                    <div class="actions d-flex">
                        <a href="">Experimente</a>
                        <a href="">Explorar</a>
                    </div>
                </div>
            </div>
        </div> <!-- wallpaper -->

        <div class="sx-card-section-contain">
            <div class="sx-container">
                <div class="records d-flex">
                    <div class="record-item">
                        <span class="qtd">+4.5K</span>
                        <small class="tw-muted">Usuários aderiram</small>
                    </div>
                    <span></span>
                    <div class="record-item">
                        <span class="qtd">+1.5K</span>
                        <small class="tw-muted">Landing pages</small>
                    </div>
                    <span></span>
                    <div class="record-item">
                        <span class="qtd">+1K</span>
                        <small class="tw-muted">Dashboard</small>
                    </div>
                    <span></span>
                    <div class="record-item">
                        <span class="qtd">5K</span>
                        <small class="tw-muted">Sites prontos</small>
                    </div>
                </div>
            </div>
        </div> <!-- overview -->

        <div class="sx-card-section"></div>
        <div class="sx-card-section"></div>

        <!-- About Sílica -->
        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="sx-col">
                            <span class="tiny-bold title-q">Quem somos nós? <br> e como lembrar de nós</span>
                            <span class="mt-2">Somos o Universo Sílica Líder de facturação, e podemos fornecer muito mais do que você pode imaginar. Explora alguns dos nosso ítens </span>
                        </div>
                        <div class="sx-col">
                        </div>
                    </div>
                </div>
            </div>
            <?php $about = ['Acadêmica', 'RPL', 'Facturação', 'Água'] ?>
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="box">
                        <?php foreach ($about as $item) : ?>
                            <div class="box-item">
                                <span class="name">Sílica <?= $item ?></span>
                                <small class="tw-muted">Lorem ipsum dolor sit amet.</small>
                                <a href="https://silicaweb.ao//sfront" target="_blank"> <small>saber mais...</small> </a>
                            </div>
                        <?php endforeach; ?>
                        <a href="https://silicaweb.ao//sfront" target="_blank" class="box-item">
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
                        <div class="sx-col">
                            <span class="mb-2"> <span class="bi bi-arrow-right"></span> Conheça as respostas das perguntas e dúvidas muito mais frequentes <br> para esse tipo de negócio.</span>
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
                ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', 'active']
            ];
            ?>
            <div class="sx-card-section-contain">
                <div class="sx-container">
                    <div class="asked-frequently d-flex">
                        <div class="sx-col">
                            <?php $i = 0;
                            foreach ($asked as $value) : ?>
                                <div class="asked-item <?= $value[2] ?>">
                                    <div class="asked-top">
                                        <span><?= $value[0] ?></span>
                                    </div>
                                    <div class="asked-body">
                                        <?= nl2br($value[1]) ?>
                                    </div>
                                </div>
                            <?php $i++;
                                if ($i == 3) break;
                            endforeach; ?>
                        </div>
                        <div class="sx-col">
                            <?php foreach ($asked as $value) : ?>
                                <div class="asked-item <?= $asked[$i][2] ?>">
                                    <div class="asked-top">
                                        <span><?= $asked[$i][0] ?></span>
                                    </div>
                                    <div class="asked-body">
                                        <?= nl2br($asked[$i][1]) ?>
                                    </div>
                                </div>
                            <?php $i++;
                                if ($i == 5) break;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- faqs-->

        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="sx-col full-width">
                            <span class="tiny-bold title-q">Conheça o nosso centro de apoio ao cliente</span>
                            <span class="mb-2"> <span class="bi bi-warning"></span> Se tens alguma dúvida, cuja a pergunta não consta na lista acima, não se preocupe, temos outra maneira de responder você, entre em contacto connsco de imediato, assim obterá uma resposta a sua pergunta, por favor <a href="">clique aqui</a>.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- continuidade de faqs -->

        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="sx-col">
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
                                <small class="tw-muted mt-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, fuga.</small>
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
                                <span class="title">Big pack</span>
                                <small class="tw-muted">Personalizado</small>
                                <small class="tw-muted mt-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat</small>
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

        <div class="sx-card-section">
            <div class="sx-card-section-header">
                <div class="sx-container">
                    <div class="title-as-horizontal-qr d-flex">
                        <div class="sx-col">
                            <span class="tiny-bold title-q">Contactos</span>
                            <span class="mt-0">Ligue logo, que estamos 24h/24</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- contacts -->

        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper ->


    
</body>
</html>