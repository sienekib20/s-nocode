<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>%title%</title>
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/editor.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/media.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
</head>

<body>

  <?= parts('labs.navbar') ?>
  <header>
    <div class="wallpaper"></div>
    <div class="ncode-container">
      <div class="nocode-header-caption">
        <span class="bold">Sílica Nocode <br> nova tendência de negócios & <br> Torne-se independete
        </span>
        <small class="tw-muted">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolores dolor consequatur <br>
          ipsum debitis, quis blanditiis ab sint odit quidem labore.</small>

        <div class="ncode-actions">
          <a href="" class="ncode-btn-action active"> <small>Explorar</small></a>
          <a href="" class="ncode-btn-action"> <small>Registra-te</small></a>
        </div>

        <div class="titlebar">
          <div class="titlebar-item">
            <span class="title">+ 4.5K</span>
            <small>Usuários aderiram</small>
          </div>
          <div class="titlebar-item">
            <span class="title">+ 1.5K</span>
            <small>Landing Páginas</small>
          </div>
          <div class="titlebar-item">
            <span class="title">+ 0.5K</span>
            <small>Dashboard</small>
          </div>
        </div>

      </div>
    </div>
  </header>

  <div class="card-section">

    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-section-row">
          <div class="contain-text">
            <div class="text-header">
              <span class="title">Nocode| Ferramenta Sílica</span>
            </div>
            <div class="text-items">
              <small class="bi-arrow-right">A Sílica a pensar sempre em ti trazendo soluções inovadoras</small>
              <small class="bi-arrow-right">Torne-se um parceiro com SP|Nocode</small>
              <small class="bi-arrow-right">Adera ao projeto e facilite o seu trablaho</small>
              <small class="bi-check tw-muted">Escolha um template a sua escolha</small>
              <small class="bi-check tw-muted">Edite-a com o seu tom de gosto e salve-a</small>
              <small class="bi-check tw-muted">Defina o novo domínio para o seu uso</small>
              <small class="bi-check tw-muted">E publique o seu novo site</small>
            </div>
          </div>
          <div class="contain-static-slider">
            <div class="static-slider-item">
              <img src="/assets/images/ncode-1.jpg" alt="">
            </div>
            <div class="static-slider-item">
              <img src="/assets/images/ncode-2.jpg" alt="">
            </div>
            <div class="static-slider-item">
              <img src="/assets/images/ncode-3.jpg" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card-section">
    <div class="wall"></div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="ncode-trailer">
          <span class="bold">Não sabes como Funciona? <br> Ensinamos você </span>
          <div class="ncode-video-play">
            <small class="bi-play-fill"></small>
            <small>Assistir</small>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="card-section">
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-pricing">
          <div class="card-pricing-header">
            <span class="title">ESCOLHA UM PLANO PARA SI</span>
            <div class="ncode-actions">
              <a href="" class="ncode-btn-action active" name="pricing-mensal"> <small>Mensal</small></a>
              <a href="" class="ncode-btn-action" name="pricing-anual"> <small>Anual</small></a>
            </div>
          </div>
          <div class="card-pricing-contain active" id="pricing-mensal">
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Gratuito</span> </span>
              <span class="price">AO <span>0,00</span> </span>
              <div class="items">
                <small class="bi-check">1 Template no máximo</small>
                <small class="bi-check">Template sem interação</small>
                <small class="bi-check">Alteração limitada</small>
                <small class="bi-check">Domínio disponivel por 30 dias</small>
                <small class="bi-check">Sem suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Prata</span> </span>
              <span class="price">AO <span>37.500,00</span> </span>
              <div class="items">
                <small class="bi-check">7 Template no máximo</small>
                <small class="bi-check">Template interativo</small>
                <small class="bi-check">Alteração livre</small>
                <small class="bi-check">Domínio disponivel por 90 dias</small>
                <small class="bi-check">Suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Bronze</span> </span>
              <span class="price">AO <span>25.000,00</span> </span>
              <div class="items">
                <small class="bi-check">3 Template no máximo</small>
                <small class="bi-check">Template mais ou menos interativo</small>
                <small class="bi-check">Alteração normalizada</small>
                <small class="bi-check">Domínio disponivel por 45 dias</small>
                <small class="bi-check">Suporte online limitada</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
          </div>

          <div class="card-pricing-contain" id="pricing-anual">
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Gratuito</span> </span>
              <span class="price">AO <span>0,00</span> </span>
              <div class="items">
                <small class="bi-check">1 Template no máximo</small>
                <small class="bi-check">Template sem interação</small>
                <small class="bi-check">Alteração limitada</small>
                <small class="bi-check">Domínio disponivel por 60 dias</small>
                <small class="bi-check">Sem suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Prata</span> </span>
              <span class="price">AO <span>60.000,00</span> </span>
              <div class="items">
                <small class="bi-check">7 Template no máximo</small>
                <small class="bi-check">Template interativo</small>
                <small class="bi-check">Alteração livre</small>
                <small class="bi-check">Domínio disponivel por 180 dias</small>
                <small class="bi-check">Suporte online</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
            <div class="card-pricing-item">
              <span class="name"><small>Plano</small> <br> <span>Bronze</span> </span>
              <span class="price">AO <span>45.000,00</span> </span>
              <div class="items">
                <small class="bi-check">3 Template no máximo</small>
                <small class="bi-check">Template mais ou menos interativo</small>
                <small class="bi-check">Alteração normalizada</small>
                <small class="bi-check">Domínio disponivel por 105 dias</small>
                <small class="bi-check">Suporte online limitada</small>
              </div>
              <a href="" class="btn-action">Subscrever</a>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>

  <?php
  $faqs = [
    [
      'title' => 'O que é preciso para começar?',
      'answer' => 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano, só assim que podes começar'
    ],
    [
      'title' => 'Quando o meu plano atingir o limite, o meu site pára de funcionar?',
      'answer' => 'Antes que expire o teu plano receberas notificações antes do tempo'
    ],
    [
      'title' => 'É preciso saber programar pra modificar o template?',
      'answer' => 'Pensamos em si, podes não saber programar'
    ],
    [
      'title' => 'Com quem posso partilhar o meu dóminio',
      'answer' => 'Reposta a processar'
    ],
    [
      'title' => 'Existem template para o meu tipo de negócio?',
      'answer' => 'Os templates são de carater aberto para adequá-los ao teu tipo de negócio'
    ]
  ];
  ?>

  <div class="card-section">
    <div class="card-section-header">
      <div class="ncode-container">
        <div class="title">
          <span class="bold">Perguntas Mais Frequentes</span>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-faqs-container">
          <?php for ($i = 0; $i < count($faqs); $i++) : ?>
            <div class="faqs-item <?= ($i == 0) ? 'active' : '' ?>">
              <div class="faqs-header">
                <small class="name">
                  <?= $faqs[$i]['title'] ?>
                </small>
                <small class="bi-chevron-down"></small>
              </div>
              <div class="faqs-contain">
                <small class="tw-muted">
                  <?= $faqs[$i]['answer'] ?>
                </small>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="card-section">
    <div class="card-section-header">
      <div class="ncode-container">
        <div class="title">
          <small class="tw-muted"></small>
          <span class="bold">Contactos</span>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="ncode-container">
        <div class="card-section-row">
          <div class="contacts">
            <div class="contact-item">
              <span class="text">TIre as tuas dúvidas</span>
            </div>
            <div class="contact-item">
              <small class="bi-pin-map-fill"></small>
              <div class="text">
                <small>Av. Doutor António A. Neto, Bairro Azul| Ingombota Luanda, Angola
                  Referencia Viaduto do Zamba 2 <br> Estamos aberto de segunda à sexta|das 8h às 17h</small>

              </div>
            </div>
            <div class="contact-item">
              <small class="bi-telephone-fill"></small>
              <div class="text">
                <small> <a href="">+244 948 109 778</a></small>
              </div>
            </div>
            <div class="contact-item">
              <small class="bi-envelope-at-fill"></small>
              <div class="text">
                <small> <a href="">cc@silicaweb.ao</a> </small>
              </div>
            </div>
            <div class="contact-item">
              <span class="text">Acompanhe-nos as redes sociais</span>
            </div>
            <div class="contact-social">
              <a href="https://webmail.silicaweb.ao/" class="btn-link" target="_blank"><small class=""><small>Wm</small></small></a>
              <a href="" class="btn-link"><small class="bi-linkedin"></small></a>
              <a href="" class="btn-link"><small class="bi-facebook"></small></a>
              <a href="" class="btn-link"><small class="bi-youtube"></small></a>
            </div>
          </div>
          <form action="" class="subject">
            <div class="ncode-form-row">
              <div class="ncode-form-item">
                <input type="text" class="ncode-input" name="contact_name" required>
                <small class="placeholder">Nome</small>
              </div>
              <div class="ncode-form-item">
                <input type="text" class="ncode-input" name="contact_email" required>
                <small class="placeholder">Email</small>
              </div>
            </div>
            <div class="ncode-form-item">
              <input type="text" class="ncode-input" name="contact_subject" required>
              <small class="placeholder">Sujeito</small>
            </div>
            <div class="ncode-form-item">
              <textarea type="text" class="ncode-input" name="contact_msg" required></textarea>
              <small class="placeholder">Mensagem</small>
            </div>
            <div class="ncode-form-item">
              <button type="submit">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?= parts('footer') ?>


  <script src="<?= asset('js/site/index.js') ?>"></script>
</body>

</html>