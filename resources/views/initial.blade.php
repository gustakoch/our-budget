@extends('layouts.app')
@section('title', 'Configurações')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Configurações iniciais</h3>
    <span>Verifique seus dados abaixo e clique em "Confirmar" para salvar as alterações.</span>
</nav>


<div class="card">
    <div class="card-body">
        <form id="form-first-access" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nome para exibição*</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ session('user')['name'] }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">E-mail de cadastro*</label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ session('user')['email'] }}" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mt-3">
                        <label for="password">Sua nova senha*</label>
                        <input class="form-control" type="password" name="password" id="password" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-3">
                        <label for="password_confirmation">Confirme a nova senha*</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" />
                    </div>
                </div>
            </div>

            <div class="form-group mt-5">
                <label>Grade de despesas*</label>
                <div class="gradle-expenses">
                    <div class="gradle-expenses-item">
                        <input class="form-check-input" type="radio" name="gradle_format" id="gradle_format_15_30" value="15/30">
                        <label class="form-check-label mb-3" for="gradle_format_15_30">Grade com divisão de 15/30 dias</label>
                        <img src="{{ asset('/images/15-30dias.png') }}" alt="Grade com divisão de 15/30 dias">
                    </div>
                    <div class="gradle-expenses-item">
                        <input class="form-check-input" type="radio" name="gradle_format" id="gradle_format_30" value="30">
                        <label class="form-check-label mb-3" for="gradle_format_30">Grade de 30 dias</label>
                        <img src="{{ asset('/images/30dias.png') }}" alt="Grade de 30 dias">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <button
                    type="button"
                    class="btn"
                    style="background-color: #7386D5; color: #fff"
                    id="first-access"
                >
                    Confirmar dados
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
