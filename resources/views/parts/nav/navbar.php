<div class="labs-container">

  <div class="loader hide">
    <span class="spinner"></span>
  </div>

  <nav class="labs-navigation">
    <div class="labs-app">
      <span class="bi-code-slash"></span>
      <div class="bold">
        <span>no</span>
        <span class="colored">code</span>
      </div>
    </div>

    <div class="labs-contain-nav-items">
      <div class="labs-nav-item <?= request()->path() == '' ? 'active' : '' ?>">
        <a href="/" class="item-link">
          <small> Inicio </small>
        </a>
      </div>
      <div class="labs-nav-item">
        <a href="" class="item-link">
          <small> Vatangens </small>
        </a>
      </div>
      <div class="labs-nav-item">
        <a href="" class="item-link">
          <small> Planos </small>
        </a>
      </div>
      <div class="labs-nav-item <?= request()->path() == 'explorar' ? 'active' : '' ?>">
        <a href="/explorar" class="item-link">
          <small> Explorar </small>
        </a>
      </div>
      <div class="labs-nav-item has-login">
        <a href="" class="item-link">
          <small> Entrar </small>
        </a>
      </div>
      <div class="labs-nav-item">
        <a href="" class="item-link">
          <small> Obter Página </small>
        </a>
      </div>
    </div>

  </nav> <!--/.g-navbar-->
</div>


<script>

  document.body.style.overflow = 'hidden';

  setTimeout(() => {

    document.body.style.overflow = 'auto';
    document.querySelector('.loader').classList.add('hide');

  }, 100);

  try {

  } catch (e) {
    console.error('error');
  }



</script>
<script src="/assets/js/jquery-3.3.1.min.js"></script>