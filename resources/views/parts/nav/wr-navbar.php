<?php

use Sienekib\Mehael\Support\Auth; ?>

<link rel="stylesheet" href="<?= asset('css/modal_popup.css') ?>">

<nav class="wr-navbar">
    <?= parts('nav.wr-loader') ?>
    <div class="container">
        <div class="wr-navbar-title">
            <span class="bi bi-cloud-haze2"></span>
            <span class="">SÍLICAPAGES</span>
        </div>
        <div class="wr-navbar-items">
            <div class="container">
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
                    <a href="<?= route('browse.categorias.todas') ?>" class="wr-navbar-link <?= request()->path() == '/browse/categorias/todas' ? 'active' : '' ?>">
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
                    <div class="wr-navbar-item" style="white-space: nowrap; background-color: transparent;">
                        <a href="<?= route('user') ?>" id="myDash" class="wr-navbar-link <?= $path == '/dash' ? 'active' : '' ?>">
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
        <div class="wr-navbar-hamburguer ml-auto">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
            <span class="bi bi-arrow-right"></span>
        </div>
    </div>


</nav>
<div class="wr-sidebar-overlay"></div>
<?= parts('nav.wr-alert') ?>
<?= parts('labs.alq-popup') ?>


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
                    var uuid = res.uuid.split('-')[3];
                    var a = document.querySelector('#myDash')
                    a.href = a.href + `/${uuid}/view`;
                });
            },
            error: function(err) {
                //alert('Erro ao carregar o menu');
            }
        });
    });

    function load_modal(title, content) {
        $('.alq-modal').addClass('show');
        $('.alq-modal #modal-title').text('');
        $('.alq-modal #modal-title').text(title);
        $('.alq-modal #explain-content').text('');
        $('.alq-modal #explain-content').text(content);
    }

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

    $('.wr-navbar-hamburguer').click(function(e) {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
        $('.wr-navbar-items').toggleClass('expanded');
    });
    $('#closeIt').click(function(e) {
        e.preventDefault();
        alert('Indisponível de momento');
    })

    let alertIntervalID;
    var maxWidth;

    function make_alert(text) {
        $('#__msg').text('');
        $('#__msg').text(text);
        $('.mkalert').addClass('show');

        $('.alert-timing-progress').css('width', '100%');
        maxWidth = 100;
        alertIntervalID = setInterval(decrease_alert_progress, 100);

        /*setTimeout(() => {
            $('.mkalert').removeClass('show');
        }, 3000);*/
    }

    function decrease_alert_progress() {
        if (maxWidth == 0) {
            $('.mkalert').removeClass('show');
            clearInterval(alertIntervalID);
        }
        $('.alert-timing-progress').css('width', maxWidth + '%');
        maxWidth--;
    }
</script>