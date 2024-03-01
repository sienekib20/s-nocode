<?php

use Sienekib\Mehael\Support\Auth;

$path = explode('/', ltrim(request()->path(), '/'));
$path = '/' . end($path);
?>

<div class="wr-sidebar">
    <div class="wr-sidebar-item">
        <a href="<?= route('dash') ?>" id="wr-sidebar-1" class="wr-sidebar-link <?= $path == '/view' ? 'active' : '' ?>">
            <span class="bi bi-grid-fill"></span>
            <span class="text">Visal geral</span>
        </a>
    </div>

    <div class="wr-sidebar-item">
        <a href="<?= route('dash') ?>" id="wr-sidebar-2" class="wr-sidebar-link">
            <span class="bi bi-collection"></span>
            <span class="text">Meus websites</span>
        </a>
    </div>

    <div class="wr-sidebar-item">
        <a href="<?= route('dash') ?>" id="wr-sidebar-3" class="wr-sidebar-link">
            <span class="bi bi-layers-fill"></span>
            <span class="text">Encomendar website</span>
        </a>
    </div>

    <div class="wr-sidebar-item">
        <a href="<?= route('dash') ?>" id="wr-sidebar-4" class="wr-sidebar-link">
            <span class="bi bi-megaphone"></span>
            <span class="text">Minhas campanhas</span>
        </a>
    </div>

    <div class="wr-sidebar-separator"></div>

    <div class="wr-sidebar-item">
        <a href="<?= route('dash') ?>" id="wr-sidebar-5" class="wr-sidebar-link">
            <span class="bi bi-bell"></span>
            <span class="text">Notificações</span>
        </a>
    </div>

    <div class="wr-sidebar-item">
        <a href="#" id="wr-sidebar-5" class="wr-sidebar-link">
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
                    var uuid = res.uuid.split('-')[0];
                    var a = document.querySelector('#myDash')
                    $('.wr-sidebar-link').each(function() {
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