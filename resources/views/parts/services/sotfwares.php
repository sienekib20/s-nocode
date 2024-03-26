<?php $about = [
    ['School', 'https://silicaweb.ao/sfront/ServicesSchool.php', 'pencil', ''],
    ['ERP', 'https://silicaweb.ao/sfront/ServicesErp.php', 'collection', 'active'],
    ['Univ', 'https://silicaweb.ao/sfront/ServicesUniv.php', 'globe', ''],
    ['Aqua', 'https://silicaweb.ao/sfront/ServicesAqua.php', 'water', ''],
    ['Health', 'https://silicaweb.ao/sfront/ServicesHealth.php', 'heart', ''],
    ['RH', 'https://silicaweb.ao/sfront/ServicesRH.php', 'archive', ''],
    ['RP', 'https://silicaweb.ao/sfront/', 'clipboard2-pulse', ''],
    ['Work', 'https://silicaweb.ao/sfront/ServicesWork.php', 'pc-display', ''],
    ['Quem me levou', '#', 'car-front', ''],
    ['Express', '#', 'building-fill-exclamation', ''],
    ['Ver mais', 'https://silicaweb.ao/sfront', 'eye', '']
]
?>

<small class="d-flex my-4"></small>

<div class="card">
    <div class="card-top mb-4">
        <div class="container-sm">
            <div class="row">
                <div class="card-title col-12">
                    <h3 class="card-heading text-black d-block mt-5">Nossos softwares</h3>
                    <small class="ff">Os nossos clientes já sabem quem somos, o que oferecemos de melhor pra eles. <br> <span class="bi bi-arrow-right"></span> Alguns dos nossos serviços. descubra mais sobre nós <a href="https://silicaweb.ao/sfront/" target="_blank">clique aqui</a></small>
                </div>
            </div>
        </div>
    </div> <!--/.card-top-->

    <div class="card-body">
        <div class="container-sm">
            <div class="row">
                <?php foreach ($about as $a) : ?>
                    <div class="col-sm-4 col-md-4 col-lg-2">
                        <a href="<?= $a[1] ?>" class="service">
                            <span class="bi bi-<?= $a[2] ?>"></span>
                            <small class="text-muted"><?= $a[0] ?></small>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<small class="d-flex my-4"></small>