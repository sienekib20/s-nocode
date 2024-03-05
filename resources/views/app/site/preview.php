<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        ::-webkit-scrollbar {
            width: 1px;
        }
        ::-webkit-scrollbar-thumb {
            background-color: transparent;
        }
        iframe {
            width: 100%;
            height: 100%;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
    </style>
</head>
<body>
    <iframe src="<?= __template($red)  ?>" frameborder="0"></iframe>
    
</body>