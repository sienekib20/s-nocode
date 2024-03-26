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

  <?= parts('nav.navbar') ?>
  <header>
    <div class="wallpaper"></div>
    <div class="contain-header">
      <div class="labs-container">
        <div class="text">
          <small class="text-muted">Nova Tendência</small>
          <span class="bold">Sílica nocode</span>
          <small class="text-muted">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Accusantium, <br> Lorem
            ipsum dolor sit amet consectetur.
            provident.</small>
        </div>

        <div class="actions">
          <a href="" class="btn-action">Experimente por 7 dias</a>
        </div>
      </div>
    </div>
  </header>

  <div class="card-section">
    <div class="card-section-header">
      <div class="labs-container">
        <div class="title">
          <small class="colored">SEJA INDEPENDENTE NA GESTÃO DO TEU NEGÓCIO</small>
          <span>Para começar</span>
          <small class="text-muted">Tudo que precisas saber</small>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="labs-container">
        <div class="card-layout-container">
          <div class="card-layout-md">
            <div class="card-header">
              <span class="bi-easel2"></span>
              <span class="bold">Selecione um template</span>
            </div>
            <div class="card-body">
              <small>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur<br> adipisicing</small>
            </div>
          </div> <!--/.card-layout-md-->
          <div class="card-layout-md">
            <div class="card-header">
              <span class="bi-sliders2"></span>
              <span class="bold">Faça as tuas modificações</span>
            </div>
            <div class="card-body">
              <small>ipsum dolor sit amet consectetur adipisicing elit. Consequatur, inventore? </small>
            </div>
          </div> <!--/.card-layout-md-->
          <div class="card-layout-md">
            <div class="card-header">
              <span class="bi-check2-square"></span>
              <span class="bold">Publique o teu negócio</span>
            </div>
            <div class="card-body">
              <small>Eveniet maiores cumque sit consectetur repellendus! Harum! <br> <br> Lorem ipsum dolor sit. </small>
            </div>
          </div> <!--/.card-layout-md-->
        </div> <!--/.card-layout-container-->
      </div>
    </div>
  </div> <!--/.card-section-->

  <div class="card-section bg-white">
    <div class="card-section-header">
      <div class="labs-container">
        <div class="title">
          <small class="colored"></small>
          <span></span>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="labs-container">

        <div class="securty-info">
          <div class="image">
            <img src="/assets/images/33770565_2208.i402.004.S.m004.c13.People with keys flat composition.svg" alt="">
          </div>

          <div class="info">
            <span>Faça a gestão do teu negócio de forma eficiente <br> e com muita segurança </span>

            <div class="includes">
              <div class="item">
                <small class="bi-arrow-right"></small>
                <div class="contain">
                  <span>HTTPS/SSL automaticamente em todas as páginas e sites</span>
                  <p>
                    <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magni accusamus quo ipsa illo vero
                      libero ab recusandae, distinctio dignissimos enim.</small>
                  </p>
                </div>
              </div>
              <div class="item">
                <small class="bi-arrow-right"></small>
                <div class="contain">
                  <span>Melhor posicionamento orgânico no Google</span>
                  <p>
                    <small> it amet consectetur adipisicing elit. Atque reprehenderit odio id vel, itaque facere? Lorem
                      ipsum dolor sit amet consectetur, adipisicing elit. Magni accusamus quo ipsa illo vero libero ab
                      recusandae, distinctio dignissimos enim.</small>
                  </p>

                </div>
              </div>
              <div class="item">
                <small class="bi-arrow-right"></small>
                <div class="contain">
                  <span>Escalabilidade e confiabilidade dos nossos servidores dedicados.</span>
                  <p>
                    <small>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magni accusamus quo ipsa illo vero
                      libero ab recusandae, distinctio dignissimos enim. Lorem ipsum dolor sit, amet consectetur
                      adipisicing elit. Officia eos ducimus exercitationem aperiam mollitia temporibus debitis explicabo
                      magnam ratione deserunt? </small>
                  </p>
                </div>
              </div>
              <div class="item">
                <small class="bi-arrow-right"></small>
                <div class="contain">
                  <span>Equipe de atendimento preparada para ajudar</span>
                  <p>
                    <small>accusamus quo ipsa illo vero libero ab recusandae, distinctio dignissimos enim.</small>
                  </p>
                </div>
              </div>
            </div>
          </div>


        </div> <!--/.security-info-->
      </div>
    </div>
  </div> <!--/.card-section-->


  <div class="card-section">
    <div class="card-section-header">
      <div class="labs-container">
        <div class="title">
          <small class="colored">PLANOS & PREÇOS A MEDIDA DAS SUAS NECESSIDADES</small>
          <span>Sílica nocode</span>
          <small>Landing Pages</small>
        </div>
      </div>
    </div>
    <div class="card-section-contain">
      <div class="labs-container">
        <div class="card-layout-v-container">
          <?php for ($i = 0; $i < 3; $i++) : ?>
            <div class="card-layout-v">
              <div class="card-layout-v-header">
                <div class="header">
                  <span class="title">PLANO GRATUÍTO</span>
                  <!--<smal class="colored">(TYPE/PLAN)</smal>-->
                </div>
                <div class="pricing">
                  <small class="text-muted">A partir de</small>
                  <span class="bold text-muted"> <b>1000</b> <small>Kz/mês</small> </span>
                </div>
              </div>

              <div class="card-layout-v-body">
                <div class="descriptions">
                  <small class="bi-check">Description of this plan</small>
                  <small class="bi-check">Another thing here</small>
                  <small class="bi-check">More comments</small>
                </div>
              </div>

              <a href="" class="btn-action">Experimentar</a>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>



  <?= parts('footer') ?>
</body>

</html>