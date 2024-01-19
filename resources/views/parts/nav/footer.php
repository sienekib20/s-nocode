<footer class="footer py-5">
    <div class="container-sm">
        <div class="row align-items-center mb-5">
            <div class="col-md-4 d-flex align-items-center">
                <div class="w-100">
                    <span class="d-block heading">Faça a tua encomenda</span>
                    <small class="subheading">Crie site personalizado</small>
                </div>
            </div>
            <div class="col-md-8 d-flex align-items-center">
                <form action="<?= route('encomendar') ?>" class="subscribe-form w-100 d-flex">
                    <input type="text" class="form-input w-100 input-warning" placeholder="Insira o teu email">
                    <button type="submit" class="btn form-input">Enviar</button>
                </form>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-md-4 col-lg-6 order-md-last">
                <div class="row justify-content-end">
                    <div class="col-md-12 col-lg-9 text-md-right mb-md-0 mb-4">
                        <span class="footer-heading"><a href="#" class="logo">Nocode</a></span>
                        <p class="copyright">Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script>
                            Todos os direitos reservados | Design feito com <i class="bi bi-heart-fill" aria-hidden="true"></i> por <a href="https://facebook.com/profile" target="_blank">sienekib</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-6">
                <div class="row">
                    <div class="col-md-6 mb-md-0 mb-4">
                        <span class="footer-heading d-block mb-3">Information</span>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Universo sílica</a></li>
                            <li><a href="#" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Preçário</a></li>
                            <li><a href="#" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Contactos</a></li>
                            <li><a href="#" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Suporte</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 mb-md-0 mb-4">
                        <span class="footer-heading d-block mb-3">Aplicativos</span>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-1 d-block"><span class="fas fa-checkmark-circle-outline mr-2"></span>Quem me levou</a></li>
                            <li><a href="#" class="py-1 d-block"><span class="fas fa-checkmark-circle-outline mr-2"></span>Bike Provider</a></li>
                            <li><a href="#" class="py-1 d-block"><span class="fas fa-checkmark-circle-outline mr-2"></span>How to Used</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer> <!--/.footer-->
