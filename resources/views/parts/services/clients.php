<?php
$clients = [
    ['inter.jpg'],
    ['net.jpeg'],
    ['salvar.jpg'],
    ['tanque.jpg']
];
?>
<div class="card">
    <div class="card-top mb-4">
        <div class="container-sm">
            <div class="row">
                <div class="card-title col-12">
                    <h4 class="title d-block mt-5">Alguns clientes nossos</h4>
                    <small class="ff">Pessoas que já trabalham connosco. <br> Conquistamos a confiança dos demais clientes no mercado</small>
                </div>
            </div>
        </div>
    </div> <!--/.card-top-->
    <div class="card-body">
        <div class="container-sm">
            <div class="row">
                <?php foreach ($clients as $client) : ?>
                    <div class="col-sm-4 col-md-4 col-lg-2">
                        <img src="<?= asset('img/clients/'. $client[0]) ?>" alt="">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>