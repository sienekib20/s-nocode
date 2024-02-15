<!DOCTYPE html>

<html lang="en" class="light-style layout-compact layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <?= parts('user.header') ?>
</head>

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
                                                    <h4 class="mb-0 me-2">04</h4>
                                                    <small class="text-success">(+10%)</small>
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
                            <div class="card-datatable table-responsive">
                                <table class="datatables-users table border-top">
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
                                        <tr>
                                            <td>1</td>
                                            <td>Landing page</td>
                                            <td>xsn.silica.ao</td>
                                            <td>Restam 10 dias</td>
                                            <td>Sim</td>
                                            <td>Template pago</td>
                                            <td><span>
                                                <a href=""><small class="fas fa-edit"></small></a>
                                                <a href=""><small class="fas fa-trash"></small></a>
                                                <a href=""><small class="fas fa-eye"></small></a>
                                                <a href=""><small class="fas fa-arrow-down"></small></a>
                                            </span></td>
                                        </tr>
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


</body>

</html>