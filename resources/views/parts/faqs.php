<?php
$asked = [
    ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="<?= route(\'contactos\') ?>">contacto</a>', 'active'],
    ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', ''],
    ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', ''],
    ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', ''],
    ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '']
];
?>

<div class="card-top">
    <div class="container-sm">
        <div class="row">
            <div class="card-title col-12 text-center">
                <h2 class="title d-block">Perguntas mais frequentes</h2>
                <small class="text-muted"></small>
                <div style="visibility: hidden; position: absolute; width: 0px; height: 0px;">
                  <svg xmlns="http://www.w3.org/2000/svg">
                    <symbol viewBox="0 0 24 24" id="expand-more">
                      <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/><path d="M0 0h24v24H0z" fill="none"/>
                    </symbol>
                    <symbol viewBox="0 0 24 24" id="close">
                      <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/>
                    </symbol>
                  </svg>
                </div>
            </div>
        </div>
    </div>
</div> <!--/.card-top-->

<div class="card-body mt-2">
  <div class="container-sm">
    <div class="row">
      <div class="col-12 mt-5 offset-xs-0 offset-md-1 col-md-10">
        <?php foreach($asked as $ask): ?>
          <details <?= $ask[2] == 'active' ? 'open' : '' ?>>
            <summary>
              <?= $ask[0] ?>
              <svg class="control-icon control-icon-expand" width="24" height="24" role="presentation"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#expand-more" /></svg>
              <svg class="control-icon control-icon-close" width="24" height="24" role="presentation"><use xmlns:xlink="http://www.w3.org/1999/xlink" fill="#f71" xlink:href="#close" /></svg>
            </summary>
            <p><?= nl2br($ask[1]) ?></p>
          </details>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>