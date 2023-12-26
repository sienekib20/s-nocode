<nav class="ncode-navbar">
  <div class="ncode-container">
    <div class="ncode-app">
      <small class="bi-code-slash"></small>
      <small>nocode</small>
    </div>
    <div class="ncode-toggle">
      <div class="ncode-toggle-button">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
      </div>
    </div>
    <div class="ncode-nav-items">
      <div class="nav-item <?= request()->path() == '/' ? 'active' : '' ?> <?= request()->path() == '/nocode' ? 'active' : '' ?>">
        <a href="/" class="nav-link">
          <small>Inicio</small>
        </a>
      </div>
      <div class="nav-item <?= request()->path() == '/templates' ? 'active' : '' ?>">
        <a href="<?= route('templates') ?>" class="nav-link">
          <small>Explorar</small>
        </a>
      </div>
      <div class="nav-item">
        <a href="<?= route('templates') ?>" class="nav-link">
          <small>Planos</small>
        </a>
      </div>
      <div class="nav-item">
        <a href="<?= route('templates') ?>" class="nav-link">
          <small>Sobre nós</small>
        </a>
      </div>
      <div class="nav-item login">
        <a href="<?= route('login') ?>" class="nav-link">
          <small>Entrar</small>
        </a>
      </div>
      <div class="nav-item">
        <a href="<?= route('login') ?>" class="nav-link">
          <small>Criar conta</small>
        </a>
      </div>

      <div class="nav-item">
        <a href="<?= route('login') ?>" class="nav-link">
          <small>Meus templates</small>
        </a>
      </div>
      <div class="nav-item">
        <a href="<?= route('login') ?>" class="nav-link">
          <small class="bi bi-cart"></small>
        </a>
      </div>
    </div>
  </div>
</nav>


<script>
  function is_empty(field) {
    var data = (isArray(field)) ? field : [field];

    return data;

    if (field.value.length == 0) {
      return true;
    }
    return field.value;
  }
</script>