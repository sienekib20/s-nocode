<nav class="__nav">
    <div class="sx-container d-flex">
        <div class="__nav-appname">
            <span class="bi bi-code-slash d-flex"></span>
        </div>
        <div class="__nav-items d-flex">
            <div class="__nav-item <?= request()->path() == '/' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Inicio</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/browse' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Browse</span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/user_template' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
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
            <div class="__nav-item ml-auto <?= request()->path() == '/compras' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span class="fas fa-shopping-cart"></span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/cadastrar' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span class="fas fa-user-plus"></span>
                </a>
            </div>
            <div class="__nav-item <?= request()->path() == '/entrar' ? 'active' : '' ?>">
                <a href="<?= route('/') ?>" class="__nav-link">
                    <span>Entrar</span>
                </a>
            </div>
        </div>
    </div>
</nav> <!-- __nav -->

<?= parts('nav.back-to-top') ?>


<script></script>