<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
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
        <?= parts('editing.wr-loading') ?>

        <span class="d-flex my-5"></span>
        <span class="d-flex mt-5"></span>

        <div class="card" id="fst-container">
            <div class="card mb-5">
                <div class="card-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <button class="btn" id="back-to-browse">
                                    <span class="bi bi-arrow-left" style="font-size: 2rem;"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span class="card-heading">O seu website</span>
                            <small class="text-muted d-block mb-4">Insere um nome para o seu website, pois é um factor indispensável, o que nos ajuda a enquadrar o seu negócio.</small>
                            <div class="input-group">
                                <input type="text" id="siteName" class="form-input" placeholder="seu dominio">
                            </div>
                            <div class="input-group mt-5">
                                <button class="btn btn-dark" id="call-next">Próximo <span class="bi bi-arrow-right"></span> </button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>

                        <div class="col-lg-6">
                            <span class="card-heading d-flex ai-center"><span class="bi bi-lightbulb"></span> Dicas</span>
                            <small class="text-muted d-block mb-4">Caso não consiga achar um nome para o seu website, tente seguir nomes inspiradores como é o caso de: </small>
                            <div class="d-flex ai-center">
                                <small class="d-block">
                                    <a href="https://silicaweb.ao/sfront/" class="text-black" target="_blank">silicaweb</a>
                                </small>

                                <small class="d-block ml-4">
                                    <a href="http://pagina.ao" class="text-black" target="_blank">pagina</a>
                                </small>

                                <small class="d-block ml-4">
                                    <a href="http://silicaerp.com" class="text-black" target="_blank">erp</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/. first -->

        <div class="card d-none" id="snd-container">
            <div class="card mb-5">
                <div class="card-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <button class="btn" id="back-to-first">
                                    <span class="bi bi-arrow-left" style="font-size: 2rem;"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span class="card-heading">Alvo</span>
                            <small class="text-muted d-block mb-4">Nos diz quem vai usar este website.</small>
                            <div class="input-group">
                                <select id="alvo" class="form-select">
                                    <option value="only-me">Apenas eu</option>
                                    <option value="litle">Uma pequena empresa</option>
                                    <option value="big">Uma grande empresa</option>
                                    <option value="other">Outro</option>
                                </select>
                            </div>
                            <div class="input-group flex-column">
                                <small class="text-muted d-block mt-4 mb-2">O tipo de website que desejas.</small>
                                <select id="type" class="form-select w-100">
                                    <?php foreach ($type as $tipo) : ?>
                                        <option value="<?= $tipo->tipo_template_id ?>"><?= $tipo->tipo_template ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input-group mt-5">
                                <button class="btn btn-dark" id="call-empty-editor">Próximo <span class="bi bi-arrow-right"></span> </button>
                            </div>
                        </div>
                        <div class="col-lg-1"></div>

                        <div class="col-lg-6">
                            <span class="card-heading d-flex ai-center"><span class="bi bi-marker-tip"></span></span>
                            <small class="text-muted d-block mb-4">Podes adequar o seu website ao tipo de negócio que quiser. e nós resolvemos tudo pra você.</small>
                            <div class="d-flex ai-center">
                                <small class="d-block">E-commerce</small>
                                <small class="d-block ml-3">Artes & Design</small>
                                <small class="d-block ml-3">Construção</small>
                                <small class="d-block ml-3">Restaurante</small>
                                <small class="d-block ml-3">Etc.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--/. first -->

    </div> <!-- /.wrapper -->

</body>

</html>
<input type="hidden" id="browse-route" value="<?= route('browse-get') ?>">
<script src="<?= asset('js/editing/index.js') ?>"></script>