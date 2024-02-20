<nav class="navbar">
    <div class="container-sm w-100 w-md-80">
        <div class="row h-100">
            <div class="d-flex h-100 align-items-center col-6">
                <button type="button" class="navbar-menu"><span class="line"></span><span class="line"></span></button>
                <span class="navbar-title d-none d-md-block">Silica Page</span>
                <span class="navbar-title d-block d-md-none">SP</span>
            </div>
            <div class="d-flex align-items-center justify-content-end col-6">
                <a href="" class="link">
                    <span class="bi bi-bell-fill"></span>
                </a>
                <a href="" class="link bg-orange">
                    <span class="letter"> <?= ucfirst(Sienekib\Mehael\Support\Auth::user()->username[0]) ?> </span>
                </a>
            </div>
        </div>
    </div>
</nav> <!-- navbar -->

<?php 
    $user_id = \Sienekib\Mehael\Support\Auth::user()->id;  

    $path = explode('/', ltrim(request()->path(), '/'));
?>

<div class="sidebar-nav">
    <div class="d-flex align-items-center justify-content-space-between my-4 pr-3">
        <span class="d-block sidebar-nav-title text-muted">Sílica Page</span>
        <small class="sidebar-nav-close bi bi-arrow-right"></small>
    </div>

    <a href="<?= route('user', $user_id . '/home') ?>" class="link <?= end($path) == 'home' ? 'active' : '' ?>">
        <small class="fas fa-home"></small>
        <small class="link-text">Dashboard</small>
    </a>

    <a href="<?= route('user', $user_id . '/websites') ?>" class="link <?= end($path) == 'websites' ? 'active' : '' ?>">
        <small class="bi bi-grid-fill"></small>
        <small class="link-text">Meus websites</small>
    </a>

    <a href="<?= route('user', $user_id . '/encomendas') ?>" class="link <?= end($path) == 'encomendas' ? 'active' : '' ?>">
        <small class="bi bi-arrow-up"></small>
        <small class="link-text">Encomendar website</small>
    </a>

    <a href="<?= route('user', $user_id . '/campanhas') ?>" class="link <?= end($path) == 'campanhas' ? 'active' : '' ?> <?= end($path) == 'mail' ? 'active' : '' ?>">
        <small class="bi bi-megaphone-fill"></small>
        <small class="link-text">Campanhas</small>
        <small class="link-brand ml-auto">0</small>
    </a>

    <a href="" class="link mt-auto">
        <small class="bi bi-arrow-left"></small>
        <small class="link-text">Terminar sessão</small>
    </a>

</div> <!-- sidebar-nav -->

<?= parts('labs.loader') ?>
<?= parts('overlay') ?>
<?= parts('notificacao') ?>

<script>
    $('.navbar-menu').click((e) => { 
        $('body').css('overflow', 'hidden');
        $('.sidebar-nav').addClass('active'); 
    });
    $('.sidebar-nav-close').click((e) => { 
        $('.sidebar-nav').removeClass('active'); $('body').css('overflow', 'auto'); });
</script>