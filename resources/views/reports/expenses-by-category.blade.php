@extends('layouts.app')
@section('title', 'Relatórios')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Relatórios</h3>
    <span>Relatório comparativo de saídas por categoria.</span>
</nav>

<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button
                class="accordion-button"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseOne"
                aria-expanded="true"
                aria-controls="collapseOne"
                id="btn-accordion-search"
            >
                <i class="fas fa-filter"></i>&nbsp; <strong>Filtros</strong> &nbsp;
                <small style="font-size: 12px;">(clique aqui para selecionar os filtros)</small>
            </button>
        </h2>
        <div
            id="collapseOne"
            class="accordion-collapse collapse show"
            aria-labelledby="headingOne"
            data-bs-parent="#accordionExample"
        >
            <div class="accordion-body">
                <form id="form-search-expenses-by-categories">
                    @csrf

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                @if (count($categories) > 0)
                                    <label for="category" class="form-label">Categoria(s):</label>
                                    <small>(Mantenha pressionado CTRL para selecionar várias categorias)</small>
                                    <select multiple class="form-select" name="category[]" id="category" style="height: 410px;">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->description }} <small>{{ $category->active_description }}</small>
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span>Não foram encontradas categorias.</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @if (count($months) > 0)
                                    <label for="start_month" class="form-label">Mês início:</label>
                                    <select class="form-select" name="start_month" id="start_month">
                                        @foreach ($months as $i => $month)
                                            <option
                                                value="{{ $i }}"
                                                @if ($i == 1) {{ 'selected' }} @endif
                                            >
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span>Não foram encontrados registros de mêses.</span>
                                @endif
                            </div>
                            <div class="form-group mt-3">
                                @if (count($months) > 0)
                                    <label for="end_month" class="form-label">Mês fim:</label>
                                    <select class="form-select" name="end_month" id="end_month">
                                        @foreach ($months as $i => $month)
                                            <option
                                                value="{{ $i }}"
                                                @if ($i == $actualMonth) {{ 'selected' }} @endif
                                            >
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span>Não foram encontrados registros de mêses.</span>
                                @endif
                            </div>
                            <div class="form-group mt-3">
                                @if (count($users) > 0)
                                    @if (session('user')['role'] == 3)
                                        <label for="user" class="form-label">Usuário cadastrado:</label>
                                        <input type="hidden" name="user" value="{{ session('user')['id'] }}" />
                                        <select class="form-select" name="user[]" id="user" disabled>
                                            <option selected value="{{ session('user')['id'] }}">
                                                {{ session('user')['name'] }} (Eu)
                                            </option>
                                        </select>
                                    @else
                                        <label for="user" class="form-label">Usuário(s) cadastrado(s):</label>
                                        <select
                                            multiple
                                            class="form-select"
                                            name="user[]"
                                            id="user"
                                            style="height: 240px;"
                                        >
                                            @foreach ($users as $user)
                                                <option
                                                    value="{{ $user->id }}"
                                                    @if ($user->id == session('user')['id']) {{ 'selected' }} @endif
                                                >
                                                    {{ $user->name }}
                                                    @if ($user->id == session('user')['id']) {{ '(Eu)' }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                @else
                                    <span>Não foram encontrados registros de usuários.</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                @if (count($years) > 0)
                                    <label class="form-label" for="start_year">Ano início:</label>
                                    <select class="form-select" name="start_year" id="start_year">
                                        @foreach ($years as $year)
                                            <option
                                                value="{{ $year->year }}"
                                                @if ($year->year == $actualYear) {{ 'selected' }} @endif
                                            >
                                                {{ $year->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span>Não foram encontrados registros de anos.</span>
                                @endif
                            </div>
                            <div class="form-group mt-3">
                                @if (count($years) > 0)
                                    <label class="form-label" for="end_year">Ano fim:</label>
                                    <select class="form-select" name="end_year" id="end_year">
                                        @foreach ($years as $year)
                                            <option
                                                value="{{ $year->year }}"
                                                @if ($year->year == $actualYear) {{ 'selected' }} @endif
                                            >
                                                {{ $year->year }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span>Não foram encontrados registros de anos.</span>
                                @endif
                            </div>
                            <div class="form-group mt-3 d-flex flex-column">
                                <label class="form-label">&nbsp;</label>
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    id="search-expenses-by-categories"
                                >
                                    <i class="fas fa-search"></i>
                                    Pesquisar dados
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<section id="table-reports" class="mt-3">
    <span class="d-block mt-3">
        <i class="fas fa-info-circle fa-lg"></i>
        Selecione os filtros acima e clique em pesquisar para buscar os dados.
    </span>
</section>

@endsection
