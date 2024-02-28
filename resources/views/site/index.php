<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/faqs.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

    <div class="sx">
        <?= parts('nav.navbar') ?>
        <div class="wallpaper vh-85">
            <div class="container-sm" style="z-index: 10800">
                <div class="col-12">
                    <h1 class="w-100 d-block text-white">SÍLICA PAGE | CRIADOR DE WEBSITES</h1>
                    <span class="d-xxs-none d-md-block text-white">Crie o um espaço de trabalho personalizado</span>
                    <div class="col-12 pl-0">
                        <a href="<?= route('browse') ?>" class="btn btn-white w-80 w-lg-20 mt-2" style="font-size: 1.2rem; padding: 0.5rem 0.75rem;">Explorar</a>
                    </div>
                </div>
            </div>
        </div> <!-- wallpaper -->

        <?php $about = [
            ['School', 'https://silicaweb.ao/sfront/ServicesSchool.php', 'pencil'],
            ['ERP', 'https://silicaweb.ao/sfront/ServicesErp.php', 'collection'],
            ['Univ', 'https://silicaweb.ao/sfront/ServicesUniv.php', 'globe'],
            ['Aqua', 'https://silicaweb.ao/sfront/ServicesAqua.php', 'water'],
            ['Health', 'https://silicaweb.ao/sfront/ServicesHealth.php', 'heart'],/*
            ['RH', 'https://silicaweb.ao/sfront/ServicesRH.php'],
            ['Work', 'https://silicaweb.ao/sfront/ServicesWork.php']*/
        ]
        ?>

        <small class="d-block mt-5"></small>
        <small class="d-block mt-5"></small>

        <div class="card mb-5">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-12 card-top">
                            <div class="card-title text-center mb-5">
                                <h2 class="title d-block">Nossos Softwares</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
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
                        <div class="col-md-4 px-0">
                            <a href="https://silicaweb.ao/sfront/" target="_blank" class="software">
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




        <small class="d-block my-4"></small>



        <small class="d-block my-4"></small>
        <small class="d-block my-4"></small>

        <small class="d-block mt-5"></small>

        <div class="card"> <?= parts('faqs') ?> </div><!--/.card-->

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-12 card-top">
                            <div class="card-title text-center mb-5">
                                <h2 class="title d-block">Contactos</h2>
                                <span>Deixa aqui a tua mensagem</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container-sm w-100 w-md-80">
                    <form action="" class="row">
                        <div class="col-12">
                            <input type="text" class="form-input" placeholder="Seu nome">
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="col-md-6 my-3">
                            <input type="text" class="form-input" placeholder="Seu Email">
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="col-md-6 my-3">
                            <input type="text" class="form-input" placeholder="Seu Telefone">
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="col-12">
                            <textarea name="contact-sms" class="form-input" cols="30" rows="10" placeholder="Mensagem"></textarea>
                            <small class="invalid-feedback"></small>
                        </div>
                        <div class="col-md-3 mt-3">
                            <button type="submit" class="btn btn-orange w-100">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="d-flex my-5"></div>

        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script>
    $(document).ready(function() {
        const asked_top = document.querySelectorAll('.asked-top');
        asked_top.forEach((item) => {
            item.addEventListener('click', (e) => {
                e.target.parentNode.classList.toggle('active');
            })
        });
    });
    $('.faqItem-top').click((e) => {
        e.preventDefault();
        $(e.target).toggleClass('active');
    });
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317" integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA==" data-cf-beacon='{"rayId":"842ce7672b5f304a","version":"2023.10.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>