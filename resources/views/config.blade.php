@extends('layouts.app')
@section('title', 'Configurações')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Configurações</h3>
    <span>Configurações pessoais e gerais da aplicação</span>
</nav>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button
                    class="nav-link active"
                    id="nav-my-data-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#nav-my-data"
                    type="button"
                    role="tab"
                    aria-controls="nav-my-data"
                    aria-selected="true"
                >
                    Meus dados
                </button>
                <button
                    class="nav-link"
                    id="nav-app-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#nav-app"
                    type="button"
                    role="tab"
                    aria-controls="nav-app"
                    aria-selected="false"
                >
                    Aplicação
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-my-data" role="tabpanel" aria-labelledby="nav-my-data-tab">
                <div class="py-4">
                    <form id="form-config-my-data" method="POST">
                        @csrf

                        <div class="row mb-5">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Apelido*</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ session('user')['name'] }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email">E-mail*</label>
                                    <input class="form-control" type="email" name="email" id="email" value="{{ session('user')['email'] }}" />
                                </div>
                            </div>
                        </div>

                        <span>Para redefinir sua senha, por favor, informe a nova senha e em seguida confirme-a.</span>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mt-3">
                                    <label for="password">Nova senha</label>
                                    <input class="form-control" type="password" name="password" id="password" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-3">
                                    <label for="password_confirmation">Confirme a nova senha</label>
                                    <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button
                                type="button"
                                class="btn btn-primary"
                                id="save-my-data"
                            >
                                Atualizar configuração
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-app" role="tabpanel" aria-labelledby="nav-app-tab">
                <div class="py-4">
                    Em breve aqui, configurações gerais da aplicação...
                </div>
                {{-- <div class="py-4">
                    <form method="POST">
                        @csrf
                        @foreach ($configs as $config)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ $config->description }}</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="{{ $config->uuid_code }}"
                                            value="{{ $config->value }}"
                                        />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@endsection
