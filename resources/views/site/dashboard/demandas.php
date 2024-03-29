<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
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
        <?= parts('nav.wr-navbar') ?>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>

        <?php parts('nav.wr-sidebar') ?>

        <div class="card mb-3">
            <div class="card-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 card-top">
                            <div class="card-title">
                                <h3 class="card-heading text-black d-block">Encomede um website personalizado</h3>
                                <span>Tens essa possibilidade de encomendar um website para o teu negócio pessoal ou empresarial, e nós resolvemos pra você.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/.card-top-->
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12 ai-flex-start d-flex flex-wrap" style="gap: 10px;">
                            <?= parts('nav.wr-open-dashboard-menu') ?>
                            <a href="<?= route('browse') ?>" class="btn btn-outline-orange"> <span class="bi bi-plus"></span> Adquirir template existente</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <span class="card-heading text-black">Preencha as informações necessárias.</span>
                            <small class="text-muted d-block mb-4">Precisamos dessas informações para atender ao teu pedido.</small>
                            <form action="<?= route('send_demand') ?>" class="row" method="post" id="form-encomenda">
                                <div class="input-group col-md-6">
                                    <input type="text" name="sujeito" class="form-input input-orange" placeholder="Sujeito" id="Subject" required>
                                </div>
                                <div class="input-group col-md-6 mt-5 mt-md-0">
                                    <select name="alvo" class="form-select input-orange" required>
                                        <option value="">Quem vai usar?</option>
                                        <option value="Apenas eu">Apenas eu</option>
                                        <option value="A minha empresa">A minha empresa</option>
                                        <option value="Um número reduzido">Um número reduzido de pessoas</option>
                                    </select>
                                </div>
                                <div class="input-group col-md-3 mt-5">
                                    <select name="tipo_template" class="form-select input-orange" required>
                                        <option value="">Tipo de website</option>
                                        <?php foreach ($tipo as $t) : ?>
                                            <option value="<?= $t->tipo_template_id ?>"><?= $t->tipo_template ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="preco_tipo_atual-<?= $t->tipo_template_id ?>" value="<?= $t->preco ?>">
                                </div>
                                <div class="input-group col-md-3 mt-5">
                                    <input type="text" class="form-input input-orange" id="tipo-preco" placeholder="0.00KZ" readonly>
                                </div>

                                <div class="input-group col-md-3 mt-5">
                                    <select name="categoria_template" class="form-select input-orange" required>
                                        <option value="">Categoria website</option>
                                        <?php foreach ($categorias as $c) :  ?>
                                            <option value="<?= $c->categoria_id ?>"><?= $c->categoria ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="categoria-preco-<?= $c->categoria_id ?>" value="<?= $c->preco ?>">
                                </div>

                                <div class="input-group col-md-3 mt-5">
                                    <input type="text" class="form-input input-orange" id="preco-categoria" placeholder="0.00KZ" readonly>
                                </div>

                                <div class="input-group col-md-3 mt-5">
                                    <select name="tempo_estimado" class="form-select input-orange">
                                        <option value="">Tempo estimado</option>
                                        <option value="1 mês">Daqui há 1 mês</option>
                                        <option value="3 mêses">Daqui há 3 mês</option>
                                        <option value="6 mêses">Daqui há 6 mês</option>
                                        <option value="Indeterminado">Tempo indeterminado</option>
                                    </select>
                                </div>
                                <div class="input-group col-md-3 mt-5">
                                    <input type="text" name="manual_estimated" class="form-input input-orange" placeholder="Prefiro informar o tempo">
                                </div>
                                <div class="input-group col-md-6 mt-5">
                                    <select name="urgencia" class="form-select input-orange" required>
                                        <option value="">Nível de urgência</option>
                                        <?php foreach ($urgencias as $u) : ?>
                                            <option value="<?= $u->urgencia_id ?>"><?= $u->urgencia ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="input-group col-md-3 mt-5">
                                    <input type="text" class="form-input input-orange" placeholder="Preço total" disabled style="background-color: transparent; border: none">
                                </div>
                                <div class="input-group col-md-3 mt-5">
                                    <input type="text" name="total_price" class="form-input input-orange" id="total_price" placeholder="0.00KZ" readonly>
                                </div>
                                <div class="input-group col-md-6 mt-5">
                                    <textarea name="descricao" class="form-input input-orange" rows="" placeholder="Descrição" required></textarea>
                                </div>
                                <div class="input-group col-md-6 col-lg-3 mt-5">
                                    <button type="submit" class="btn btn-orange btn-block" id="_send_demand_">Enviar Solicitação</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script src="<?= asset('js/encomendas/index.js') ?>"></script>
<script>
    applyDarkNavbar();
</script>