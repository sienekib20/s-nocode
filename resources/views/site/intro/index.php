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


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper-->


</body>

</html>