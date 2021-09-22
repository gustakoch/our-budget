<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('/images/budget-logo.png') }}" />

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <title>Login | Our Budget</title>
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
            <div class="col-lg-7 text-center text-lg-start">
                <h1 class="display-4 fw-bold lh-1 mb-3">
                    <img src="{{ asset('images/budget-logo.png') }}" alt="Logo">
                    Our Budget
                </h1>
                <p class="col-lg-10 fs-4">Uma aplicação que dispõe de tudo o que você precisa para o seu planejamento e controle financeiro.</p>
            </div>
            <div class="col-md-10 mx-auto col-lg-5">
                <form id="form-login" class="p-4 p-md-5 border rounded-3 bg-light">
                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control"
                            id="floatingInput"
                            name="email"
                            placeholder="nome@exemplo.com.br"
                            value="<?php if (isset($_COOKIE['email'])) { echo $_COOKIE['email']; } ?>"
                        >
                        <label for="floatingInput">E-mail</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="floatingPassword"
                            name="password"
                            placeholder="Senha"
                            value="<?php if (isset($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>"
                        >
                        <label for="floatingPassword">Senha</label>
                    </div>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" name="remember" value="1" <?php if (isset($_COOKIE['password'])) { echo 'checked="checked"'; } ?>> Lembrar-me
                        </label>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary login-button" type="button">Login</button>
                    <hr class="my-4">
                    <small class="text-muted d-block text-center">Em breve você poderá criar sua conta para utilizar o serviço. Fique ligado!</small>
                </form>
            </div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function() {
            function getAppUrl() {
                let appUrl

                jQuery.ajax({
                    url: 'app/url',
                    type: 'get',
                    dataType: 'json',
                    timeout: 20000,
                    async: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(response) {
                        appUrl = response.appUrl
                    }
                })

                return appUrl
            }

            jQuery(document).on('click', '.login-button', function() {
                let email = jQuery('input[name="email"]').val()
                let password = jQuery('input[name="password"]').val()

                let button = jQuery(this)

                if (!email || !password) {
                    Swal.fire({
                        title: 'Houve um erro',
                        text: 'Precisamos que nos informe o seu e-mail e senha para acessar o sistema.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Tentar novamente'
                    })
                    return false
                }

                jQuery.ajax({
                    url: 'login',
                    type: 'post',
                    dataType: 'json',
                    timeout: 20000,
                    data: jQuery('#form-login').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    beforeSend: function() {
                        jQuery(button).html(`
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Acessando sistema...
                        `)
                        jQuery(button).attr('disabled', true)
                    },
                    error: function(xhr, status, error) {
                        swalNotification('Houve um erro', `${status} ${error}`, 'error', 'Tentar novamente')
                        jQuery(button).html('Login')
                        jQuery(button).attr('disabled', false)
                    },
                    success: function(response) {
                        if (!response.ok) {
                            jQuery(button).html('Login')
                            jQuery(button).attr('disabled', false)

                            Swal.fire({
                                title: 'Houve um erro',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Tentar novamente'
                            })
                        } else {
                            let appUrl = getAppUrl()

                            if (response.dashboard) {
                                window.location.href = `${appUrl}/dashboard`
                            } else {
                                window.location.href = `${appUrl}/initial`
                            }
                        }
                    }
                })
            })
        })
    </script>
</body>
</html>
