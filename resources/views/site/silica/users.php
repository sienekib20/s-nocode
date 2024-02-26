<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>%title%</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/ui/cool-alert.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/ui/ui-alert.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/style-bs.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
  <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

  <div class="extended">
    <!-- sidenav -->
    <?= parts('user.aside') ?>
    <!-- navbar -->
    <?= parts('user.topnav') ?>

    <div class="contain-extends mt-1">

      <div class="ex-card">
        <div class="container-sm mt-3 ">
          <div class="row">
            <div class="col-md-6">
              <h5 class="bold">Seja bemvindo, <span id="name"><?= Sienekib\Mehael\Support\Auth::user()->username ?></span> </h5>
              <span class="text-muted">Os teus sites estão progredindo muito bem, dê uma olhada e veja o que podes melhorar</span>
            </div>
          </div>
        </div>

        <div class="container-sm mt-3 mb-5">
          <div class="row align-items-center">
            <div class="col-md-3">
              <div class="card-plan mt-xxs-3">
                <div class="card-plan-top">
                  <div class="d-flex align-items-baseline"> <small>Total de <?= $templateUsuario->total ?></small></div>
                </div>
                <div class="card-plan-body mt-2">
                  <div class="d-flex flex-direction-column">
                    <div class="d-flex align-items-center card-plan-item">
                      <small class="bi bi-check"></small>
                      <small class="text-muted">Templates</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-plan mt-xxs-3">
                <div class="card-plan-top">
                  <div class="d-flex align-items-baseline"> <small>Total de <?= $subscricoes ?></small></div>
                </div>
                <div class="card-plan-body mt-2">
                  <div class="d-flex flex-direction-column">
                    <div class="d-flex align-items-center card-plan-item">
                      <small class="bi bi-check"></small>
                      <small class="text-muted">Subscrição de pacotes</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-plan mt-xxs-3">
                <div class="card-plan-top">
                  <div class="d-flex align-items-baseline"> <small>Total de <?= $encomendas ?></small></div>
                </div>
                <div class="card-plan-body mt-2">
                  <div class="d-flex flex-direction-column">
                    <div class="d-flex align-items-center card-plan-item">
                      <small class="bi bi-check"></small>
                      <small class="text-muted">Encomendas</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-plan mt-xxs-3">
                <div class="card-plan-top">
                  <div class="d-flex align-items-baseline"> <small>Total de <?= $encomendas ?></small></div>
                </div>
                <div class="card-plan-body mt-2">
                  <div class="d-flex flex-direction-column">
                    <div class="d-flex align-items-center card-plan-item">
                      <small class="bi bi-check"></small>
                      <small class="text-muted">Campanhas</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>


</body>

</html>