<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

    <div class="wrapper">
        <?= parts('nav.header') ?>


        <?= parts('services.sotfwares') ?>


        <?= parts('faqs.faq') ?>

        <small class="d-flex my-4"></small>


        

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="card-title col-12 col-md-6">
                            <h4 class="title d-block mt-5">Fale connosco</h4>
                            <small class="ff">Entre em contato conosco para mais informações sobre nossos serviços de criação de sites.</small>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
            <small class="d-flex my-4"></small>
            <div class="card-body">
                <div class="container-sm">
                    <div class="row my-5">
                        <div class="col-12 col-md-4">
                            <div class="cus-item">
                                <span class="bi bi-chat-left-text"></span>
                                <div class="contain">
                                    <span class="title">Converse connosco</span>
                                    <small class="text-muted">O nosso team está aqui pra te ajudar</small>
                                    <small>cc@silicaweb.ao</small>
                                </div>
                            </div> <!--/.cus-item-->
                            <div class="cus-item">
                                <span class="bi bi-geo"></span>
                                <div class="contain">
                                    <span class="title">Visite-nos</span>
                                    <small class="text-muted">Venha dizer olá no nosso escritório</small>
                                    <small>Bº Azul - Zamba II. Ref. Memorial A.Neto Luanda-Angola</small>
                                </div>
                            </div> <!--/.cus-item-->
                            <div class="cus-item">
                                <span class="bi bi-telephone-outbound"></span>
                                <div class="contain">
                                    <span class="title">Ligue pra nós</span>
                                    <small class="text-muted">Seg. à Sexta, das 8h às 17h</small>
                                    <small>(+244) 948 109 778</small>
                                </div>
                            </div> <!--/.cus-item-->
                        </div> <!--/.col-->
                        <div class="col-12 col-md-1"></div>
                        <form action="" class="col-12 col-md-7 mt-5 mt-md-0" id="introMailForm">
                            <div class="input-group">
                                <input type="text" name="username" class="form-input" placeholder="Seu nome" required>
                            </div>
                            <div class="input-group my-3">
                                <input type="text" name="email" class="form-input" placeholder="Seu email" required>
                            </div>
                            <div class="input-group mb-3">
                                <textarea name="mensagem" class="form-input" cols="30" rows="4" placeholder="Nos fale da tua preocupação" required></textarea>
                            </div>
                            <div class="input-group">
                                <button type="submit" id="sendIntroMailForm" class="btn btn-orange input-block">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>


        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper-->


</body>

</html>
<?php use \Sienekib\Mehael\Support\Auth;  if(Auth::check()):  ?>
<input type="hidden" id="activeUserId" value="<?= Auth::user()->id ?>">
<?php endif; ?>
<script>
    $('#introMailForm').submit((e) => {
        e.preventDefault();
        var activeUser = $('#activeUserId').val();
        if (activeUser) {
            const formData = new FormData(e.target);
                formData.append('account', activeUser);
            $.ajax({
                url: '/introMail',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    console.log(res)
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
        alert('Entre com a sua conta!');
    });
</script>