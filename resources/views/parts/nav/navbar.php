<nav class="__nav">
    <div class="sx-container d-flex">
        <div class="__nav-appname">
            <span class="bi bi-code-slash d-flex"></span>
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
                    <span>Adquirir plano</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/encomendar' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Encomendar</span>
                </a>
            </div>
            <span class="ml-auto"></span>
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
                <div class="__nav-item ml-auto <?= request()->path() == '/compras' ? 'active' : '' ?>">
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
    </div>
</nav> <!-- __nav -->

<?= parts('nav.back-to-top') ?>
<?= parts('labs.loader') ?>

<script></script>