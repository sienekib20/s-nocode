<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/inter/inter.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/fonts/helvetica/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/alquimist.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dash.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/frequent.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/navbar.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <style>
        .alq-modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 105000001111111111111111111 !important;
            display: none;
            overflow: auto;
            outline: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .alq-modal.show {
            display: block;
        }

        .alq-modal-top {
            padding-bottom: 0.8rem;
            border-bottom: 1px solid transparent;
        }

        .alq-modal-contain {
            border-radius: 4px;
            padding: 1rem 0;
        }


        .alq-modal-dark .alq-modal-contain {
            background-color: #fff;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
        }

        .alq-modal-body {
            padding: 1.5rem 0 1rem;
        }
    </style>
</head>

<body>
    <input type="hidden" id="lead_response" value="<?= session()->get('response_save_lead') ?>">

    <div class="alq-modal show alq-modal-dark">
        <span class="d-flex my-5"></span>
        <span class="d-flex my-5"></span>
        <div class="alq-modal-contain col-11 col-sm-10 col-lg-8 mx-auto">
            <div class="alq-modal-top">
                <div class="container">
                    <div class="row no-spacing">
                        <div class="col-10">
                            <span class="card-heading">Servidor</span>
                            <small>
                                <?= session()->get('response_save_lead')  ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    setTimeout(function() {
        window.location.href = '/my/script';
    }, 2000);
</script>