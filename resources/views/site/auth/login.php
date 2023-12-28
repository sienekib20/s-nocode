<!DOCTYPE html>
<html lang="en">

<head>

    <title>%title%</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= asset('css/my-css.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/my-media.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/bootstrap-icons.css') ?>">

</head>

<body>
    <?= parts('labs.loader') ?>
    <div class="sx">
        <div class="wallpaper no-top">
            <div class="sx-container container-auth __login">
                <div class="sx-col">
                    <span class="bold title">Inicie a sessão <br> Para começar a caminhada</span>
                    <small class="td-muted">Conheça a nova ferramenta dos Sílica, um criador de landing pages sem necessidade de mexer no código, apenas com um click e já está!</small>
                    <div class="actions d-flex">
                        <a href="" class="auth-change">Cadastra-se</a>
                        <a href="<?= route('/') ?>">Voltar no site</a>
                    </div>
                </div>
                <div class="sx-col">
                    <form action="<?= route('') ?>" method="POST">
                        <div class="auth-group">
                            <input type="text" class="auth-input" placeholder="Usuário">
                            <small class="fas fa-user"></small>
                        </div>
                        <div class="auth-group">
                            <input type="text" class="auth-input" placeholder="Senha">
                            <small class="fas fa-lock"></small>
                        </div>
                        <div class="auth-group">
                            <button type="submit">Entrar</button>
                        </div>
                        <div class="auth-group">
                            <small class="tw-muted">Esqueceu a senha?</small>
                            <a href="<?= route('/') ?>">Recupere aqui</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="sx-container container-auth __register">
                <div class="sx-col">
                    <span class="bold title">Cadastra-se na plataforma <br> E crie a sua lógica de negócios</span>
                    <small class="td-muted">Conheça a nova ferramenta dos Sílica, um criador de landing pages sem necessidade de mexer no código, apenas com um click e já está!</small>
                    <div class="actions d-flex">
                        <a href="" class="auth-change">Entrar</a>
                        <a href="<?= route('/') ?>">Voltar no site</a>
                    </div>
                </div>
                <div class="sx-col">
                    <form action="<?= route('') ?>" method="POST">
                        <div class="auth-group">
                            <select class="account" id="">
                                <option value="new_account">Criar uma nova conta</option>
                                <option value="sil_account">Usar a minha conta do Sílica</option>
                            </select>
                            <input type="hidden" name="tipo_conta" id="tipo_conta" value="Nova">
                        </div>
                        <div class="sx-card-section"></div>
                        <div class="__silica">
                            <div class="auth-group">
                                <input type="text" class="auth-input" placeholder="Email">
                                <small class="fas fa-envelope"></small>
                            </div>
                            <div class="auth-group">
                                <input type="text" class="auth-input" placeholder="Código de confirmação">
                                <small class="fas fa-key"></small>
                            </div>
                        </div>
                        <div class="__group">
                            <div class="auth-group">
                                <input type="text" class="auth-input" placeholder="Nome & apelido">
                                <small class="fas fa-user"></small>
                            </div>
                            <div class="auth-group">
                                <select class="type-register" id="">
                                    <option value="email">Cadastrar com o endereço email</option>
                                    <option value="phone">Cadastrar com o número de telefone</option>
                                </select>
                            </div>
                            <div class="auth-group __email">
                                <input type="text" class="auth-input" placeholder="Email">
                                <small class="fas fa-envelope"></small>
                            </div>
                            <div class="auth-group __phone">
                                <input type="text" class="auth-input" placeholder="Telefone">
                                <small class="fas fa-phone"></small>
                            </div>
                            <div class="auth-group">
                                <input type="text" class="auth-input" placeholder="Senha">
                                <small class="fas fa-lock"></small>
                            </div>
                        </div>
                        <div class="sx-card-section"></div>
                        <div class="auth-group">
                            <button type="submit">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- wallpaper -->

    </div> <!-- sx-wrapper -->



</body>

</html>

<script>
    const auth_change = document.querySelectorAll('.auth-change');
    const auth_containers = document.querySelectorAll('.container-auth');
    auth_change.forEach((auth, key) => {
        auth.addEventListener('click', (e) => {
            e.preventDefault();
            if (key == 0) {
                auth_containers[0].style.top = '-100%';
                auth_containers[1].style.top = '0%';
            } else {
                auth_containers[0].style.top = '0%';
                auth_containers[1].style.top = '100%';
            }
        });
    });

    const type = document.querySelector('.type-register');
    const __email = document.querySelector('.__email');
    const __phone = document.querySelector('.__phone');

    type.addEventListener('change', (e) => {
        if (e.target.value == 'email') {
            __email.style.display = 'block';
            __phone.style.display = 'none';
        }

        if (e.target.value == 'phone') {
            __email.style.display = 'none';
            __phone.style.display = 'block';
        }
    });

    const s_account = document.querySelector('.__silica');
    const n_account = document.querySelector('.__group');
    document.querySelector('.account').addEventListener('change', (e) => {
        if (e.target.value == 'new_account') {
            n_account.style.display = 'block';
            s_account.style.display = 'none';
            document.querySelector('#tipo_conta').value = 'Nova';
        }

        if (e.target.value == 'sil_account') {
            n_account.style.display = 'none';
            s_account.style.display = 'block';
            document.querySelector('#tipo_conta').value = 'Sílica';
        }

        console.log(document.querySelector('#tipo_conta').value);
    });
</script>