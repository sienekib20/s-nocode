<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>%title%</title>
  <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/editor.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/media.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
</head>

<body>
  <div class="authentiq">
    <div class="authentiq-sitename">
      <div class="name">
        <span class="bi bi-code-slash"></span>
        <span>nocode</span>
      </div>
    </div>
    <div class="authentiq-fcontainer">
      <form action="" class="authentiq-form">
        <div class="authentiq-fitem">
          <input type="text" class="authentiq-control" placeholder="Email">
          <small class="__error"></small>
        </div>
        <div class="authentiq-fitem">
          <input type="password" class="authentiq-control" placeholder="Password">
          <small class="__error"></small>
        </div>
        <div class="authentiq-fitem">
          <button type="submit" class="authentiq-control">Entrar</button>
        </div>
        <div class="authentiq-fitem flex">
          <div>
            <small class="tw-muted">Esqueceu a senha?</small>
            <a href="" class="tw-muted">Recupe a sua senha</a>
          </div>
          <a href="" class="tw-muted">Criar conta</a>
        </div>
      </form>
    </div>
  </div>

</body>

</html>