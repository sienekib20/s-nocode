<?php $asked = [
    ['O que é preciso para começar?', 'Primeiro tens que ter uma conta no Sílica, depois escolha um plano (opcional), só assim que podes começar. para mais informações entre em <a href="<?= route(\'contactos\') ?>">contacto</a>', 'active', 'fa-minus'],
    ['O meu site pára de funcionar, quando expirar o plano?', 'Antes que expire o teu plano receberas notificações antes do tempo, mas damos sempre um prazo considerável de 3 mêses aos nossos clientes, excepto quando estiveres a usar um plano grátis.', '', 'fa-plus'],
    ['Devo ter conhecimentos tenicos para editar o template?', 'Pensamos em si, podes não ter conhecimentos tecnicos, vais construir o teu template mesmo que começar do zero.', '', 'fa-plus'],
    ['Com quem posso partilhar o meu dóminio?', 'Podes divulgar o link do teu site com quem quizer, onde quizer, e qualquer um poderá acessar livremente', '', 'fa-plus'],
    ['Existem template para o meu tipo de negócio?', 'Nós damos a base, o alicerce com que vais edificar a sua casa, isto é, os templates são de carater aberto para adequá-los ao teu tipo de negócio.', '', 'fa-plus']
];
?>

<div class="card py-5" style="background-color: #000; color: #fff">
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <h3 class="card-heading" style="color: #fff">Respostas de Perguntas <br> mais frequentes</h3>
                </div>
                <div class="col-lg-6 col-12 mt-lg-0 mt-5">
                    <?php foreach ($asked as  $value) : ?>
                    <div class="asks">
                        <div class="ask-header">
                            <span class="ask-header-title"><?= $value[0] ?></span>
                            <span class="ml-auto fas <?= $value[3] ?> ask-icon"></span>
                        </div>
                        <div class="ask-contain <?= $value[2] ?>">
                                <div class="d-flex ai-center">
                                    <?= $value[1] ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex my-5"></div>
    <div class="card-body my-4">
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <h3 class="card-heading" style="color: #fff">Oferecemos um suporte de 24h/24</h3>
                    <span>Estamos sempre disponíveis para responder as suas dúvidas e preocupações. Consulte o nosso call center</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <a href="" class="btn-call-center">
                        <span class="t">Escritório do Sílica</span>
                        <span>Fale com o pessoal do outro lado</span>
                        <span class="bi bi-arrow-right"></span>
                    </a>
                </div>
                <div class="col-lg-6 col-12">
                    <a href="" class="btn-call-center">
                        <span class="t">Visite o nosso site</span>
                        <span>Descobra ainda mais sobre nós</span>
                        <span class="bi bi-arrow-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex my-1"></div>
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