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
            <div class="col-12">
              <span class="d-block bold">Planos de uso</span>
              <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> Escolha única trazendo uma poupança múltipla</small>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body mt-5">
        <div class="container-sm">
          <div class="row align-items-start">
            <div class="col-md-3">
              <div class="card-plan mt-xxs-3">
                <div class="card-plan-top">
                  <span class="title d-block">Grátis</span>
                  <div class="d-flex align-items-baseline"> <small>Preço</small> <span class="d-block">0,00KZ</span></div>
                </div>
                <div class="card-plan-body">
                  <div class="d-flex flex-direction-column">
                    <div class="d-flex align-items-center">
                      <small class="bi bi-check"></small>
                      <small class="text-muted">1 Template no máximo</small>
                    </div>

                    <small class="d-flex  bi bi-check">Domínio válido por30 dias</small>
                    <small class="d-flex align-items-center bi bi-check">Sem suporte</small>
                    <small class="d-flex align-items-center bi bi-check">1 Template no máximo</small>
                  </div>
                  <a href="{{ route('aderir', 1) }}" class="btn btn-orange input-block my-3 d-block">Aderir</a>
                </div>
              </div> <!--/.card-plan-->
            </div> <!--/.col-md-3-->
          </div>
        </div>
      </div>
    </div> <!--/.card-->

    <small class="d-block mt-5"></small>
    <div class="card">
      <div class="card-top">
        <div class="container-sm">
          <div class="row">
            <div class="col-12">
              <span class="d-block bold">Respostas de Perguntas Frequentes</span>
              <small class="text-muted d-block"> <span class="bi bi-arrow-right"></span> Tire aqui a sua dúvida quanto a escolha e uso de planos.</small>
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