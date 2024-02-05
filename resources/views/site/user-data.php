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
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/popper.js') ?>"></script>

</head>

<body>
    <div class="wrapper">
        <?= parts('nav.navbar') ?>

        <small class="d-block mt-3"></small>

        <div class="container-sm mt-3 mb-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-3">
                    <div class="card-plan mt-xxs-3">
                        <div class="card-plan-top">
                            <div class="d-flex align-items-baseline"> <small>Total de <?= $templateUsuario->total ?></small></div>
                        </div>
                        <div class="card-plan-body mt-2">
                            <div class="d-flex flex-direction-column">
                                <div class="d-flex align-items-center card-plan-item">
                                    <small class="bi bi-check"></small>
                                    <small class="text-muted">Templates</small>
                                </div>
                            </div>
                        </div>
                    </div> <!--/.card-plan-->
                </div> <!--/.col-md-3-->
                <div class="col-md-3">
                    <div class="card-plan mt-xxs-3">
                        <div class="card-plan-top">
                            <div class="d-flex align-items-baseline"> <small>Total de <?= $subscricoes ?></small></div>
                        </div>
                        <div class="card-plan-body mt-2">
                            <div class="d-flex flex-direction-column">
                                <div class="d-flex align-items-center card-plan-item">
                                    <small class="bi bi-check"></small>
                                    <small class="text-muted">Subscrição de pacotes</small>
                                </div>
                            </div>
                        </div>
                    </div> <!--/.card-plan-->
                </div> <!--/.col-md-3-->
                <div class="col-md-3">
                    <div class="card-plan mt-xxs-3">
                        <div class="card-plan-top">
                            <div class="d-flex align-items-baseline"> <small>Total de <?= $encomendas ?></small></div>
                        </div>
                        <div class="card-plan-body mt-2">
                            <div class="d-flex flex-direction-column">
                                <div class="d-flex align-items-center card-plan-item">
                                    <small class="bi bi-check"></small>
                                    <small class="text-muted">Encomendas</small>
                                </div>
                            </div>
                        </div>
                    </div> <!--/.card-plan-->
                </div> <!--/.col-md-3-->
            </div>
        </div>

        <small class="d-block my-3"></small>

        <div class="card">
            <div class="card-top">
                <div class="container-sm text-center">
                    <div class="row mb-5">
                        <div class="card-title col-12">
                            <span class="d-block bold">Os teus dados</span>
                            <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> o que tens na tua conta</small>
                        </div>
                    </div>

                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-2">
                            <select name="type" id="typeSearch" class="form-input">
                                <option value=""> Filtrar dados</option>
                                <option value="">Templates Recentes</option>
                                <option value="">Templates Vencidos</option>
                                <option value="">Pacotes aderidos</option>
                            </select>
                        </div> <!--/.col-md-2-->
                        <div class="col-md-3 pesquise_">
                            <input type="text" class="form-input input-block" placeholder="Pesquise">
                            <small class="bi bi-search"></small>
                        </div> <!--/.col-md-2-->
                    </div>
                </div>
            </div>
            <div class="card-body mt-5">
                <div class="container-sm">
                    <div class="row align-items-start justify-content-center">
                        <?php if (empty($data)) : ?>
                            <div class="col-12 text-center">
                                <span class="d-block my-4">Não tens nenhum template ainda</span>
                                <a href="<?= route('browse') ?>" class="btn btn-outline"><small class="text-muted">Adquirir agora</small></a>
                            </div>
                        <?php endif; ?>
                        <?php foreach ($data as $datum) : ?>
                            <div class="col-md-3">
                                <div class="card-plan mt-xxs-3">
                                    <div class="card-plan-top">
                                        <span class="title d-block"><?= $datum->titulo ?></span>
                                        <div class="d-flex align-items-baseline"> <small>Landing page</small></div>
                                    </div>
                                    <div class="card-plan-body">
                                        <div class="d-flex flex-direction-column">
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">http://localhost:8001/sn/<?= $datum->dominio ?></small>
                                            </div>
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Template <?= $datum->status ?></small>
                                            </div>
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Obtido a <?= $datum->preco . '.00 KZ' ?></small>
                                            </div>
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Valido por <?= $datum->prazo ?></small>
                                            </div>
                                            <div class="d-flex align-items-center card-plan-item">
                                                <small class="bi bi-check"></small>
                                                <small class="text-muted">Criado em <?= $datum->created_at ?></small>
                                            </div>
                                        </div>
                                        <div class="row d-flex my-3 ">
                                            <div class="col-3">
                                                <a href="{{ route('sn', $datum->dominio) }}" class="btn btn-outline-orange input-block" target="_blank">
                                                    <span class="bi bi-eye d-flex align-items-center justify-content-center" style="gap: 5px; font-size: 14px"></span>
                                                </a>
                                            </div>
                                            <div class="col-3">
                                                <a href="{{ route('edit', $datum->dominio, $datum->template_uuid) }}" class="btn btn-outline-orange d-flex w-100 h-100" target="_blank">
                                                    <span class="bi bi-pencil-square w-100 h-100 d-flex align-items-center justify-content-center" style="gap: 5px; font-size: 14px"></span>
                                                </a>
                                            </div>
                                            <div class="col-3">
                                                <a href="{{ route('aderir', 1) }}" class="btn btn-outline-orange input-block">
                                                    <span class="bi bi-arrow-up d-flex align-items-center justify-content-center" style="gap: 5px; font-size: 14px"></span>
                                                </a>
                                            </div> <!-- para publicar -->
                                            <div class="col-3">
                                                <a href="{{ route('aderir', 1) }}" class="btn btn-outline-orange input-block">
                                                    <span class="bi bi-trash d-flex align-items-center justify-content-center" style="gap: 5px; font-size: 14px"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--/.card-plan-->
                            </div> <!--/.col-md-3-->
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div> <!--/.card-->




        <small class="d-block my-5"></small>
        <small class="d-block my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper -->


</body>

</html>