<?php

use Sienekib\Mehael\Support\Auth;

$path = explode('/', ltrim(request()->path(), '/'));
$path = '/' . end($path);
?>

<div class="__wr-sidebar-overlay"></div>

<div class="__wr-sidebar">

    <div class="__wr-sidebar-close">
        <span class="bi bi-arrow-left"></span>
    </div>

    <div class="_wr-sidebar-item">
        <a href="<?= route('user') ?>" id="wr-sidebar-1" class="_wr-sidebar-link <?= $path == '/view' ? 'active' : '' ?>">
            <span class="bi bi-grid-fill"></span>
            <span class="text">Visal geral</span>
        </a>
    </div>

    <div class="_wr-sidebar-item">
        <a href="<?= route('user') ?>" id="wr-sidebar-2" class="_wr-sidebar-link <?= $path == '/websites' ? 'active' : '' ?>">
            <span class="bi bi-collection"></span>
            <span class="text">Meus websites</span>
        </a>
    </div>

    <div class="_wr-sidebar-item">
        <a href="<?= route('user') ?>" id="wr-sidebar-3" class="_wr-sidebar-link <?= $path == '/encomendas' ? 'active' : '' ?>">
            <span class="bi bi-layers-fill"></span>
            <span class="text">Encomendar website</span>
        </a>
    </div>

    <div class="_wr-sidebar-item">
        <a href="<?= route('user') ?>" id="wr-sidebar-4" class="_wr-sidebar-link <?= $path == '/campanhas' ? 'active' : '' ?>">
            <span class="bi bi-megaphone"></span>
            <span class="text">Minhas campanhas</span>
        </a>
    </div>

    <div class="_wr-sidebar-item">
        <a href="<?= route('user') ?>" id="wr-sidebar-5" class="_wr-sidebar-link <?= $path == '/notificao' ? 'active' : '' ?>">
            <span class="bi bi-bell"></span>
            <span class="text">Notificações</span>
        </a>
    </div>

    <div class="_wr-sidebar-item">
        <a href="#" id="wr-sidebar-5" class="_wr-sidebar-link">
            <span class="bi bi-gear"></span>
            <span class="text">Definições da conta</span>
        </a>
    </div>
</div>
<input type="hidden" id="user-ids" value="<?= Auth::user()->id ?>">
<script>
    $(document).ready(() => {
        var menu = ['view', 'websites', 'encomendas', 'campanhas', 'notificao'];
        var iterator = 0;
        $.ajax({
            url: '/userId',
            method: 'POST',
            dataType: 'JSON',
            data: {
                id: $('#user-ids').val()
            },
            success: function(res) {
                $.each(res, (key, val) => {
                    var uuid = res.uuid.split('-')[3];
                    var a = document.querySelector('#myDash')
                    $('._wr-sidebar-link').each(function() {
                        var href = $(this).attr('href');
                        if (href != '#') {
                            $(this).attr('href', `${href}/${uuid}/${menu[iterator]}`);
                        }
                        iterator++;
                    });
                });
            },
            error: function(err) {
                //alert('Erro ao carregar o menu');
            }
        });
    });
</script>