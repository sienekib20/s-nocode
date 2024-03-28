<div class="wr-auth-overlay"></div>
<aside class="wr-auth-aside" name="__login__auth__">
    <div class="container w-90">
        <span class="d-flex my-4"></span>
        <div class="wr-auth-header text-white">
            <div class="row">
                <div class="col-10">
                    <span class="card-heading">Entrar</span>
                    <small class="text-muted d-block">Entre com a sua conta</small>
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
            <form id="auth-login-form" class="row">
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="text" name="username" class="form-input input-orange" placeholder="E-mail">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <input type="password" name="password" class="form-input input-orange" placeholder="Senha">
                        <small class="input-error"></small>
                    </div>
                </div>
                <div class="col-12 mx-auto">
                    <div class="input-group">
                        <button type="submit" class="btn btn-block btn-orange">Entrar</button>
                    </div>
                </div>
                <div class="col-12 mx-auto my-3">
                    <div class="input-group flex-column">
                        <small class="text-muted">Não tens conta no universo sílica?</small>
                        <a href="https://silicaweb.ao/sfront/accountnew/" target="_blank" class="text-white text-underline">Crie uma agora</a>
                    </div>
                </div>
            </form>
        </div>
    </div>





</aside>

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
    });

    function __close__all() {
        $('body').css('overflow', 'auto');
        $('.wr-auth-aside').removeClass('active');
        $('.wr-auth-overlay').removeClass('active');
    }
</script>