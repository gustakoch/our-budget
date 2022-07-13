<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('/images/budget-logo.png') }}" />

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Nova conta | Our Budget</title>
    <style>
        .body-bg {
            background: rgb(222,227,99);
            background: linear-gradient(0deg, rgba(222,227,99,0.6530987394957983) 0%, rgba(255,255,255,1) 100%);
        }
    </style>
</head>
<body class="body-bg">
    <div class="container col-xl-10 col-xxl-8 px-4 py-5 d-flex justify-content-center align-items-center vh-100">
        <div class="row align-items-center g-lg-5 py-5">
            <div class="col-md-10 mx-auto col-lg-5">
                <form id="form-register" class="p-4 p-md-5 border rounded-3 bg-light">
                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control"
                            id="floatingName"
                            name="name"
                            placeholder="Maria das Graças"
                        >
                        <label for="floatingName">Nome completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control"
                            id="floatingInput"
                            name="email"
                            placeholder="nome@dominio.com.br"
                        >
                        <label for="floatingInput">Seu melhor e-mail</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="floatingPassword"
                            name="password"
                            placeholder="Senha"
                        >
                        <label for="floatingPassword">Senha secreta</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="floatingPassword"
                            name="password_confirmation"
                            placeholder="Repita a senha"
                        >
                        <label for="floatingPassword">Repita a senha secreta</label>
                    </div>

                    <button class="w-100 btn btn-lg btn-success register-button" type="button">Registrar</button>
                    <hr class="my-4">
                    <small class="text-muted d-block text-center">Já possui uma conta? <a href="{{ route('home') }}">Faça login!</a></small>
                </form>
            </div>
            <div class="col-lg-7 text-center text-lg-end">
                <h1 class="display-4 fw-bold lh-1 mb-3">
                    <img src="{{ asset('images/budget-logo.png') }}" alt="Logo">
                    Our Budget
                </h1>
                <p class="col-lg-12 fs-4">Crie sua conta gratuita agora mesmo! É rápido, simples e seguro. Valide seu e-mail para ter acesso a ferramenta.</p>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery(document).on('click', '.register-button', function() {
                let name = jQuery('input[name="name"]').val()
                let email = jQuery('input[name="email"]').val()
                let password = jQuery('input[name="password"]').val()
                let passwordConfirmation = jQuery('input[name="password_confirmation"]').val()

                let button = jQuery(this)

                if (!name || !email || !password || !passwordConfirmation) {
                    Swal.fire({
                        title: 'Houve um erro',
                        text: 'Precisamos que todos os campos sejam preenchidos para realizar o cadastro.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tentar novamente'
                    })
                    return false
                }

                if (password != passwordConfirmation) {
                    Swal.fire({
                        title: 'Houve um erro',
                        text: 'As senhas informadas não conferem. Por favor, tente novamente.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tentar novamente'
                    })
                    return false
                }

                jQuery.ajax({
                    url: 'register/new',
                    type: 'post',
                    dataType: 'json',
                    timeout: 20000,
                    data: jQuery('#form-register').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    beforeSend: function() {
                        jQuery(button).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Registrando usuário...
                        `)
                        jQuery(button).attr('disabled', true)
                    },
                    error: function(xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                        jQuery(button).html('Registrar')
                        jQuery(button).attr('disabled', false)
                    },
                    success: function(response) {
                        if (!response.ok) {
                            jQuery(button).html('Registrar')
                            jQuery(button).attr('disabled', false)

                            Swal.fire({
                                title: 'Houve um erro',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Tentar novamente'
                            })
                        } else {
                            jQuery(button).html('<i class="fas fa-check-circle"></i> Usuário registrado')

                            Swal.fire({
                                title: 'Registro efetuado!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Fechar'
                            })
                        }
                    }
                })
            })
        })
    </script>
</body>
</html>
