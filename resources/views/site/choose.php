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
</head>

<body>
    <div class="wrapper">
        <?= parts('nav.navbar') ?>

        <small class="d-block my-4"></small>

        <div class="card">
            <form action="" method="POST" class="sx-card-section-contain">
                <div class="container-sm in-use">
                    <div class="row">
                        <div class="col-xxs-12 col-sm-7 col-md-7">
                            <div class="col-12" style="text-align: left !important;">
                                <span class="bold d-block mb-3"><?= ucfirst($template->titulo ?? 'Default') ?></span>

                                <div class="contain-img">
                                    <img src="<?= asset('img/ncode-2.jpg') ?>" alt="Imagem do template selecionado">
                                    <a href="" class="preview"> <small class="bi bi-eye"></small> previsualizar </a>
                                </div>

                                <hr>

                                <div class="text-muted mt-3 mb-4">
                                    <small>Nota do desenvolvedor: Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus perspiciatis quidem quibusdam quia deserunt facilis placeat labore modi sapiente consectetur! Molestias a quisquam ex accusantium. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe, nostrum! Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat voluptates doloremque officia possimus, corporis aut natus, rerum itaque suscipit architecto rem dolorem placeat! Fugit, nulla? </small>
                                </div>

                                <small class="d-block my-4"></small>

                            </div> <!--/.col-12-->


                            <small class="d-block my-3"></small>

                            <small class="d-block my-3"></small>

                            <div class="col-12" style="text-align: left !important;">


                                <span class="mt-2"></span></span>

                                <hr>

                                <span class="my-4 d-block text-muted">Tecnologias usadas</span>
                                <div class="d-flex flex-direction-column">
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">HTML5</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">CSS3</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">Sass</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">Bootstrap5</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">Bootstrap icons</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">Font-Awesome 6</span>
                                    <span class="d-flex align-items-center mt-3 bi bi-dot">Etc.</span>
                                </div>

                            </div>
                        </div> <!--/.col-md-7-->
                        <div class="template-in-use col-xxs-12 col-md-4 col-sm-4">

                            <small class="my-3 d-block text-muted" style="font-size: 14.5px;"> <span class="bi bi-arrow-right"></span> Para tornar público o template a sua escolha, deves preencher os dados necessários abaixos</small>
                            <div class="row align-items-center">
                                <div class="col-xxs-3 col-md-3">
                                    <small class="btn btn-primary input-block w-100">sn.com</small>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <input type="text" id="dominio" placeholder="Teu endereço" required class="form-input input-block">
                                    </div>
                                </div>
                            </div>
                            <span class="my-4 d-block text-muted">Este é o endereço pelo qual será accessado o teu site quando publicado, nota que já tem um prefixo por padrão.</span>
                            <small class="tw-muted d-block" style="font-size: 14.5px;"> <span class="bi bi-arrow-right"></span> Dica: de preferência escolher um nome fácil. <i>exemplo: nocode</i>
                            </small>
                            <div class="form-group">
                                <input type="text" id="mail" placeholder="Endereço email" class="form-input input-block">
                            </div>
                            <span class="my-3 d-block"> <span class="bi bi-arrow-right"></span> O email que inseriste acima, será usado para receber as notificações do público que quiser contactar você</span>


                            <button type="submit" class="btn btn-primary input-block validar-uso">Publicar</button>
                            <a href="<?= route('editor', $template->uuid ?? 'default') ?>" target="_blank" class="choose-open-editor-btn btn btn-outline input-block d-block mt-1 mb-2">
                                <span class="fas fa-pencil-square"></span> <span>Editar</span>
                            </a>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Preço</small>
                                <small class="value"><?= $template->preco ?? '0.00' . 'KZ' ?></small>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Status</small>
                                <small class="value"><?= $template->status ?? 'Grátis' ?></small>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Categoria</small>
                                <small class="value"><?= 'Landing Page' ?></small>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Criado por</small>
                                <small class="value"><?= $template->autor ?? \Sienekib\Mehael\Support\Auth::user()->username ?></small>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Pessoas usando</small>
                                <small class="value"><?= $template->quantidade ?? '0' ?></small>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <small class="title">Classificação</small>
                                <small class="value"><?= 'Alta' ?></small> <!-- alta|normal|mais usado|etc. -->
                            </div>
                            <div class="sx-card-section"></div>

                        </div>
                    </div> <!--/.row-->


                </div>
            </form>
        </div>

        <small class="d-block my-2"></small>
        <small class="d-block my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->
    <div class="rotas" style="display: none">
        <input type="hidden" id="user_id" value="<?= 1 ?>">
        <input type="hidden" id="template" value="<?= $template->template ?? 'default' ?>">
        <input type="hidden" id="template_titulo" value="<?= $template->titulo ?? 'default' ?>">
        <input type="hidden" id="template_id" value="<?= $template->template_id ?? 'default' ?>">
        <input type="hidden" id="rota-choose" value="<?= route('usar') ?>">
    </div>


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>