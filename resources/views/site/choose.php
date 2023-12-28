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
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="sx">
        <?= parts('nav.navbar') ?>
        <div class="sx-card-section"></div>

        <div class="sx-card-section">
            <form action="" method="POST" class="sx-card-section-contain">
                <div class="sx-container d-flex in-use">
                    <div class="title-as-horizontal-qr col-7">
                        <div class="col-12">
                            <span class="tiny-bold title-q"><?= ucfirst($template->titulo) ?></span>
                            <span class="mt-2"> <span class="bi bi-arrow-right"></span> Para tornar público este template em seu posse, antes preencha os dados necessários abaixos apresentados</span>
                        </div>
                        <div class="sx-card-section"></div>
                        <div class="col-12">
                            <div class="domain-input">
                                <span class="prefix">nocode.com</span>
                                <input type="text" id="dominio" placeholder="Teu endereço" required>
                                <span class="spinner hide"></span>
                                <span class="bi bi-x hide"></span>
                                <span class="bi bi-check hide"></span>
                            </div>
                            <span class="mt-2">Este é o endereço pelo qual será accessado o teu site quando publicado, nota que já tem um prefixo por padrão.</span>
                            <small class="tw-muted">Dica: de preferência escolher um nome fácil. <i>exemplo: nocode</i> </small>
                        </div>
                        <div class="sx-card-section"></div>
                        <div class="col-12">
                            <div class="domain-input">
                                <input type="text" id="mail" placeholder="Endereço email" style="width: 100%;">
                                <span class="spinner hide"></span>
                                <span class="bi bi-x hide"></span>
                                <span class="bi bi-check hide"></span>
                            </div>
                            <span class="mt-2">O email que inseriste acima, será usado para receber as notificações do público que quiser contactar você</span>
                        </div>
                    </div>

                    <div class="template-in-use col-4">
                        <div class="cover">
                            <img src="<?= asset('img/ncode-2.jpg') ?>" alt="">
                        </div>
                        <div class="in-use-item">
                            <span class="title">Autor</span>
                            <span class="value"><?= $template->autor ?></span>
                        </div>
                        <div class="in-use-item">
                            <span class="title">Pessoas usando</span>
                            <span class="value"><?= $template->quantidade ?></span>
                        </div>
                        <div class="in-use-item">
                            <span class="title">Status</span>
                            <span class="value"><?= $template->status ?></span>
                        </div>
                        <div class="in-use-item">
                            <span class="title">Classificação</span>
                            <span class="value"><?= 'Alta' ?></span> <!-- alta|normal|mais usado|etc. -->
                        </div>
                        <div class="in-use-item">
                            <span class="title">Preço</span>
                            <span class="value"><?= $template->preco . 'KZ' ?></span>
                        </div>
                        <div class="sx-card-section"></div>
                        <button type="submit" class="validar-uso">Publicar</button>
                        <a href="#">Previsualizar</a>
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