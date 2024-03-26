<?php use Sienekib\Mehael\Support\Auth; ?>
<?php 
    $user_id = \Sienekib\Mehael\Support\Auth::user()->id;  

    $path = explode('/', ltrim(request()->path(), '/'));
    $path = end($path);
?>
<aside class="ex-sidenav">
    <div class="sidenav-header">
        <span class="letter">Webcreator</span>
    </div>
    <div class="sidenav-contain">
        <span class="sidenav-title">Menu items</span>
        <a href="<?= route('user', $user_id . '/home') ?>" class="sidenav-link <?= $path == 'home' ? 'active' : '' ?>">
            <i class="bi bi-collection"></i>
            <p>Dashboard</p>
        </a>
        <a href="<?= route('user', $user_id . '/websites') ?>" class="sidenav-link <?= $path == 'websites' ? 'active' : '' ?>">
            <i class="bi bi-grid-fill"></i>
            <p>Meus websites</p>
        </a>
        <a href="<?= route('user', $user_id . '/encomendas') ?>" class="sidenav-link <?= $path == 'encomendas' ? 'active' : '' ?>">
            <i class="bi bi-upload"></i>
            <p>Encomendar websites</p>
        </a>

        <a href="<?= route('user', $user_id . '/campanhas') ?>" class="sidenav-link <?= $path == 'campanhas' ? 'active' : '' ?> <?= $path == 'mail' ? 'active' : '' ?>">
            <i class="bi bi-megaphone"></i>
            <p>Minhas campanhas</p>
            <small class="link-brand ml-auto">0</small>
        </a>
        <span class="sidenav-title">Acções</span>
        <a href="#" class="sidenav-link call_config">
            <i class="fas fa-cog"></i>
            <p>Configurações</p>
        </a>
        <a href="<?= route('/') ?>" class="sidenav-link">
            <i class="fas fa-question-circle"></i>
            <p>Perguntas frequentes</p>
        </a>
        <a href="<?= route('/') ?>" class="sidenav-link">
            <i class="fas fa-arrow-left"></i>
            <p>Voltar para Inicio</p>
        </a>
        <a href="<?= route('logout') ?>" class="sidenav-link">
            <i class="fas fa-power-off"></i>
            <p>Terminar sessão</p>
        </a>
    </div>
</aside>

<script>
    $('.call_config').click((e) => { e.preventDefault(); alert('Não disponível de momento'); });
</script>