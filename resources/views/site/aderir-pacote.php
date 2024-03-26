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

        <?php $payments = [
            ['multicaixa express', 'mxe.png'], ['transferencia bancária', 'trans.png'], ['Bai directo', 'baid.jpg'], ['Deposito bancário', 'depo.avif']
        ] ?>

        <div class="card">
            <div class="card-top mb-3 mt-4">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-12 text-center">
                            <span class="title d-block"> Diferentes formas de pagamentos</span>
                            <small class="text-muted">Faça o teu pagamento de um jeito mais fácil</small>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <div class="card-body">
                <div class="container-sm">
                    <div class="row align-items-center justify-content-center">
                        <?php foreach ($payments as $item) : ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-body mt-2">
                                        <div class="d-flex align-items-center">
                                            <div class="col-5">
                                                <img src="<?= asset('img/' . $item[1]) ?>" alt="">
                                            </div>
                                            <div class="col-7"><?= $item[0] ?></div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>
                    </div> <!--/.row-->
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <span>Para concluir o processo de transação entre logo em contacto para ter mais informações</span>
                            <span>Para mais sobre informações entre em <a href="" style="color: #f71">contacto</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.formas de pagamentos -->

        <div class="card">
            <div class="card-top op-0">
            </div>
            <div class="card-body mt-5">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card-title">
                                <span class="text-bold d-block" style="font-size: 22px !important;">Plano <?= $package->pacote ?> </span>
                                <small class="text-muted d-block" style="font-size: 18px !important; font-family: Roboto-Regular">Cria a tua lógica negócios, definindo as tuas próprias regras</small>
                            </div>
                            <small class="d-block mt-4"></small>
                            <div class="d-flex flex-direction-column">
                                <?php foreach ($descricao as $desc) : ?>
                                    <div class="d-flex align-items-center card-plan-item mt-3">
                                        <span class="bi bi-check"></span>
                                        <span><?= $desc ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <span class="text-orange text-bold d-block mt-3 mb-xxs-3 mb-lg-0"><?= $text ?></span>
                        </div> <!--/.col-md-7-->
                        <div class="col-md-1"></div>
                        <div class="card-plan col-md-4">
                            <div class="card-title" style="font-family: Roboto-Regular;">
                                <small class="">Preço oficial</small>
                                <span class="title text-bold d-block">Por apenas 0.00KZ</span>
                            </div>

                            <form action="<?= route('adesao') ?>" method="POST">
                                <input type="hidden" name="current_user_id" value="<?= \Sienekib\Mehael\Support\Auth::user()->id ?>">
                                <input type="hidden" name="plano_id" value="<?= $current_plane_id[0]['id'] ?>">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <small class="input-label">Carregar comprovativo *</small>
                                            <div class="content-input">
                                                <input type="file" class="form-input input-block" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <small class="input-label">Nº Referencia *</small>
                                            <div class="content-input">
                                                <input type="text" class="form-input input-block" placeholder="Referência da transação" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <small class="input-label">Email de verificação *</small>
                                            <div class="content-input">
                                                <input type="text" class="form-input input-block" placeholder="E-mail" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-orange input-block">Validar</button>
                                    </div>
                                </div>
                            </form>
                        </div><!--/.col-md-5-->
                    </div>
                </div>
            </div>
        </div> <!--/.card-->

        <small class="d-block my-5"></small>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title text-center col-12">
                            <span class="d-block bold">Planos que podem te interessar</span>
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
                                        <a href="<?= route('aderir', $planos['id']) ?>" class="btn btn-outline-orange w-20 input-block my-3 d-block btn-rounded"> <span class="bi bi-chevron-right"></span> </a>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php $xx++;
                        endforeach; ?>
                    </div>
                </div>
            </div>
        </div> <!--/.card-->


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