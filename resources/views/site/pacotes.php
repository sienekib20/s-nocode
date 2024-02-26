<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%title%</title>
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/faqs.css') ?>">
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
                            <h2 class="d-block bold">Planos & preços</h2>
                            <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> Temos todo tipo de plano pra você</small>
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
                                        <div class="d-flex align-items-baseline"> <small>Preço oficial</small> <span class="d-block"><?= $planos['preco'] ?? '0.00 KZ'?></span></div>
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
                                            <a href="<?= route('aderir', $planos['id']) ?>" class="btn btn-orange input-block my-3 d-block" style="width: 120px !important">Aderir</a>
                                        <?php else : ?>
                                            <a href="<?= route('aderir', $planos['id']) ?>" class="btn btn-outline-orange input-block my-3 d-block" style="width: 120px !important">Aderir</a>
                                        <?php endif; ?>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div> <!--/.card-->

        <div class="card">
            <div class="card-top mb-3 mt-5">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-12 text-center">
                            <h2 class="title d-block">Preço de encomendas</h2>
                            <small class="text-muted"></small>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <div class="card-body">
                <div class="container-sm">
                    <div class="row align-items-center justify-content-center">
                        <?php foreach ($tipo as $t) : ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-body mt-2">
                                        <div class="row align-items-center">
                                            <div class="col-md-7 d-flex align-items-center card-plan-item">
                                                <small class="bi"></small>
                                                <small class="text-muted" style="font-size: 14px"><?= $t->tipo_template ?></small>
                                            </div>
                                            <div class="col-md-5" style="font-family: 'Roboto-Bold';font-size: 20px"><?= '0.00KZ' ?></div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div> <!--/.row-->
                    <div class="row mt-5">
                        <div class="col-md-12 d-flex align-items-center justify-content-center">
                            <span>Para mais sobre informações entre em <a href="" style="color: #f71">contacto</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.formas de pagamentos -->

        <small class="d-block mt-5"></small>
        <div class="card">
            <?= parts('faqs') ?>
        </div><!--/.card-->

        <small class="d-block my-5"></small>

        <?= parts('nav.footer') ?>
    </div> <!--/.wrapper-->
</body>

</html>

<script>
    $('.faqItem-top').click((e) => {
        e.preventDefault();
        $(e.target).toggleClass('active');
    });
</script>