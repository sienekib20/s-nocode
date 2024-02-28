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
        <?= parts('nav.header-sm') ?>

        <div class="card">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-0 col-lg-1"></div>
                        <div class="card-title col-12 col-lg-10">
                            <h4 class="title d-block mt-5">FAQ - Perguntas mais frequentes</h4>
                            <span class="text-muted ff">Sabemos o que os nossos clientes querem sempre saber <br> Fazendo essa pesquisa, podes encontrar a solucão ao problema que te encomoda.</span>

                            <span class="d-block mt-5 form-label">Pesquise alguma pergunta de que tenha dúvida</span>
                            <form action="" id="formTrigger" class="w-100 w-lg-80">
                                <input type="text" name="itemFormTrigger" class="form-input mt-2" placeholder="ex. Existem template para o meu tipo de negócio?" id="typeFrequenteQuestion">
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->

            <div class="card-body mt-5">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-0 col-lg-1"></div>
                        <div class="col-12 col-lg-10" id="frequentQuestionContainer">
                            <div class="faq" id="frequentQuestionItems">
                                <?php foreach ($asked as $faq) : ?>
                                    <div class="faq-item">
                                        <span class="count"><?= $faq->faq_id < 10 ? '0' . $faq->faq_id : $faq->faq_id ?></span>
                                        <div class="contain">
                                            <div class="question">
                                                <span><?= $faq->pergunta ?></span>
                                            </div>
                                            <p><?= nl2br($faq->resposta) ?></p>
                                        </div>
                                        <span class="ml-auto bi bi-plus"></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-0 col-lg-1"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card ">
            <div class="card-top">
                <div class="container-sm">
                    <div class="row">
                        <div class="col-0 col-lg-1"></div>
                        <div class="card-title col-12 col-lg-10">
                            <h4 class="title d-block mt-5">Não achou a tua solução?</h4>
                            <span class="text-muted ff">Se não consegue achar a resposta da tua pergunta nos FAQs, <br> podes nos contactar sempre. Deixe aqui a sua pergunta, e vamos responder</span>

                            <form action="<?= route('purpose') ?>" method="post" class="col-12 col-lg-10 px-0 mt-5">
                                <div class="input-group">
                                    <input type="text" name="pergunta" class="form-input" placeholder="Menciona aqui a tua pergunta">
                                </div>
                                <div class="input-group mt-3">
                                    <textarea name="descricao" class="form-input" cols="30" rows="5" placeholder="Descreva um pouco a tua pergunta"></textarea>
                                </div>
                                <div class="input-group mt-3">
                                    <button type="submit" class="btn btn-outline-orange d-block w-100">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!--/.card-top-->
        </div>


        <small class="d-flex my-5"></small>
        <small class="d-flex my-5"></small>

        <?= parts('nav.footer') ?>

    </div> <!--/.wrapper-->


</body>
<input type="hidden" id="faq-get" value="<?= route('faqs-get') ?>">
<input type="hidden" class="session_message" value="<?= session()->getFlashMessage('purpose') ?? '' ?>">

</html>

<script src="<?= asset('js/ui/kib-ui.js') ?>"></script>
<script>
    $(document).ready(() => {
        if ($('.session_message').val() != '') {
            $('.cool-alert').addClass('active');
            $('.cool-alert-title').text('Aviso');
            $('.cool-alert-text').text($('.session_message').val());
        }
        $('.bg-secondary').css('backgroundColor', '#f1f1f1');
    });

    var frequentQuestionContainer = $('#frequentQuestionContainer');
    frequentQuestionContainer.css('min-height', frequentQuestionContainer.height());
    $('#frequentQuestionItems').css('height', '100%');
    // Adiciona um event listener ao elemento pai existente
    $('#frequentQuestionItems').on('click', '.faq-item', (e) => {
        // Captura o elemento clicado
        var item = e.currentTarget;
        // Adiciona ou remove a classe 'open' no elemento clicado
        item.classList.toggle('open');
    });

    $(document).ready(() => {
        $('#typeFrequenteQuestion').on('input', (e) => {
            const formData = new FormData(document.getElementById('formTrigger'));
            const xhr = new XMLHttpRequest();
            const url = 'http://localhost:8000' + $('#faq-get').val();

            for (const a of formData.entries()) {
                console.log(a);
            }

            xhr.open('POST', url);
            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    $('#frequentQuestionItems').html('');
                    $.each(response, (key, value) => {
                        var item = faqQuestionModelItem(value.pergunta, value.resposta, +value.faq_id);
                        $('#frequentQuestionItems').append(item);
                    });
                } else {
                    var error = xhr.statusText;
                    console.error('Erro:', error);
                }
            };
            xhr.onerror = () => {
                var error = xhr.statusText;
                console.error('Erro:', error);
            };
            xhr.send(formData);
        });
    });


    $(document).ready((e) => {
        var container = document.querySelectorAll('.faq-item');
        container.forEach(item => {
            item.addEventListener('click', (e) => {
                item.classList.toggle('open');
            });
        });
    });

    function faqQuestionModelItem(pergunta, resposta, id) {
        var id = (id < 10) ? '0' + id : id.toString();
        var model = '';
        model += '<div class="faq-item">';
        model += '<span class="count">' + id + '</span>';
        model += '<div class="contain">';
        model += '<div class="question">';
        model += `<span>${pergunta}</span>`;
        model += '</div>';
        model += `<p>${resposta}</p>`;
        model += '</div>';
        model += '<span class="ml-auto bi bi-plus"></span>';
        model += '</div>';
        return model;
    }
</script>