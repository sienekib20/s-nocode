<nav class="navbar">
    <div class="container-sm px-5">
        <div class="row h-100">            
            <div class="navbar-brand col-lg-2 col-xs-12 d-flex align-items-center justify-content-space-between">
                <a href="#" class="nav-app w-100 d-flex">nocode</a>
                <button type="button" class="navbar-toggler d-lg-none">
                    <span class="line"></span>
                    <span class="line"></span>
                </button>
            </div>
            <div class="navbar-items h-100 justify-content-end d-xxs-none d-lg-flex col-lg-10">
                <div class="__nav-item <?= request()->path() == '/' ? 'active' : '' ?>">
                    <a href="<?= route('') ?>" class="__nav-link">
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="__nav-item <?= request()->path() == '/browse' ? 'active' : '' ?>">
                    <a href="<?= route('browse') ?>" class="__nav-link">
                        <span>Browse</span>
                    </a>
                </div>

                <?php if (str_contains($path = request()->path(), 'dados')) $path = explode('/', ltrim($path, '/'))[0] ?>


                <div class="__nav-item <?= $path == 'dados' ? 'active' : '' ?>">
                    <a href="<?= route('dados', 'conta') ?>" class="__nav-link">
                        <span>Meus dados</span>
                    </a>
                </div>
                <div class="__nav-item <?= request()->path() == '/planos' ? 'active' : '' ?>">
                    <a href="<?= route('/') ?>" class="__nav-link">
                        <span>Pacotes</span>
                    </a>
                </div>
                <div class="__nav-item <?= request()->path() == '/encomendar' ? 'active' : '' ?>">
                    <a href="<?= route('/') ?>" class="__nav-link">
                        <span>Encomendar</span>
                    </a>
                </div>

                <?php if (!session()->get('user_id')) : ?>
                    <div class="__nav-item <?= request()->path() == '/cadastrar' ? 'active' : '' ?>">
                        <a href="<?= route('cadastrar') ?>" class="__nav-link">
                            <span class="fas fa-user-plus"></span>
                        </a>
                    </div>
                    <div class="__nav-item">
                        <a href="<?= route('entrar') ?>" class="__nav-link">
                            <span>Entrar</span>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="__nav-item <?= request()->path() == '/compras' ? 'active' : '' ?>">
                        <a href="<?= route('/') ?>" class="__nav-link">
                            <span class="fas fa-shopping-cart"></span>
                        </a>
                    </div>
                    <div class="__nav-item <?= request()->path() == '/compras' ? 'active' : '' ?>">
                        <a href="#" class="__nav-link">
                            <span>Sair</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div> <!--/.navbar-items-->
        </div>
    </div>
</nav>


<!--<nav class="__nav">
    <div class="sx-container d-flex">
        <div class="__nav-appname">
            <div class="bi d-flex">
                <span>NO</span>
                <span>CODE</span>
            </div>
        </div>
        <div class="__harmbuger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
        <div class="__nav-items d-flex">
            <div class="__nav-item <?= request()->path() == '/' ? 'active' : '' ?>">
                <a href="<?= route('') ?>" class="__nav-link">
                    <span>Inicio</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/browse' ? 'active' : '' ?>">
                <a href="<?= route('browse') ?>" class="__nav-link">
                    <span>Browse</span>
                </a>
            </div>

            <?php if (str_contains($path = request()->path(), 'dados')) $path = explode('/', ltrim($path, '/'))[0] ?>


            <div class="__nav-item <?= $path == 'dados' ? 'active' : '' ?>">
                <a href="<?= route('dados', 'conta') ?>" class="__nav-link">
                    <span>Meus dados</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/planos' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Pacotes</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/encomendar' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Encomendar</span>
                </a>
            </div>

            <?php if (!session()->get('user_id')) : ?>
                <div class="__nav-item <?= request()->path() == '/cadastrar' ? 'active' : '' ?>">
                    <a href="<?= route('cadastrar') ?>" class="__nav-link">
                        <span class="fas fa-user-plus"></span>
                    </a>
                </div>
                <div class="__nav-item">
                    <a href="<?= route('entrar') ?>" class="__nav-link">
                        <span>Entrar</span>
                    </a>
                </div>
            <?php else : ?>
                <div class="__nav-item <?= request()->path() == '/compras' ? 'active' : '' ?>">
                    <a href="<?= route('/') ?>" class="__nav-link">
                        <span class="fas fa-shopping-cart"></span>
                    </a>
                </div>
                <div class="__nav-item <?= request()->path() == '/compras' ? 'active' : '' ?>">
                    <a href="#" class="__nav-link">
                        <span>Sair</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="hamburger">
            <spna class="line"></spna>
            <spna class="line"></spna>
            <spna class="line"></spna>
        </div>
    </div>
</nav> <!-- __nav -->

<?= parts('nav.back-to-top') ?>
<?= parts('labs.loader') ?>

<script>
    $('.__harmbuger').click((e) => {
        $('.__nav-items').toggleClass('active');
    });
</script>