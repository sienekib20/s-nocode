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
                            <span class="title d-block">Há cada tipo um preço correspondente</span>
                            <small class="text-muted">Escolha na medida do seu bolso</small>
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

        <small class="d-block mt-4"></small>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <span class="card-title d-block bold">Faça a tua incomenda</span>
                            <span class="text-muted">Informa a tua necessidade e nós resolvemos</span>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <div class="card-body mt-3">
                <div class="container-sm">
                    <form action="" method="POST" class="w-100">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="input-label">Email informativo</small>
                                    <div class="content-input">
                                        <input type="text" name="Sujeito" class="form-input input-block" placeholder="E-mail" required>
                                    </div>
                                </div> <!--/.form-group-->

                                <div class="form-group">
                                    <small class="input-label">Sujeito da encomenda</small>
                                    <div class="content-input">
                                        <input type="text" name="Sujeito" class="form-input input-block" placeholder="Sujeito" required>
                                    </div>
                                </div> <!--/.form-group-->

                                <small class="d-block my-3"></small>

                                <div class="form-group">
                                    <small class="input-label">Tipo de template</small>
                                    <div class="content-input">
                                        <select name="" id="" class="form-input input-block h-100">
                                            <option value="">-- Template na demanda --</option>
                                            <?php foreach ($tipo as $type) : ?>
                                                <option value="<?= $type->tipo_template_id ?>"><?= $type->tipo_template ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div> <!--/.form-group-->

                                <small class="d-block my-3"></small>

                                <div class="form-group order-md-last">
                                    <small class="input-label">Deixa a tua mensagem</small>
                                    <div class="content-input">
                                        <textarea name="" cols="30" rows="3" class="form-input input-block" placeholder="Exponha o que quer"></textarea>
                                    </div>
                                </div> <!--/.form-group-->
                            </div> <!--/.col-md-6-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <small class="input-label">Quem vai usar</small>
                                    <div class="content-input">
                                        <select name="" id="" class="form-input input-block h-100">
                                            <option value="">-- Seleciona --</option>
                                            <option value="">Apenas eu</option>
                                            <option value="">A minha empresa</option>
                                            <option value="">O team de trabalho</option>
                                            <option value="">Não decidir agora</option>
                                        </select>
                                    </div>
                                </div> <!--/.form-group-->

                                <small class="d-block my-3"></small>

                                <div class="form-group">
                                    <small class="input-label">Vai precisar até quando</small>
                                    <div class="content-input">
                                        <select name="" id="" class="form-input input-block h-100">
                                            <option value="">-- Seleciona --</option>
                                            <option value="">Dáqui há 2 Semanas</option>
                                            <option value="">Dáqui há 1 mês</option>
                                            <option value="">Dáqui há 2 mêses</option>
                                            <option value="">Dáqui há 3 mêses ou mais</option>
                                        </select>
                                    </div>
                                </div> <!--/.form-group-->

                                <small class="d-block my-3"></small>

                                <div class="form-group">
                                    <small class="input-label">Informa aqui o tempo caso não esteja listado acima</small>
                                    <div class="content-input">
                                        <input type="text" name="Sujeito" class="form-input input-block" placeholder="1 mês e 2 semanas" required>
                                    </div>
                                </div> <!--/.form-group-->

                                <small class="d-block my-3"></small>

                                <div class="form-group op-0" style="pointer-events: none">
                                    <button type="submit" class="btn btn-orange input-block">Enviar pedido</button>
                                </div> <!--/.form-group-->

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-orange input-block">Enviar pedido</button>
                                </div> <!--/.form-group-->
                            </div>
                        </div>
                    </form> <!--/.form-->
                </div>
            </div> <!--/.card-body-->
        </div>



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