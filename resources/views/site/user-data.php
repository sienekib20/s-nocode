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

        <div class="sx-card-section"></div>

        <div class="container-sm my-4">
            <div class="row align-items-center justify-content-center">
                <div class="col-xxs-12 col-md-8 text-center">
                    <span class="d-block">Meus dados</span>
                    <small class="mt-2 d-block text-muted">Está listados todos os dados que tem na nossa plataforma, isto é, <br> os teus pacotes bem como os templates que você adqueriu na plataforma</small>
                </div>
            </div>
        </div>


        <div class="container-sm mt-3 mb-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-2 text-center">
                    <span class="qtd d-block"><?= $templateUsuario->total ?></span>
                    <small class="tw-muted">Template</small>
                </div>
                <span></span>
                <div class="col-md-2 text-center">
                    <span class="qtd d-block">0</span>
                    <small class="tw-muted">Pacote aderido</small>
                </div>
                <span></span>
                <div class="col-md-2 text-center">
                    <span class="qtd d-block">0</span>
                    <small class="tw-muted">Encomenda</small>
                </div>
            </div>
        </div>

        <small class="d-block my-3"></small>

        <div class="container-sm">
            <div class="row align-items-center justify-content-center">
                <div class="col-xxs-12 col-lg-10" style="overflow-x: auto;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Template</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Dominio</th>
                                <th>Prazo</th>
                                <th>Criação</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data)) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Sem nenhum dado</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($data as $datum) : ?>
                                    <tr>
                                        <td class="col"><?= $datum->temp_parceiro_id ?></td>
                                        <td class="col"><?= $datum->titulo ?></td>
                                        <td class="col"><?= '-' ?></td>
                                        <td class="col"><?= $datum->status ?></td>
                                        <td class="col"><?= '-' ?></td>
                                        <td class="col"><?= $datum->prazo ?></td>
                                        <td class="col"><?= explode(' ', $datum->created_at)[0] ?></td>
                                        <td class="col">
                                            <div>
                                                <a href="<?= route('editar', $datum->temp_parceiro_id) ?>">editar</a>
                                                <a href="<?= route('excluir', $datum->temp_parceiro_id) ?>">excluir</a>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <small class="d-block my-5"></small>
        <small class="d-block my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper -->


</body>

</html>