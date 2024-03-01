<?php

use Sienekib\Mehael\Support\Auth; ?>

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
                <a href="<?= route('/') ?>" class="wr-navbar-link">
                    <p>Planos</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('faqs') ?>" class="wr-navbar-link <?= request()->path() == '/faqs' ? 'active' : '' ?>">
                    <p>Faqs</p>
                </a>
            </div>
            <div class="wr-navbar-item">
                <a href="<?= route('contactos') ?>" class="wr-navbar-link <?= request()->path() == '/contactos' ? 'active' : '' ?>">
                    <p>Contactos</p>
                </a>
            </div>
            <?php if (!Auth::check()) :  ?>
                <div class="wr-navbar-item ml-auto">
                    <a href="<?= route('entrar') ?>" class="wr-navbar-link">
                        <p>Entrar</p>
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
    </div>

</nav>


<?= parts('nav.wr-loader') ?>

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

</script>