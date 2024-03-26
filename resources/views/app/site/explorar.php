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
  <header class="mh">
    <div class="wallpaper"></div>
    <div class="ncode-container">
      <div class="nocode-header-caption">
        <span class="bold">Nocode Templates
        </span>
        <small class="tw-muted">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolores dolor consequatur <br>
          ipsum debitis, quis blanditiis ab sint odit quidem labore.</small>
        <form action="">
          <div class="ncode-form-item">
            <input type="text" class="ncode-input" name="searchable" placeholder="Buscar template">
            <small class="bi-search"></small>
          </div>
        </form>
        <div class="ncode-filter">
          <a href="" class="ncode-filter-item active">
            <small class="item">Landing Pages</small>
          </a>
          <a href="" class="ncode-filter-item">
            <small class="item">Dashboard</small>
          </a>
          <a href="" class="ncode-filter-item">
            <small class="item">Tudo</small>
          </a>
        </div>
      </div>
    </div>
  </header>

  <div class="card-section">
    <div class="card-section-contain">
      <div class="ncode-container">
        <?php for ($x = 0; $x < 1; $x++) : ?>
          <div class="card-templates-row">
            <?php foreach ($templates as $template) : ?>
              <a href="<?= 'editor/' . $template->uuid ?>" target="_blank" class="card-template-item">

                <div class="cover">
                  <img src="<?= storage() . "templates/defaults/{$template->referencia}/cover/{$template->capa}" ?>" alt="">
                </div>
                <div class="template-info">
                  <div>
                    <small class="name">
                      <?= ucfirst($template->titulo) ?>
                    </small>
                    <small class="tw-muted">autor:
                      <?= 'Sílica' ?>
                    </small>
                  </div>
                  <small class="bi-heart-fill tw-muted"> <span class="qtd">0</span> </small>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>


  <?= parts('footer') ?>


  <script src="<?= asset('js/site/index.js') ?>"></script>
</body>

</html>