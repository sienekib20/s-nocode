<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/nav/nav.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/grid-system.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/popper.js') ?>"></script>

</head>

<body>
<div class="sx">
    <?= parts('nav.navbar') ?>

    <div class="sx-card-section"></div>

    <div class="sx-card-section">
        <div class="sx-card-section-header">
            <div class="sx-container">
                <div class="title-as-horizontal-qr d-flex">
                    <div class="sx-col">
                        <span class="tiny-bold title-q">Meus dados</span>
                        <span class="mt-0">Está listados todos os dados que tem na nossa plataforma, isto é, <br> os teus pacotes bem como os templates que você adqueriu na plataforma</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sx-card-section"></div>

    <div class="sx-card-section-contain">
        <div class="sx-container">
            <div class="records d-flex">
                <div class="record-item">
                    <span class="qtd"><?= $templateUsuario->total ?></span>
                    <small class="tw-muted">Template</small>
                </div>
                <span></span>
                <div class="record-item">
                    <span class="qtd">0</span>
                    <small class="tw-muted">Pacote aderido</small>
                </div>
                <span></span>
                <div class="record-item">
                    <span class="qtd">0</span>
                    <small class="tw-muted">Encomenda</small>
                </div>
            </div>
        </div>
    </div> <!-- overview -->

    <div class="sx-card-section"></div>
    <div class="sx-card-section"></div>

    <div class="sx-card-section">
        <div class="sx-card-section-contain">
            <div class="sx-container">
                <div class="col-md-12 col-sm-12">
                    <table class="table table-responsive-md table-responsive-sm">
                        <thead class="thead-light">
                        <tr class="text-center">
                            <th class="col">Nº</th>
                            <th class="col">Template</th>
                            <th class="col">Categoria</th>
                            <th class="col">Status</th>
                            <th class="col">Dominio</th>
                            <th class="col">Prazo</th>
                            <th class="col">Criaçao</th>
                            <th class="col">Acção</th>
                        </tr>
                        </thead>
                        <tbody name="aulas_table">
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
                                    <td class="col"><div>
                                        <a href="">editar</a>
                                        <a href="">excluir</a>
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
    </div>

    <div class="sx-card-section"></div>
    <div class="sx-card-section"></div>
    <div class="sx-card-section"></div>


    <?= parts('nav.footer') ?>

</div> <!-- sx-wrapper -->


</body>

</html>