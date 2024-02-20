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
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

    <div class="wrapper">
        
        <?= parts('user.topnav') ?>

        <small class="d-block mt-3"></small>

        <div class="container-sm w-100 w-md-80 mt-3 mb-3">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="bold">As Minhas Campanhas</h3>
                    <span class="text-muted">Lendo a mensagem do cliente #x </span>
                </div>
            </div>
        </div>

        <div class="container-sm w-100 w-md-80 mt-3 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="inbox-contain">
                        <div class="inbox-top row align-items-center">
                            <span class="col-12 col-md-2 text-muted">Ler Mensagem de <a href="">remitente@dominio.com</a> </span>
                        </div>
                        <div class="inbox-body">
                            <span class="bold my-3 d-block">Sujeito da mensagem</span>
                            <p class="my-3 contain-text-sms">Lorem ipsum dolor sit amet consectetur adipisicing, elit. Deserunt necessitatibus iure, at cum sunt, nulla. Voluptatem, sint! Amet, qui aperiam. Lorem ipsum dolor, sit amet consectetur, adipisicing elit. Dolore minima quibusdam quod aspernatur vitae sequi libero. Soluta voluptatibus ducimus fugiat. Ut totam, commodi reprehenderit praesentium voluptate quam dicta assumenda quaerat debitis, obcaecati, accusantium consectetur temporibus?Lorem, ipsum.lorem20 Lorem ipsum dolor, sit amet, consectetur adipisicing elit. Debitis eligendi necessitatibus fuga, tempore veniam magnam nemo recusandae laboriosam ea harum unde nobis quidem quas distinctio repellendus temporibus deleniti quos doloribus odio perferendis sunt laudantium pariatur. Doloribus, cupiditate optio necessitatibus fugiat, obcaecati dolore impedit rerum consequatur mollitia eos dolor perferendis voluptate. Lorem ipsum dolor, sit amet consectetur adipisicing, elit. Libero vero esse ducimus unde autem dolorum ut non voluptates itaque quia. Tempora, illum voluptas illo deleniti dignissimos reprehenderit a minima id rerum magni consequuntur obcaecati hic est error quo labore distinctio nesciunt aliquid sequi eum, facilis, non voluptatum atque iusto. Repellat. </p>

                            <form action="">
                                <div class="form-group">
                                    <textarea name="" cols="30" rows="4" class="form-input input-block" placeholder="Responder"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="__" class="btn btn-outline-orange"> <span class="bi bi-anex"></span> Anexar</label>
                                            <input type="file" id="__" hidden>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-orange">Enviar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!--/.wrapper-->

</body>

</html>