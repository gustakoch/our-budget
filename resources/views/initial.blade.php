@extends('layouts.app')
@section('title', 'Configurações iniciais')

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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Como gostaria de ser chamado?*</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ session('user')['name'] }}" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">Seu e-mail*</label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ session('user')['email'] }}" />
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="form-group mt-3">
                        <label for="password">Sua nova senha*</label>
                        <input class="form-control" type="password" name="password" id="password" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mt-3">
                        <label for="password_confirmation">Confirme a nova senha*</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" />
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <small>(* Dados obrigatórios)</small>
            </div>

            <div class="d-flex mt-3">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="first-access"
                >
                    Salvar dados
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
