<div class="wr-auth-overlay"></div>
<aside class="wr-auth-aside" name="__register__auth__">
    <div class="container w-90">
        <span class="d-flex my-4"></span>
        <div class="wr-auth-header text-white">
            <div class="row">
                <div class="col-10">
                    <span class="card-heading">Registro</span>
                    <small class="text-muted d-block">Crie uma nova conta</small>
                </div>
                <div class="col-2">
                    <div class="card-heading"></div>
                    <a href="" class="wr-auth-close text-white">
                        <span class="bi bi-arrow-left"></span>
                    </a>
                </div>
            </div>
        </div>
        <span class="d-flex my-4"></span>
        <div class="wr-auth-body mt-4">
            <form id="auth-register-form" class="row">
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="text" name="nome" class="form-input input-orange" placeholder="Nome & Apelido">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="text" name="telefone" id="input-phone" class="form-input input-orange" placeholder="Telefone">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="email" name="email" id="input-mail" class="form-input input-orange" placeholder="Endereço e-mail">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="password" name="password" class="form-input input-orange" placeholder="Senha">
                        
                        <input type="password" name="re_password" class="form-input input-orange ml-2" placeholder="Confimar Senha">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <button type="submit" class="btn btn-block btn-orange">Registar</button>
                    </div>
                </div>
                <div class="col-12 mx-auto my-3">
                    <div class="input-group flex-column">
                        <small class="text-muted">Se já tens uma conta</small>
                        <a href="https://silicaweb.ao/sfront/accountnew/" target="_blank" class="text-white text-underline" name="call-account-log">Entrar agora</a>
                    </div>
                </div>
            </form>
        </div>
    </div>





</aside>
<script src="<?= asset('js/auths/index.js') ?>"></script>
<script src="<?= asset('js/ui/kib-ui.js') ?>"></script>
<script>
    $(document).ready(function(e) {
        $('.wr-auth-close').click(function(e) {
            e.preventDefault();
            __close__all();
        });

        $('.wr-auth-overlay').click(function(e) {
            e.preventDefault();
            __close__all();
        });

        $('[name="call-account-log"]').click(function(e) {
            e.preventDefault(); // Isso é por enquanto, depois vamos ter que redirecionar para url do sílica
            __close__all();
        });
    });

    $(document).ready(function(e) {
        $('#auth-register-form').submit(function(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            $.ajax({
                url: '/register',
                method: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (!result.response == '1') {
                        setTimeout(() => {
                            $('.loading').addClass('hide');
                            make_alert(result.response);
                        }, 1000);
                        return;
                    }
                    setTimeout(() => {
                        window.location.href = '/entrar';
                    }, 1000);
                },
                error: function(err) {
                    $('.loading').addClass('hide');
                    console.log(err);
                }
            });
        });
    });
</script>