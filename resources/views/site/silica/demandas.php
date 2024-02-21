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
                                <h5 class="bold">Encomendar website</span> </h5>
                                <span class="text-muted">É possivel ter um website personalizado, de acorodo com as tuas necessidades.</span>
                            </div>
                        </div>
                    </div>

                    <div class="container-sm mt-3 mb-5">
                        <form action="" class="col-12">
                            <div class="row">
                                <div class="col-12 col-md-6 pl-md-0">
                                    <div class="form-group">
                                        <select name="who" class="form-input input-block">
                                            <option value="">Quem vai usar ?</option>
                                            <option value="Eu">Apenas mim</option>
                                            <option value="Eu">A minha empresa</option>
                                            <option value="Eu">Os meus clientes</option>
                                            <option value="Eu">Outro</option>
                                        </select>
                                        <small class="invalid-feeback"></small>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 pl-md-0">
                                    <div class="form-group">
                                        <select name="who" class="form-input input-block">
                                            <option value="">Tipo de negócios</option>
                                            <?php foreach($categorias as $cat): ?>
                                                <option value="<?= $cat->tipo_template_id ?>"><?=$cat->tipo_template?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="invalid-feeback"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 pl-md-0">
                                    <div class="form-group">
                                        <label for="modelo" class="form-input input-block bg-white">
                                            <span class="bi bi-image d-flex align-items-center" style="gap: 5px; cursor: pointer;">Carregar uma imagem de modelo</span>
                                        </label>
                                    </div>
                                    <input type="file" hidden id="modelo">
                                </div>
                                <div class="col-12 col-md-6 pl-md-0">
                                    <div class="form-group">
                                        <input type="text" name="tel" class="form-input input-block" placeholder="Telefone">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 pl-md-0">
                                    <textarea placeholder="Descreva aqui" name="" class="form-input input-block" cols="30" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 pl-md-0 mt-3">
                                    <button type="submit" class="btn btn-orange input-block">Enviar pedido</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

    </div>

</body>

</html>