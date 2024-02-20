@font-face {
    font-family: 'MinhaFonte';
    src: url('minha-fonte.woff2') format('woff2');
    unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}

<!DOCTYPE html>

<html lang="en" class="light-style layout-compact layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default">

<head>
    <?= parts('user.header') ?>
</head>

<style>
    .toggle-play-pause small {
        pointer-events: none;
    }

    .toggle-play-pause .fas.fa-play {
        color: green;
    }

    .toggle-play-pause .fas.fa-pause {
        /*color: brown;*/
        color: #cc0;
    }
</style>

<body>

    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe>
    </noscript>



    <!-- Layout Content -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <?= parts('user.aside') ?>


            <!-- Layout page -->
            <div class="layout-page">
                <?= parts('user.topnav') ?>

                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">


                        <div class="row g-4 mb-4">
                            <div class="col-sm-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="content-left">
                                                <span>Websites ativos</span>
                                                <div class="d-flex align-items-end mt-2">
                                                    <h4 class="mb-0 me-2">01</h4>
                                                </div>
                                            </div>
                                            <div class="avatar">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    <i class="bx bx-layout bx-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="content-left">
                                                <span>Clientes visitando</span>
                                                <div class="d-flex align-items-end mt-2">
                                                    <h4 class="mb-0 me-2">00</h4>
                                                    <small class="text-success">(+0%)</small>
                                                </div>
                                            </div>
                                            <div class="avatar">
                                                <span class="avatar-initial rounded bg-label-danger">
                                                    <i class="bx bx-user-check bx-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Users List Table -->
                        <div class="card">
                            <div class="py-2"></div>
                            <div class="card-datatable table-responsive px-2">
                                <table class="datatables-users table-striped table border">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Template</th>
                                            <th>Domínio</th>
                                            <th>Validade</th>
                                            <th>Em execução</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $key => $d) : ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $d->titulo ?></td>
                                                <td><?= $d->dominio ?></td>
                                                <td><?= $d->prazo ?></td>
                                                <td><span class="d-flex align-items-baseline gap-4">
                                                        <div>Sim</div>
                                                        <a href="" class="toggle-play-pause"><small class="fas fa-play"></small></a>
                                                    </span></td>
                                                <td>Template pago</td>
                                                <td><span class="d-flex align-items-center gap-2">
                                                        <a href=""><small class="fas fa-edit"></small></a>
                                                        <a href=""><small class="fas fa-trash"></small></a>
                                                        <a href="<?= route('silica', $d->dominio) ?>" target="_blank"><small class="fas fa-eye"></small></a>
                                                    </span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <!-- / Content -->

                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->


                <div class="drag-target"></div>
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <!--/ Layout Content -->

    <?= parts('user.bottom') ?>

    <script>
        try {
            const tpp = document.querySelectorAll('.toggle-play-pause');
            tpp.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    var child = e.target.querySelector('small');
                    child.className = child.className == 'fas fa-play' ? 'fas fa-pause' : 'fas fa-play'
                });
            })

        } catch (err) {}
    </script>

</body>

</html>