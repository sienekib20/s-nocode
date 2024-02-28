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
        <?= parts('nav.header') ?>

        <?php $about = [
            ['School', 'https://silicaweb.ao/sfront/ServicesSchool.php', 'pencil', ''],
            ['ERP', 'https://silicaweb.ao/sfront/ServicesErp.php', 'collection', 'active'],
            ['Univ', 'https://silicaweb.ao/sfront/ServicesUniv.php', 'globe', ''],
            ['Aqua', 'https://silicaweb.ao/sfront/ServicesAqua.php', 'water', ''],
            ['Health', 'https://silicaweb.ao/sfront/ServicesHealth.php', 'heart', ''],/*
            ['RH', 'https://silicaweb.ao/sfront/ServicesRH.php'],
            ['Work', 'https://silicaweb.ao/sfront/ServicesWork.php']*/
        ]
        ?>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title text-center col-12">
                            <h4 class="title d-block mt-5">A nossa empresa</h4>
                            <span class="text-muted ff">Os nossos clientes já sabem quem somos, o que oferecemos de melhor pra eles. <br> <span class="bi bi-arrow-right"></span> Alguns dos nossos serviços. descubra mais sobre nós <a href="https://silicaweb.ao/sfront/" target="_blank">clique aqui</a></span>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <div class="card-body my-5">
                <div class="container-sm">
                    <div class="row">
                        <?php foreach ($about as $a) : ?>
                            <div class="col-md-4 px-0">
                                <a href="<?= $a[1] ?>" target="_blank" class="software">
                                    <span class="bi bi-<?= $a[2] ?>"></span>
                                    <span class="title">Sílica <?= $a[0] ?></span>
                                    <small class="text-muted">Lorem, ipsum, dolor sit amet consectetur adipisicing elit. Ipsum esse alias error quibusdam inventore tenetur!</small>
                                    <span class="bi bi-arrow-right"></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-lg-4 px-0">
                            <a href="https://silicaweb.ao/sfront/" target="_blank" class="software active">
                                <span class="bi bi-cog"></span>
                                <span class="title">Ver tudo</span>
                                <small class="text-muted">Explora mais sobre os nossos softwares</small>
                                <span class="bi bi-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?= parts('faqs.faq') ?>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-12 col-md-6">
                            <h4 class="title d-block mt-5">Fale connosco</h4>
                            <span class="text-muted ff">Entre em contato conosco para mais informações sobre nossos serviços de criação de sites.</span>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <small class="d-flex my-4"></small>
            <div class="card-body">
                <div class="container-sm">
                    <div class="row my-5">
                        <div class="col-12 col-md-4">
                            <div class="cus-item">
                                <span class="bi bi-chat-left-text"></span>
                                <div class="contain">
                                    <span class="title">Converse connosco</span>
                                    <small class="text-muted">O nosso team está aqui pra te ajudar</small>
                                    <small>cc@silicaweb.ao</small>
                                </div>
                            </div> <!--/.cus-item-->
                            <div class="cus-item">
                                <span class="bi bi-geo"></span>
                                <div class="contain">
                                    <span class="title">Visite-nos</span>
                                    <small class="text-muted">Venha dizer olá no nosso escritório</small>
                                    <small>Bº Azul - Zamba II. Ref. Memorial A.Neto Luanda-Angola</small>
                                </div>
                            </div> <!--/.cus-item-->
                            <div class="cus-item">
                                <span class="bi bi-telephone-outbound"></span>
                                <div class="contain">
                                    <span class="title">Ligue pra nós</span>
                                    <small class="text-muted">Seg. à Sexta, das 8h às 17h</small>
                                    <small>(+244) 948 109 778</small>
                                </div>
                            </div> <!--/.cus-item-->
                        </div> <!--/.col-->
                        <div class="col-12 col-md-1"></div>
                        <form action="" class="col-12 col-md-7 mt-5 mt-md-0">
                            <div class="input-group">
                                <input type="text" class="form-input" placeholder="Seu nome">
                            </div>
                            <div class="input-group my-3">
                                <input type="text" class="form-input" placeholder="Seu email">
                            </div>
                            <div class="input-group mb-3">
                                <textarea name="" class="form-input" cols="30" rows="4" placeholder="Nos fale da tua preocupação"></textarea>
                            </div>
                            <div class="input-group">
                                <button type="submit" class="btn btn-orange input-block">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper-->


</body>

</html>