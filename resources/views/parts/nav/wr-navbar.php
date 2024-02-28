<nav class="wr-navbar">
    <div class="container-sm">
        <div class="wr-navbar-title">
            <span>WebCreator.<span class="colored">SP</span> </span>
        </div>
        <div class="wr-navbar-items">
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link <?= request()->path() == '/' ? 'active' : '' ?>">
                    <p>Início</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Softwares</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Templates</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Planos</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Faqs</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('contactos') ?>" class="wr-navbar-link <?= request()->path() == '/contactos' ? 'active' : '' ?>">
                    <p>Contactos</p>
                </a>
            </div>
            <div class="wr-navbar-item ml-auto">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Entrar</p>
                </a>
            </div>
        </div>
    </div>

</nav>


<script>
    $(window).on('scroll', (e) => {
        if (window.scrollY > 300) {
            $('.wr-navbar').addClass('dark');
        } else {
            $('.wr-navbar').removeClass('dark');
        }
    });
</script>