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

  <div class="credentials">
    <div class="container">
      <div class="cred-img">
        <img src="<?= asset('images/app/code.ico') ?>" alt="">
        <div class="text">
          <small class="tw-muted">Comece um negócio do teu gosto</small>
          <span class="bold">Seja independente, obtenha um site</span>
        </div>
      </div>
      <form action="" method="POST" class="credentials-data">
        <div class="credential-item">
          <span class="bold"> <span class="bi-code-slash"></span> Nocode | Entrar</span>
          <small class="tw-muted">Construa o teu próprio site</small>
        </div>
        <div class="credential-item">
          <input type="text" name="email" class="credi-input" required>
          <span class="pholder">Email</span>
        </div>
        <div class="credential-item">
          <input type="password" name="password" class="credi-input" required>
          <span class="pholder">Password</span>
        </div>
        <div class="credential-item">
          <button type="submit">Entrar</button>
        </div>
        <div class="credential-item">
          <span class="tw-muted">Não tens conta ainda? <a href="">Criar agora</a> </span>
          <small class="forgot">Esqueceu a senha? <a href="">recupere</a> </small>
        </div>
      </form>
    </div>

  </div> <!-- sk-credentials -->

</body>

</html>