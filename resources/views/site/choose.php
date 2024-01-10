<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/nav/nav.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/grid-system.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
<div class="sx">
    <?= parts('nav.navbar') ?>
    <div class="sx-card-section"></div>

    <div class="sx-card-section">
        <form action="" method="POST" class="sx-card-section-contain">
            <div class="container d-flex in-use">
                <div class="title-as-horizontal-qr col-md-7 col-sm-7">
                    <div class="col-md-12 col-sm-12" style="text-align: left !important;">
                        <h4>Template <?= ucfirst($template->titulo) ?></h4>
                        <span class="mt-2"> <span class="bi bi-arrow-right"></span> Para tornar público o template a sua escolha, deves preencher os dados necessários abaixos</span>
                    </div>
                    <div class="sx-card-section"></div>
                    <div class="col-md-12 col-sm-12" style="text-align: left !important;">
                        <div class="domain-input">
                            <span class="prefix col-md-3 col-sm-3">nocode.com</span>
                            <input type="text" id="dominio" placeholder="Teu endereço" required
                                   class="col-md-9 col-sm-9">
                            <span class="spinner hide"></span>
                            <span class="bi bi-x hide"></span>
                            <span class="bi bi-check hide"></span>
                        </div>
                        <span class="mt-2">Este é o endereço pelo qual será accessado o teu site quando publicado, nota que já tem um prefixo por padrão.</span>
                        <small class="tw-muted">Dica: de preferência escolher um nome fácil. <i>exemplo: nocode</i>
                        </small>
                    </div>
                    <div class="sx-card-section"></div>
                    <div class="col-md-12 col-sm-12" style="text-align: left !important;">
                        <div class="domain-input">
                            <input type="text" id="mail" placeholder="Endereço email" style="width: 100%;">
                            <span class="spinner hide"></span>
                            <span class="bi bi-x hide"></span>
                            <span class="bi bi-check hide"></span>
                        </div>
                        <span class="mt-2">O email que inseriste acima, será usado para receber as notificações do público que quiser contactar você</span>

                        <span class="mt-2"></span></span>
                    </div>
                </div>


                <div class="template-in-use col-md-4 col-sm-4">
                    <div class="cover" style="height: 150px !important">
                        <img src="<?= asset('img/ncode-2.jpg') ?>" alt="">
                    </div>
                    <button type="submit" class="validar-uso">Publicar</button>
                    <a href="<?= route('editor', $template->uuid) ?>" target="_blank" class="choose-open-editor-btn">
                        <span class="fas fa-pencil-square"></span> <span>Editar</span>
                    </a>
                    <div class="in-use-item">
                        <small class="title">Preço</small>
                        <small class="value"><?= $template->preco . 'KZ' ?></small>
                    </div>
                    <div class="in-use-item">
                        <small class="title">Status</small>
                        <small class="value"><?= $template->status ?></small>
                    </div>
                    <div class="in-use-item">
                        <small class="title">Categoria</small>
                        <small class="value"><?= 'Landing Page' ?></small>
                    </div>
                    <div class="in-use-item">
                        <small class="title">Criado por</small>
                        <small class="value"><?= $template->autor ?></small>
                    </div>
                    <div class="in-use-item">
                        <small class="title">Pessoas usando</small>
                        <small class="value"><?= $template->quantidade ?></small>
                    </div>
                    <div class="in-use-item">
                        <small class="title">Classificação</small>
                        <small class="value"><?= 'Alta' ?></small> <!-- alta|normal|mais usado|etc. -->
                    </div>
                    <div class="sx-card-section"></div>

                </div>
            </div>
        </form>
    </div>

    <div class="sx-card-section"></div>
    <div class="sx-card-section"></div>
    <div class="sx-card-section"></div>


    <?= parts('nav.footer') ?>

</div> <!-- sx-wrapper -->
<div class="rotas" style="display: none">
    <input type="hidden" id="user_id" value="<?= 1 ?>">
    <input type="hidden" id="template" value="<?= $template->template ?>">
    <input type="hidden" id="template_titulo" value="<?= $template->titulo ?>">
    <input type="hidden" id="template_id" value="<?= $template->template_id ?>">
    <input type="hidden" id="rota-choose" value="<?= route('usar') ?>">
</div>


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>