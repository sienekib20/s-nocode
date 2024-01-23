<footer class="footer py-5">
    <div class="container-sm pt-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-4 d-flex align-items-center">
                <div class="w-100">
                    <small class="text-muted">Obtenha uma marca própria e única</small>
                    <span class="d-block heading">Encomenda site personalizado</span>
                </div>
            </div>
            <div class="col-md-8 d-flex align-items-center">
                <form action="<?= route('encomendar') ?>" class="subscribe-form w-100 d-flex">
                    <input type="text" class="form-input w-100 input-warning" placeholder="Insira o teu email" required>
                    <button type="submit" class="btn btn-orange form-input ml-2" style="border-color: transparent;">Enviar</button>
                </form>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-md-4 col-lg-6 order-xxs-last">
                <div class="row justify-content-end">
                    <div class="col-md-12 col-lg-9 text-md-right mb-md-0 mb-4">
                        <span class="footer-heading"><a href="#" class="logo" style="color: #f71">Sílica Nocode</a></span>
                        <small class="text-muted d-block mb-4">Uma ferramenta nativa do <a href="<?= 'https://silicaweb.ao/sfront' ?>" target="_blank">Sílica</a></small>
                        <p class="copyright">Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            Todos os direitos reservados | Design feito com <i class="bi bi-heart-fill" aria-hidden="true"></i> por <a href="https://www.facebook.com/profile.php?id=100091417419139" target="_blank" class="por">sienekib</a> 
                            <!-- ao clicar no  -->
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-6">
                <div class="row">
                    <div class="col-md-6 mb-md-0 mb-4">
                        <span class="footer-heading d-block mb-3">Informação</span>
                        <ul class="list-unstyled">
                            <li><a href="<?= 'https://silicaweb.ao/sfront' ?>" target="_blank" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Universo sílica</a></li>
                            <li><a href="<?= route('planos') ?>" class="py-1 d-block"><span class="ion-ios-checkmark-circle-outline mr-2"></span>Preçário</a></li>
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