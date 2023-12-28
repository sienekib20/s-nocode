<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/grid-system.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">

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
                            <span class="mt-0">Está listados todos os dados que tem na nossa plataforma, isto é, os teus pacotes bem como os templates que você adqueriu na plataforma</span>
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
                        <span class="qtd">0</span>
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
                    <div class="table">
                        <div class="thead">
                            <div class="tr">
                                <span class="td col-1">Nº</span>
                                <span class="td col-3">Titulo</span>
                                <span class="td col-2">Categoria</span>
                                <span class="td col-1">Status</span>
                                <span class="td col-1">Preço</span>
                                <span class="td col-1">Prazo</span>
                                <span class="td col-1">Criaçao</span>
                                <span class="td col-2">Acção</span>
                            </div>
                        </div>
                        <div class="tbody">
                            <?php if (empty($data)) : ?>
                                <div class="tr">
                                    <span class="td col-12">Sem nenhum dados</span>
                                </div>
                            <?php else : ?>
                                <?php foreach ($data as $datum) : ?>
                                    <div class="tr">
                                        <span class="td col-1"><?= $datum->temp_parceiro_id ?></span>
                                        <span class="td col-3"><?= $datum->titulo ?></span>
                                        <span class="td col-2"><?= '' ?></span>
                                        <span class="td col-1"><?= 'Template' ?></span>
                                        <span class="td col-1"><?= $datum->preco ?></span>
                                        <span class="td col-1"><?= $datum->prazo ?></span>
                                        <span class="td col-1"><?= explode(' ', $datum->created_at)[0]; ?></span>
                                        <div class="td col-2">
                                            <a href="">editar</a>
                                            <a href="">excluir</a>
                                            <a href="<?= route('') ?>">abrir</a>
                                        </div>
                                    </div>
                            <?php endforeach;
                            endif; ?>
                        </div>
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