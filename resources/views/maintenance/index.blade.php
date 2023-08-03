@extends('layouts.app')
@section('title', 'Manutenções')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Manutenções</h3>
    <span>O sistema quebrou ou precisa ajustar alguma informação? Você está no lugar certo!</span>
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
                    Saídas
                </button>
                {{-- <button
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
                </button> --}}
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-my-data" role="tabpanel" aria-labelledby="nav-my-data-tab">
                <div class="py-4">
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
                                    <form id="form-search-maintenance">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-2">
                                                <label for="id_expense" class="form-label">Código da saída</label>
                                                <input class="form-control mb-3" type="number" name="id_expense" />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-primary search-maintenance">
                                                    <i class="fas fa-search"></i>
                                                    Pesquisar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="table-search-maintenance" class="mt-3">
    <span class="d-block mt-3">
        <i class="fas fa-info-circle fa-lg"></i>
        Selecione os filtros acima e clique em pesquisar para buscar os dados.
    </span>
</section>

@include('maintenance.modals.adjust', [
    'expenseCategories' => $expenseCategories,
    'type_expenses' => $typeExpenses,
    'installments' => $installments,
    'cards' => $cards
])

@endsection
