<div class="topnavi">
    <nav class="tn-navbar">
        <div class="tn-navbar-header">
            <button type="button" class="btn d-flex d-lg-none tn-navbar-toggler">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </button>  
            <span class="tn-navbar-brand">SILICAPAGE</span>
        </div>
        <div class="tn-navbar-items">
            <a href="#" class="tn-link">Ajuda</a>
            <a href="#" class="tn-link" id="call_tn_search"> <i class="fas fa-search"></i> </a>
            <a href="#" class="tn-link"> 
                <i class="fas fa-bell"></i> 
                <span class="count">0</span>
            </a>
            <a href="#" class="tn-link username-letter">
                <?= ucfirst(Sienekib\Mehael\Support\Auth::user()->username[0]) ?> 
            </a>
        </div>
    </nav>
    <form action="" class="tn-search-form">
        <input type="text" id="tn-search" placeholder="Escreve algo">
        <span class="btn fas fa-x close-search"></span>
    </form>
</div>

<?= parts('labs.loader') ?>
<?= parts('overlay') ?>
<?= parts('notificacao') ?>

<script>
    $('#call_tn_search').click((e) => {
        e.preventDefault();
        $('.tn-search-form').addClass('active');
        $('.tn-search-form input').focus();
    }); $('.close-search').click(() => { $('.tn-search-form').removeClass('active');});
</script>