<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>%title%</title>
  <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
  <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

  <div class="wrapper">
    <?= parts('nav.navbar') ?>

    <div class="card">
      <div class="card-top">
        <div class="container-sm">
          <div class="row">
            <div class="card-title text-center col-12">
              <span class="d-block bold">Planos de uso</span>
              <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> Escolha única trazendo uma poupança múltipla</small>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body mt-5">
        <div class="container-sm">
          <div class="row align-items-start justify-content-center">
            <?php foreach ($enviar as $planos) :  ?>
              <div class="col-md-3">
                <div class="card-plan mt-xxs-3">
                  <div class="card-plan-top">
                    <span class="title d-block"><?= $planos['pacote'] ?></span>
                    <div class="d-flex align-items-baseline"> <small>Preço oficial</small> <span class="d-block">0,00KZ</span></div>
                  </div>
                  <div class="card-plan-body">
                    <div class="d-flex flex-direction-column">
                      <?php foreach ($planos['desc'] as $plane) : ?>
                        <div class="d-flex align-items-center card-plan-item">
                          <small class="bi bi-check"></small>
                          <small class="text-muted"><?= $plane ?></small>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <?php if ($planos['pacote'] == 'Básico') : ?>
                      <a href="{{ route('aderir', 1) }}" class="btn btn-orange input-block my-3 d-block">Aderir</a>
                    <?php else : ?>
                      <a href="{{ route('aderir', 1) }}" class="btn btn-outline-orange input-block my-3 d-block">Aderir</a>
                    <?php endif; ?>
                  </div>
                </div> <!--/.card-plan-->
              </div> <!--/.col-md-3-->
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div> <!--/.card-->

    <small class="d-block mt-5"></small>
    <div class="card">
      <div class="card-body">
        <div class="container-sm">
          <div class="row">
            <div class="col-lg-5">
              <span class="title">Respostas de Perguntas mais frequentes</span>
              <small class="text-muted d-block">Tire todas as tuas dúvidas</small>
            </div>
            <div class="col-lg-7">
              <div class="faqs">
                <?php for ($i = 0; $i < 4; $i++) : ?>
                  <div class="faqItem">
                    <div class="faqItem-top"> <span class="bi bi-chevron-down">FaqItem question</span></div>
                    <div class="faqItem-body">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Asperiores, reiciendis!</div>
                  </div>
                <?php endfor; ?>
              </div> <!-- faqs -->
            </div>
          </div>
        </div>
      </div>
    </div><!--/.card-->

    <small class="d-block my-5"></small>

    @parts('nav.footer')
  </div> <!--/.wrapper-->
</body>

</html>