<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/wr-table.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>
    <div class="wrapper">
        <?= parts('nav.wr-navbar-alt') ?>

        <small class="d-flex my-4"></small>
        <small class="d-flex my-4"></small>
        <div class="contain-wrapper">
            <div class="container-sm">
                <?php parts('nav.wr-sidebar') ?>

                <div class="contain-pages">
                    <div class="card">
                        <div class="card-top">
                            <div class="container-sm">
                                <div class="row">
                                    <div class="card-title col-12">
                                        <h4 class="title d-block">Meus websites</h4>
                                        <small class="ff">Todos os websites que já adquiriste.</small>
                                        <small class="d-block text-muted"> <span class="bi bi-arrow-right"></span> Só podes ter 2 websites no máximo por enquanto</small>
                                    </div>
                                </div>
                            </div>
                        </div> <!--/.card-top-->
                        <div class="card-body mt-4">
                            <div class="row">
                                <form action="" class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" id="liveSearch" class="form-input" placeholder="Pesquisar template...">
                                        <span class="bi bi-search"></span>
                                    </div>
                                </form>
                                <div class="col-md-4 mt-2 mt-md-0">
                                    <a href="" class="btn btn-white"> <span class="fas fa-play"></span> Pausar </a>
                                    <a href="" class="btn btn-primary"> <span class="fas fa-edit"></span> editar </a>
                                    <a href="" class="btn btn-orange"> <span class="fas fa-trash"></span> remover </a>
                                </div>
                            </div>
                            <div class="wr-table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>-</th>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Domínio</th>
                                            <th>Status</th>
                                            <th>Validade</th>
                                            <th>Criação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data)): ?>
                                        <?php foreach ($data as $d) : ?>
                                            <tr>
                                                <td><input type="checkbox" id=""></td> 
                                                <td><?= $d->temp_parceiro_id ?></td>
                                                <td><?= $d->titulo ?></td>
                                                <td><?= $d->dominio ?></td>
                                                <td><?= 'Em execuçaõ' ?></td>
                                                <td><?= '' ?></td>
                                                <td><?= $d->created_at ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7">Sem nenhum dado</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!--/.contain-pages-->
            </div>
        </div> <!--/.contain-wrapper-->

        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>


        <?= parts('nav.footer') ?>

    </div> <!-- sx-wrapper -->


</body>

</html>

<script src="<?= asset('js/choose/index.js') ?>"></script>
<script>
</script>