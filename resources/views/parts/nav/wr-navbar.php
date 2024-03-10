<?php

use Sienekib\Mehael\Support\Auth; ?>

<nav class="wr-navbar">
<?= parts('nav.wr-loader') ?>
    <div class="container">
        <div class="wr-navbar-title">
            <span class="bi bi-cloud-haze2"></span>
            <span class="">SÍLICAPAGES</span>
        </div>
        <div class="wr-navbar-items">
            <div class="wr-navbar-item ml-auto">
                <a href="<?= route('/') ?>" class="wr-navbar-link <?= request()->path() == '/' ? 'active' : '' ?>">
                    <p>Início</p>
                </a>
            </div>
            <!--<div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Softwares</p>
                </a>
            </div>-->
            <div class="wr-navbar-item">
                <a href="<?= route('browse') ?>" class="wr-navbar-link <?= request()->path() == '/browse' ? 'active' : '' ?>">
                    <p>Templates</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('/') ?>" class="wr-navbar-link" id="closeIt">
                    <p>Planos</p>
                </a>
            </div>
            <!--<div class="wr-navbar-item">
                <a href="<?= route('faqs') ?>" class="wr-navbar-link <?= request()->path() == '/faqs' ? 'active' : '' ?>">
                    <p>Faqs</p>
                </a>
            </div>-->
            <!--<div class="wr-navbar-item">
                <a href="<?= route('contactos') ?>" class="wr-navbar-link <?= request()->path() == '/contactos' ? 'active' : '' ?>">
                    <p>Contactos</p>
                </a>
            </div> -->
            <?php if (!Auth::check()) :  ?>
                <div class="wr-navbar-item">
                    <a href="<?= route('entrar') ?>" class="wr-navbar-link">
                        <p>Fazer login</p>
                    </a>
                </div>
                <div class="wr-navbar-item wr-nav-btn">
                    <a href="<?= route('browse') ?>" class="wr-navbar-link">
                        <p>Explorar agora</p>
                    </a>
                </div>
            <?php else : ?>
                <?php $path = '/' . explode('/', ltrim(request()->path(), '/'))[0] ?>
                <input type="hidden" id="user-id" value="<?= Auth::user()->id ?>">
                <div class="wr-navbar-item ml-auto" style="white-space: nowrap; background-color: transparent;">
                    <a href="<?= route('dash') ?>" id="myDash" class="wr-navbar-link <?= $path == '/dash' ? 'active' : '' ?>">
                        <p>Meu dashboard</p>
                    </a>
                </div>

                <div class="wr-navbar-item">
                    <a href="<?= route('logout') ?>" class="wr-navbar-link">
                        <p>Sair</p>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="wr-navbar-hamburguer ml-auto">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>

    
</nav>
<div class="wr-sidebar-overlay"></div>


<script>
    $(document).ready(() => {
        $.ajax({
            url: '/userId',
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: $('#user-id').val()
            },
            success: function(res) {
                $.each(res, (key, val) => {
                    var uuid = res.uuid.split('-')[0];
                    var a = document.querySelector('#myDash')
                    a.href = a.href + `/${uuid}/view`;
                });
            },
            error: function(err) {
                //alert('Erro ao carregar o menu');
            }
        });
    });

    function changeWrNavbar() {
        $(window).on('scroll', (e) => {
            if (window.scrollY > 100) {
                $('.wr-navbar').addClass('dark');
            } else {
                $('.wr-navbar').removeClass('dark');
            }
        });
    }

    function applyDarkNavbar() {
        $('.wr-navbar').addClass('dark');
    }

    $('.wr-navbar-hamburguer').click((e) => {
        $('.wr-navbar-items').toggleClass('expanded');
    });
    $('#closeIt').click(function(e) {
        e.preventDefault();
        alert('Indisponível de momento');
    })
</script>