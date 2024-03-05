<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="wrapper">
        <?= parts('nav.wr-navbar-alt') ?>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>

        <div class="card">
            <form action="" method="POST" class="sx-card-section-contain">
                <div class="container-sm in-use">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-7">
                            <div class="card-title col-12">
                                <h4 class="title d-block mb-1"><?= ucfirst($template->titulo ?? 'Default') ?> <?= $template->categoria ?> </h4>

                                <div class="d-flex mb-1" style="line-height: 1.5">
                                    <small>ID: <?= explode('-', $template->uuid)[0] ?></small>
                                    <small class="d-block mx-3">|</small>
                                    <small>Categoria: <?= $template->branch ?></small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div href="#" class="w-100 d-flex contain_choose">
                                    <a href="<?= route('live', $template->referencia) ?>" target="_blank" class="target_visible"></a>
                                    <img src="<?= "/html-templates/$template->capa" ?>" alt="">
                                </div>

                                <div class="card-title">
                                    <h4 class="title d-block mt-5 mb-3" style="font-size: 20px"><?= ucfirst($template->titulo ?? 'Default') ?> </h4>
                                    <small class="d-block mb-3" style="font-family: 'Roboto-Light'; line-height: 1.5"><?= $template->descricao ?></small>

                                    <div class="d-flex mb-1" style="line-height: 1.5">
                                        <small>Data de criação: <?= date('d-m-Y', strtotime(explode(' ', $template->created_at)[0])) ?></small>
                                        <small class="d-block mx-3">|</small>
                                        <small>Visto por: <?= '0' ?></small>
                                        <small class="d-block mx-3">|</small>
                                        <small>Aderido por: <?= '0' ?></small>
                                    </div>
                                </div>

                                <div class="row row-no-margin">
                                    <a href="<?= route('live', $template->referencia) ?>" target="_blank" class="btn btn-orange">Pre-visualizar</a>
                                    <a href="<?= "/html-templates/$template->capa" ?>" target="_blank" id="viewShot" name="<?= explode('-', $template->uuid)[0] ?>" target="_blank" class="btn btn-outline-orange">Ver Screenshot</a>
                                    <a href="" class="btn btn-outline-orange">Baixar</a>
                                </div>


                            </div> <!--/.col-12-->
                        </div> <!--/.col-md-7-->

                        <div class="template-in-use col-12 col-md-6 col-lg-4">
                            <span class="bold">Defina um nome para o teu site</span>
                            <small class="my-3 d-block text-muted" style="font-size: 14.5px;"> <span class="bi bi-arrow-right"></span> Informa um dóminio para o seu site</small>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" id="dominio" placeholder="seudominio.ao" required class="form-input input-block">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" id="mail" placeholder="Endereço email" class="form-input input-block mt-2">
                            </div>

                            <small class="d-block my-4"></small>
                            <div class="d-flex mt-2">
                                <div class="col-5 px-0">
                                    <a href="<?= route('editor', explode('-', $template->uuid)[0]) ?>" target="_blank" class="choose-open-editor-btn btn btn-outline-orange input-block d-block mt-1 mb-2">
                                        <span class="fas fa-pencil-square"></span> <span>Editar</span>
                                    </a>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-5 ml-auto px-0">
                                    <a href="#" target="_blank" class="choose-open-editor-btn btn btn-outline-orange input-block d-block mt-1 mb-2">
                                        <span class="fas fa-arrow-up"></span> <span>Publicar</span>
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Preço</span>
                                <span class="value"><?= $template->preco . '.00 KZ' ?? '0.00' . 'KZ' ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Status</span>
                                <span class="value"><?= $template->status ?? 'Grátis' ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Categoria</span>
                                <span class="value"><?= 'Landing Page' ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Criado por</span>
                                <span class="value"><?= $template->autor ?? \Sienekib\Mehael\Support\Auth::user()->username ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Pessoas usando</span>
                                <span class="value"><?= $template->quantidade ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-space-between py-2 in-use-item">
                                <span class="title">Classificação</span>
                                <span class="value"><?= 'Alta' ?></span> <!-- alta|normal|mais usado|etc. -->
                            </div>
                            <div class="sx-card-section"></div>

                            <div class="tools mt-4">
                                <span class="bold">Ferramentas de desenvolvimento</span>
                                <div class="d-flex mt-4">
                                    <div class="col-6 d-flex align-items-baseline px-0">
                                        <div class="item-cover">
                                            <img src="<?= asset('img/icons/wp.png') ?>" alt="">
                                        </div>
                                        <span class="d-flex ml-2">jQuery</span>
                                    </div>
                                    <div class="col-6 d-flex align-items-baseline px-0">
                                        <div class="item-cover">
                                            <img src="<?= asset('img/icons/html.png') ?>" alt="">
                                        </div>
                                        <span class="d-flex ml-2">HTML5</span>
                                    </div>
                                </div>
                                <div class="d-flex my-4">
                                    <div class="col-6 d-flex align-items-baseline px-0">
                                        <div class="item-cover">
                                            <img src="<?= asset('img/icons/css.png') ?>" alt="">
                                        </div>
                                        <span class="d-flex ml-2">CSS3</span>
                                    </div>
                                    <div class="col-6 d-flex align-items-baseline px-0">
                                        <div class="item-cover">
                                            <img src="<?= asset('img/icons/bs.png') ?>" alt="">
                                        </div>
                                        <span class="d-flex ml-2">Bootstrap</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col-6 d-flex align-items-baseline px-0">
                                        <div class="item-cover">
                                            <img src="<?= asset('img/icons/js.png') ?>" alt="">
                                        </div>
                                        <span class="d-flex ml-2">JavaScript</span>
                                    </div>
                                </div>
                            </div> <!--/tools-->

                            <div class="tools">
                                <span class="bold">Sílica Page Editor</span>
                            </div>

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
<script>
    /*document.getElementById('viewShot').addEventListener('click', function(event) {
        event.preventDefault(); // Evita que o comportamento padrão de redirecionamento da âncora seja acionado

        // Cria um novo objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();

        const formData = new FormData();
        formData.append('id', event.target.name);

        // Configura a função de tratamento de evento para a resposta
        xhr.open('POST', 'http://localhost:8000/shot', true);
        xhr.onload = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Abre uma nova janela e carrega a imagem
                var newWindow = window.open('about:blank', '_blank');
                if (newWindow) {
                    let ar = JSON.parse(xhr.responseText);
                    console.log(ar[0]);
                    newWindow.document.write('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Imagem</title></head><body><img src="http://localhost:8000/templates/defaults/' + ar.referencia + '/cover/'+ ar.capa 
                    + '" alt="Imagem"></body></html>');

                    console.log(newWindow);
                } else {
                    alert('O bloqueio de pop-up pode estar impedindo a abertura da nova janela.');
                }
            }
        };
        xhr.send(formData);
    });*/
</script>