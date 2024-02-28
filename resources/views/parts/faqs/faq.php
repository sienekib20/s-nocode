<?php $asked = [
    ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="<?= route(\'contactos\') ?>">contacto</a>', 'active'],
    ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', ''],
    ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', ''],
    ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', ''],
    ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '']
];
?>
<div class="card pb-5" style="background-color: #f0f0f0ad;">
    <div class="card-top">
        <div class="container-sm">
            <div class="row">
                <div class="card-title text-center col-12">
                    <h4 class="title d-block mt-5">FAQ - Perguntas mais frequentes</h4>
                    <span class="text-muted ff">Sabemos o que os nossos clientes querem sempre saber. <br> Caso não tenha a tua dúvida <a href="<?= route('faqs') ?>">clique aqui</a> </span>
                </div>
            </div>
        </div>
    </div> <!--/.card-top-->

    <div class="card-body mt-5 mb-5">
        <div class="container-sm">
            <div class="row">
                <div class="col-0 col-lg-1"></div>
                <div class="col-12 col-lg-10">
                    <div class="faq">
                        <?php for ($i = 0; $i < count($asked); $i++) : ?>
                            <div class="faq-item">
                                <span class="count"><?= $i < 10 ? '0' . $i + 1 : $i + 1 ?></span>
                                <div class="contain">
                                    <div class="question">
                                        <span><?= $asked[$i][0] ?></span>
                                    </div>
                                    <p><?= nl2br($asked[$i][1]) ?></p>
                                </div>
                                <span class="ml-auto bi bi-plus"></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="col-0 col-lg-1 mb-4"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready((e) => {
        var container = document.querySelectorAll('.faq-item');
        container.forEach(item => {
            item.addEventListener('click', (e) => {
                item.classList.toggle('open');
            });
        });
    });
</script>