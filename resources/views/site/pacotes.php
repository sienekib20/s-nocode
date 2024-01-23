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

<body>

    <div class="wrapper">
        <?= parts('nav.navbar') ?>

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
            </div> <!--/.card-top-->
            <div class="card-body mt-5">
                <div class="container-sm">
                    <div class="row align-items-start justify-content-center">
                        <?php foreach ($enviar as $planos) :  ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <span class="title d-block"><?= $planos['pacote'] ?></span>
                                        <div class="d-flex align-items-baseline"> <small>Preço oficial</small> <span class="d-block">0,00KZ</span></div>
                                    </div>
                                    <div class="card-plan-body">
                                        <div class="d-flex flex-direction-column">
                                            <?php foreach ($planos['desc'] as $plane) : ?>
                                                <div class="d-flex align-items-center card-plan-item">
                                                    <small class="bi bi-check"></small>
                                                    <small class="text-muted"><?= $plane ?></small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if ($planos['pacote'] == 'Básico') : ?>
                                            <a href="<?= route('aderir', $planos['id']) ?>" class="btn btn-orange input-block my-3 d-block">Aderir</a>
                                        <?php else : ?>
                                            <a href="<?= route('aderir', $planos['id']) ?>" class="btn btn-outline-orange input-block my-3 d-block">Aderir</a>
                                        <?php endif; ?>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div> <!--/.card-->

        <?php
        $asked = [
            ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="">contacto</a>', 'active'],
            ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', ''],
            ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', ''],
            ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', ''],
            ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '']
        ];
        ?>

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

        <small class="d-block my-5"></small>

        @parts('nav.footer')
    </div> <!--/.wrapper-->
</body>

</html>

<script>
    $('.faqItem-top').click((e) => {
        e.preventDefault();
        $(e.target).toggleClass('active');
    });
</script>