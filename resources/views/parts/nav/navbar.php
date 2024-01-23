<nav class="navbar d-xxs-none d-lg-block">
  <div class="container-sm px-5">
    <div class="row h-100">
      <div class="navbar-brand col-lg-2 col-xs-12 d-flex align-items-center justify-content-space-between">
        <a href="#" class="nav-app w-100 d-flex"> <span class="bi bi-code-slash"></span> nocode</a>
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

        <?php if (\Sienekib\Mehael\Support\Auth::check()) : $id = \Sienekib\Mehael\Support\Auth::user()->id ?>
          <div class="__nav-item <?= $path == 'dados' ? 'active' : '' ?>">
            <a href="<?= route('dados', $id) ?>" class="__nav-link">
              <span>Meus dados</span>
            </a>
          </div>
        <?php endif; ?>
        <div class="__nav-item <?= request()->path() == '/planos' ? 'active' : '' ?>">
          <a href="<?= route('planos') ?>" class="__nav-link">
            <span>Pacotes</span>
          </a>
        </div>
        <div class="__nav-item <?= request()->path() == '/encomendar' ? 'active' : '' ?>">
          <a href="<?= route('/') ?>" class="__nav-link">
            <span>Encomendar</span>
          </a>
        </div>

        <?php if (!\Sienekib\Mehael\Support\Auth::check()) : ?>
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
          <div class="__nav-item <?= request()->path() == '/logout' ? 'active' : '' ?>">
            <a href="{{ route('logout') }}" class="__nav-link">
              <span>Sair</span>
            </a>
          </div>
        <?php endif; ?>
      </div> <!--/.navbar-items-->
    </div>
  </div>
</nav>

<div class="mobileMenu d-xxs-block d-lg-none">
  <div class="mobileMenu-top">
    <div class="container-sm">
      <a href="#" class="nav-app w-100 d-flex"> <span class="bi bi-code-slash"></span> nocode</a>
      <div class="openMenu">
        <span class="lineMenu"></span>
        <span class="lineMenu"></span>
      </div>
    </div>
  </div>
  <div class="mobileMenu-contain">
    <div class="mmItem closeMenu"> <span class="bi bi-arrow-left">Voltar</span> </div>
    <div class="mmItem {{ request()->path() == '/' ? 'active' : '' }}">
      <a href="{{ route('/') }}" class="mmLink">Inicio</a>
    </div>
    <div class="mmItem {{ request()->path() == '/browse' ? 'active' : '' }}">
      <a href="{{ route('browse') }}" class="mmLink">Browse</a>
    </div>
    <div class="mmItem {{ request()->path() == '/planos' ? 'active' : '' }}">
      <a href="{{ route('planos') }}" class="mmLink">Pacotes</a>
    </div>
    <div class="mmItem {{ request()->path() == '/encomendas' ? 'active' : '' }}">
      <a href="{{ route('demand') }}" class="mmLink">Encomendar <small class="count">0</small> </a>
    </div>
    <?php if (\Sienekib\Mehael\Support\Auth::check()) : ?>
      <?php if (str_contains($path = request()->path(), 'dados')) $path = explode('/', ltrim($path, '/'))[0]; ?>
      <div class="mmItem <?= $path == 'dados' ? 'active' : '' ?>">
        <a href="{{ route('dados', \Sienekib\Mehael\Support\Auth::user()->id ) }}" class="mmLink">Meus dados</a>
      </div>
      <div class="mmItem {{ request()->path() == '/buy' ? 'active' : '' }}">
        <a href="{{ route('buy') }}" class="mmLink"> <span class="bi bi-cart">Carrinho</span> </a>
      </div>
      <div class="mmItem">
        <a href="{{ route('') }}" class="mmLink">Termos de uso</a>
      </div>
      <div class="mmItem">
        <a href="{{ route('logout') }}" class="mmLink">Sair</a>
      </div>
    <?php else : ?>
      <div class="mmItem">
        <a href="{{ route('login') }}" class="mmLink">Entrar</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<small class="d-xxs-block d-lg-none my-4"></small>
<small class="d-xxs-block d-lg-none my-3"></small>


<!-- @parts('nav.back-to-top')-->
<?= parts('labs.loader') ?>

<script>
  $('.__harmbuger').click((e) => {
    $('.__nav-items').toggleClass('active');
  });
  $('.openMenu').click((e) => {
    $('.mobileMenu-contain').css('right', '0');
    $('body').css('overflow', 'hidden');
  });
  $('.closeMenu').click((e) => {
    $('.mobileMenu-contain').css('right', '-100%');
    $('body').css('overflow', 'auto');
  });
</script>