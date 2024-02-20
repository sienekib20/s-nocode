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


                <!-- / Navbar -->


                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="card bg-transparent shadow-none border-0 my-4">
                            <div class="card-body row p-0 pb-3">
                                <div class="col-12 col-md-8 card-separator">
                                    <h3>Seja bemvindo, <?= \Sienekib\Mehael\Support\Auth::user()->username ?> 👋🏻 </h3>
                                    <div class="col-12 col-lg-7">
                                        <p>Os seus websites estão progredindo bem. Vamos mostra-lhe pontos necessários de que precisas</p>
                                    </div>
                                    <div class="d-flex justify-content-between flex-wrap gap-3 me-5">
                                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                                            <span class=" bg-label-primary p-2 rounded">
                                                <i class='bx bx-laptop bx-sm'></i>
                                            </span>
                                            <div class="content-right">
                                                <p class="mb-0">Total de websites</p>
                                                <h4 class="text-primary mb-0">
                                                    <?= ($t = $templateUsuario->total) < 10 ? "0{$t}" : $t ?>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="bg-label-info p-2 rounded">
                                                <i class='bx bx-bulb bx-sm'></i>
                                            </span>
                                            <div class="content-right">
                                                <p class="mb-0">Campanhas</p>
                                                <h4 class="text-info mb-0">0%</h4>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="bg-label-warning p-2 rounded">
                                                <i class='bx bx-check-circle bx-sm'></i>
                                            </span>
                                            <div class="content-right">
                                                <p class="mb-0">Encomendas feitas</p>
                                                <h4 class="text-warning mb-0">0</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 ps-md-3 ps-lg-5 pt-3 pt-md-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div>
                                                <h5 class="mb-2">Tempo passados</h5>
                                                <p class="mb-4">Relatório semanal</p>
                                            </div>
                                            <div class="time-spending-chart">
                                                <h3 class="mb-2">231<span class="text-muted">h</span> 14<span class="text-muted">m</span> </h3>
                                                <span class="badge bg-label-success">+18.4%</span>
                                            </div>
                                        </div>
                                        <div id="leadsReportChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4 g-4 d-none">
                            <div class="col-12 col-xl-12">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Topic you are interested in</h5>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
                                                <a class="dropdown-item" href="javascript:void(0);">Highest Views</a>
                                                <a class="dropdown-item" href="javascript:void(0);">See All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body row g-3">
                                        <div class="col-md-6">
                                            <div id="horizontalBarChart"></div>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-around align-items-center">
                                            <div>
                                                <div class="d-flex align-items-baseline">
                                                    <span class="text-primary me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">UI Design</p>
                                                        <h5>35%</h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-baseline my-3">
                                                    <span class="text-success me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">Music</p>
                                                        <h5>14%</h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-baseline">
                                                    <span class="text-danger me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">React</p>
                                                        <h5>10%</h5>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="d-flex align-items-baseline">
                                                    <span class="text-info me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">UX Design</p>
                                                        <h5>20%</h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-baseline my-3">
                                                    <span class="text-secondary me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">Animation</p>
                                                        <h5>12%</h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-baseline">
                                                    <span class="text-warning me-2"><i class='bx bxs-circle'></i></span>
                                                    <div>
                                                        <p class="mb-2">SEO</p>
                                                        <h5>9%</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <?= parts('user.footer') ?>

                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->

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