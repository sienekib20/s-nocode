<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web editor</title>
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <link rel="stylesheet" href="<?= asset('lib/nc/bs/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/lib/wp/grapes.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('lib/wp/grapesjs-preset-webpage.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/own-editor.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
</head>

<body>

    <div id="navbar" class="sidenav d-flex flex-column overflow-scroll">
        <nav class="navbar navbar-light px-2">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3 logo">Sílica Page Editor</span>
            </div>
        </nav>
        <div class="my-2 d-flex flex-column px-2">
            <div class="df-e">
                <button type="button" class="btn btn-outline-warning" title="adicionar pagina">
                    <i class="bi bi-file-earmark-plus"></i>
                </button>
                <button type="button" class="btn btn-outline-warning" title="salvar">
                    <i class="bi bi-save"></i>
                </button>
            </div>
            <ul class="list-group pages mt-3">
                <li class="list-group-item py-2 d-flex justify-content-between">
                    Home
                    <div class="mx-2">
                        <i class="bi bi-pencil-fill"></i>
                        <i class="bi bi-trash"></i>
                    </div>
                </li>
            </ul>
        </div>
        <div class="px-2">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="block-tab" data-bs-toggle="tab" data-bs-target="#block" aria-selected="true" aria-controls="block">
                        <i class="bi bi-grid-fill"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="trait-tab" data-bs-toggle="tab" data-bs-target="#trait" aria-selected="true" aria-controls="trait">
                        <i class="bi bi-layers-fill"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="style-tab" data-bs-toggle="tab" data-bs-target="#style" aria-selected="true" aria-controls="style">
                        <i class="bi bi-palette-fill"></i>
                    </button>
                </li>
            </ul>
            <div class="tab-content overflow-scroll">
                <div id="block" class="tab-pane fade show active" role="tabpanel" aria-labelledby="block-tab">
                    <div id="blocks"></div>
                </div>
                <div id="trait" class="tab-pane fade show" role="tabpanel" aria-labelledby="block-tab">
                    <div id="layer-container"></div>
                </div>
                <div id="style" class="tab-pane fade show" role="tabpanel" aria-labelledby="block-tab">
                    <div id="style-view"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <nav class="navbar navbar-fixed navbar-dark">
            <div class="container-fluid">
                <div class="panel__devices"></div>
                <div class="panel__basic-actions"></div>
            </div>
        </nav>
        <!-- own-editor -->
        <div id="own-editor">
            <?= $indexContent ?>
        </div> <!--/.own-editor-->
    </div>


</body>

</html>


<script src="<?= asset('lib/nc/bs/bootstrap.min.js') ?>"></script>
<script src="<?= asset('lib/wp/grapes.min.js') ?>"></script>
<script src="<?= asset('lib/wp/grapesjs-preset-webpage.js') ?>"></script>
<script src="<?= asset('lib/wp/grapesjs-blocks-basic.js') ?>"></script>

<script src="<?= asset('js/own-editor.js') ?>"></script>