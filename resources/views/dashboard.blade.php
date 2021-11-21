@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Dashboard</h3>
    <span>"Um plano não é nada, mas o planejamento é tudo." <small>(Dwight D. Eisenhower)</small></span>
</nav>

<div class="filters">
    <div class="years">
        <strong>Ano</strong>
        <div class="months-years-row">
            @foreach ($years as $yearItem)
                <input type="radio" name="year" id="year-{{ $yearItem->year }}" value="{{ $yearItem->year }}" {{ $yearItem->year == $year ? 'checked' : '' }}>
                <label for="year-{{ $yearItem->year }}">{{ $yearItem->year }}</label>
            @endforeach
        </div>
    </div>
    <div class="months">
        <strong>Meses</strong>
        <div class="months-years-row">
            @foreach ($months as $i => $monthOfYear)
                <input type="radio" name="month" id="month-{{ $i +1 }}" value="{{ $i +1 }}" {{ $i + 1 == $month ? 'checked' : '' }}>
                <label for="month-{{ $i +1 }}">{{ $monthOfYear }}</label>
            @endforeach
        </div>
    </div>
</div>

@include('components.resume', [$resumeData['totals']])

<section id="recipes" class="mt-5">
    <div class="container-custom">
        <div class="section-title d-flex align-items-end pb-2">
            <img class="me-2" src="{{ asset('/images/icons/up-icon.png') }}" alt="Ícone de cadastro de receitas">
            <h5 class="text-weight me-4">Receitas</h5>
            <button
                class="btn btn-primary"
                id="add-recipe"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#addRecipeModal"
                style="display: none"
            >
                Adicionar receita
            </button>
        </div>
        <div class="accordion accordion-flush border shadow-sm mt-2" id="accordionResume">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-recipes"
                        aria-expanded="false"
                        aria-controls="collapse-recipes"
                        style="background-color: #9ac09a !important"
                    >
                        <strong>Todas as receitas</strong>
                    </button>
                </h2>
                <div id="collapse-recipes" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionResume">
                    <div class="accordion-body accordion-recipes">
                        @if (count($recipes) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Descrição da receita</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Orçado (R$)</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-recipes">
                                    @foreach ($recipes as $recipe)
                                    <tr class="align-middle">
                                        <td>{{ $recipe->description }}</td>
                                        <td>{{ $recipe->category_description }}</td>
                                        <td>{{ number_format($recipe->budgeted_amount, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item d-flex align-items-center edit-recipe" id="{{ $recipe->id }}" data-bs-toggle="modal" data-bs-target="#editRecipeModal">
                                                        <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                                        Editar
                                                    </a></li>
                                                    <li><a class="dropdown-item d-flex align-items-center delete-recipe" id="{{ $recipe->id }}">
                                                        <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                                        Excluir
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="align-middle no-border-bottom justify-content-end">
                                        <th></th>
                                        <td></td>
                                        <th id="total-amount" class="text-success">{{ number_format($budgeted_amount, 2, ',', '.') }}</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <span>Não há receitas lançadas.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="recipes" class="mt-5">
    <div class="container-custom">
        <div class="section-title d-flex align-items-end pb-2">
            <img class="me-2" src="{{ asset('/images/icons/down-icon.png') }}" alt="Ícone de cadastro de despesas">
            <h5 class="text-weight me-4">Despesas</h5>
            <button
                class="btn btn-primary"
                id="adicionar-despesa"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#addExpenseModal"
                style="display: none"
            >
                Adicionar despesa
            </button>
        </div>
        <div class="accordion accordion-flush border shadow-sm mt-2" id="accordionResume">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-expensesOne"
                        aria-expanded="false"
                        aria-controls="collapse-expensesOne"
                        style="background-color: #cea1a1 !important"
                    >
                        <strong>Todas as despesas</strong>
                    </button>
                </h2>
                <div id="collapse-expensesOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionResume">
                    <div class="accordion-body accordion-expenses-type-0">
                        @if (count($expenses0) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Descrição da despesa</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Parcelas</th>
                                        <th scope="col">Orçado (R$)</th>
                                        <th scope="col">Realizado (R$)</th>
                                        <th scope="col">Pendente (R$)</th>
                                        <th scope="col">Situação</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-expenses-type-0">
                                    @foreach ($expenses0 as $expense)
                                        @if ($expense->period == 0)
                                            <tr
                                                class="
                                                    align-middle
                                                    {{ $expense->cancelled == 1 ? 'cancelled' : '' }}
                                                    {{ $expense->budgeted_amount - $expense->realized_amount <= 0 ? 'text-success font-bold-500' : '' }}"
                                            >
                                                <td>{{ $expense->description }}</td>
                                                <td>{{ $expense->category_description }}</td>
                                                <td>
                                                    @if ($expense->installments == 1)
                                                        --
                                                    @else
                                                        {{ $expense->installment }} de {{ $expense->installments }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($expense->budgeted_amount, 2, ',', '.') }}</td>
                                                <td>{{ number_format($expense->realized_amount, 2, ',', '.') }}</td>
                                                <td>{{ number_format(($expense->budgeted_amount - $expense->realized_amount), 2, ',', '.') }}</td>
                                                <td>
                                                    @if ($expense->cancelled == 1)
                                                        <span class="cancelled">Cancelado</span>
                                                    @elseif ($expense->budgeted_amount - $expense->realized_amount == 0)
                                                        <span class="text-success">Concluído</span>
                                                    @else
                                                        <span class="text-warning">Em aberto</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($expense->cancelled == 0)
                                                                <li><a class="dropdown-item d-flex align-items-center edit-expense" id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#editExpenseModal">
                                                                    <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                                                    Editar
                                                                </a></li>
                                                            @endif
                                                            @if ($expense->installments == 1)
                                                                <li><a class="dropdown-item d-flex align-items-center delete-expense" id="{{ $expense->id }}">
                                                                    <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                                                    Excluir
                                                                </a></li>
                                                            @endif

                                                            @if ($expense->installments > 1 && $expense->cancelled == 0 && (floatval($expense->budgeted_amount) - floatval($expense->realized_amount)) != 0)
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item d-flex align-items-center cancel-installment" id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#oneReasonCancellation">
                                                                    <img class="me-2" src="{{ asset('/images/icons/icon-cancel.png') }}">
                                                                    Cancelar parcela
                                                                </a></li>
                                                                <li><a class="dropdown-item d-flex align-items-center cancel-installments" id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#allReasonCancellation">
                                                                    <img class="me-2" src="{{ asset('/images/icons/icon-cancel.png') }}">
                                                                    Cancelar parcela atual e as próximas
                                                                </a></li>
                                                                <li><a class="dropdown-item d-flex align-items-center extend-installment" id="{{ $expense->id }}">
                                                                    <svg class="me-2" height="14" width="14" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="fill: green"><title/><g data-name="fast forward" id="fast_forward"><path d="M23,16a1,1,0,0,1-.37.78L13,24.6,6.63,29.78A1,1,0,0,1,6,30a.94.94,0,0,1-.43-.1A1,1,0,0,1,5,29V3a1,1,0,0,1,.57-.9,1,1,0,0,1,1.06.12L13,7.4l9.63,7.82A1,1,0,0,1,23,16Z"/><path d="M30,16a1,1,0,0,1-.35.76l-15,13A1,1,0,0,1,14,30a1.06,1.06,0,0,1-.42-.09A1,1,0,0,1,13,29V25.89l10.26-8.34a2,2,0,0,0,0-3.1L13,6.11V3a1,1,0,0,1,1.65-.76l15,13A1,1,0,0,1,30,16Z"/></g></svg>
                                                                    Prorrogar
                                                                </a></li>
                                                            @endif

                                                            @if ($expense->cancelled != 0)
                                                                <li><a class="dropdown-item d-flex align-items-center info-expense" id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#infoExpenseModal">
                                                                    <svg class="me-2" enable-background="new 0 0 85 85" height="14" widht="14" id="Layer_1" version="1.1" viewBox="0 0 85 85" style="fill: blue" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M42.5,0.003C19.028,0.003,0,19.031,0,42.503s19.028,42.5,42.5,42.5S85,65.976,85,42.503S65.972,0.003,42.5,0.003z   M42.288,66.27c0,0-1.972,1.311-3.32,1.305c-0.12,0.055-0.191,0.087-0.191,0.087l0.003-0.087c-0.283-0.013-0.568-0.053-0.855-0.125  l-0.426-0.105c-2.354-0.584-3.6-2.918-3.014-5.271l3.277-13.211l1.479-5.967c1.376-5.54-4.363,1.178-5.54-1.374  c-0.777-1.687,4.464-5.227,8.293-7.896c0,0,1.97-1.309,3.319-1.304c0.121-0.056,0.192-0.087,0.192-0.087l-0.005,0.087  c0.285,0.013,0.57,0.053,0.857,0.124l0.426,0.106c2.354,0.584,3.788,2.965,3.204,5.318l-3.276,13.212l-1.482,5.967  c-1.374,5.54,4.27-1.204,5.446,1.351C51.452,60.085,46.116,63.601,42.288,66.27z M50.594,24.976  c-0.818,3.295-4.152,5.304-7.446,4.486c-3.296-0.818-5.305-4.151-4.487-7.447c0.818-3.296,4.152-5.304,7.446-4.486  C49.403,18.346,51.411,21.68,50.594,24.976z"/></svg>
                                                                    Visualizar
                                                                </a></li>
                                                                <li><a class="dropdown-item d-flex align-items-center revert-installment" id="{{ $expense->id }}">
                                                                    <svg class="me-2" height="14" width="14" version="1.1" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="#42c2d8" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#42c2d8" id="Core" transform="translate(-424.000000, -463.000000)"><g id="undo" transform="translate(424.000000, 464.000000)"><path d="M8,3 L8,-0.5 L3,4.5 L8,9.5 L8,5 C11.3,5 14,7.7 14,11 C14,14.3 11.3,17 8,17 C4.7,17 2,14.3 2,11 L0,11 C0,15.4 3.6,19 8,19 C12.4,19 16,15.4 16,11 C16,6.6 12.4,3 8,3 L8,3 Z" id="Shape"/></g></g></g></svg>
                                                                    Reverter cancelamento
                                                                </a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="align-middle no-border-bottom justify-content-end">
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th id="total-amount-budgeted0" class="text-danger">{{ number_format($budgeted_amount_expenses0, 2, ',', '.') }}</th>
                                        <th id="total-amount-realized0" class="text-danger">{{ number_format($realized_amount_expenses0, 2, ',', '.') }}</th>
                                        <th id="total-amount-pending0" class="text-danger">{{ number_format($pending_amount_expenses0, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        @elseif (count($creditCardExpenses) > 0)
                        {{-- Não exibir nada --}}
                        @else
                            <span>Não há despesas lançadas.</span>
                        @endif

                        @if (count($creditCardExpenses) > 0)
                            <h5>Cartões de crédito</h5>
                            @foreach ($creditCardExpenses as $cardExpense)
                                @if (in_array(0, $cardExpense->periods))
                                    <div class="accordion" id="accordionExample" >
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $cardExpense->credit_card }}">
                                                <button
                                                    class="accordion-button button-credit-card"
                                                    style="background-color: #dac2c2 !important; flex: 1;"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $cardExpense->credit_card }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse{{ $cardExpense->credit_card }}"
                                                    title="Clique para ver mais detalhes"
                                                >
                                                    <div class="card-info">
                                                        <strong><i class="fas fa-credit-card"></i> {{ $cardExpense->credit_card_description }} <i class="fas fa-sort-down"></i></strong>
                                                        <small>Fatura atual em R$ {{ number_format($cardExpense->total_budgeted_amount, 2, ',', '.') }}.</small>
                                                        <small>Vencimento para {{ $cardExpense->invoice_day }}.</small>
                                                    </div>
                                                </button>
                                                <div class="pay-button">
                                                    <span
                                                        span class="d-inline-block"
                                                        tabindex="0"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $cardExpense->invoice_payment == '1' ? "Esta fatura foi paga em $cardExpense->pay_day" : '' }}"
                                                    >
                                                        <button
                                                            style="{{ $cardExpense->total_budgeted_amount == 0 ? 'display: none' : '' }}"
                                                            class="btn {{ $cardExpense->invoice_payment == '1' ? 'btn-secondary' : 'btn-info' }} pay-invoice"
                                                            data-js-invoice-id="{{ $cardExpense->invoice_id }}"
                                                            data-js-card-name="{{ $cardExpense->credit_card_description }}"
                                                            {{ $cardExpense->invoice_payment == '1' ? 'disabled' : '' }}
                                                        >
                                                            {{ $cardExpense->invoice_payment == '1' ? 'Fatura paga' : 'Pagar fatura' }}
                                                        </button>
                                                    </span>
                                                </div>
                                            </h2>
                                            <div id="collapse{{ $cardExpense->credit_card }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Descrição da despesa</th>
                                                                <th scope="col">Categoria</th>
                                                                <th scope="col">Parcelas</th>
                                                                <th scope="col">Orçado (R$)</th>
                                                                <th scope="col">Realizado (R$)</th>
                                                                <th scope="col">Pendente (R$)</th>
                                                                <th scope="col">Situação</th>
                                                                <th scope="col">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody-expenses-type-1">
                                                            @foreach ($cardExpense->expenses as $expenseDetails)
                                                                @if ($expenseDetails->period == 0)
                                                                    <tr
                                                                        class="
                                                                            align-middle
                                                                            {{ $expenseDetails->cancelled == 1 ? 'cancelled' : '' }}
                                                                            {{ $expenseDetails->budgeted_amount - $expenseDetails->realized_amount <= 0 ? 'text-success font-bold-500' : '' }}"
                                                                    >
                                                                        <td>{{ $expenseDetails->description }}</td>
                                                                        <td>{{ $expenseDetails->category_description }}</td>
                                                                        <td>
                                                                            @if ($expenseDetails->installments == 1)
                                                                                --
                                                                            @else
                                                                                {{ $expenseDetails->installment }} de {{ $expenseDetails->installments }}
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ number_format($expenseDetails->budgeted_amount, 2, ',', '.') }}</td>
                                                                        <td>{{ number_format($expenseDetails->realized_amount, 2, ',', '.') }}</td>
                                                                        <td>{{ number_format(($expenseDetails->budgeted_amount - $expenseDetails->realized_amount), 2, ',', '.') }}</td>
                                                                        <td>
                                                                            @if ($expenseDetails->cancelled == 1)
                                                                                <span class="cancelled">Cancelado</span>
                                                                            @elseif ($expenseDetails->budgeted_amount - $expenseDetails->realized_amount <= 0)
                                                                                <span class="text-success">Concluído</span>
                                                                            @else
                                                                                <span class="text-warning">Em aberto</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <div class="input-group">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                                                                </button>
                                                                                <ul class="dropdown-menu">
                                                                                    @if ($expenseDetails->cancelled == 0)
                                                                                        <li><a class="dropdown-item d-flex align-items-center edit-expense" id="{{ $expenseDetails->id }}" data-bs-toggle="modal" data-bs-target="#editExpenseModal">
                                                                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                                                                            Editar
                                                                                        </a></li>
                                                                                    @endif
                                                                                    @if ($expenseDetails->installments == 1)
                                                                                        <li><a class="dropdown-item d-flex align-items-center delete-expense" id="{{ $expenseDetails->id }}">
                                                                                            <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                                                                            Excluir
                                                                                        </a></li>
                                                                                    @endif

                                                                                    @if (($expenseDetails->installments > 1 && $expenseDetails->cancelled == 0) && (floatval($expenseDetails->budgeted_amount) - floatval($expenseDetails->realized_amount)) != 0)
                                                                                        <li><hr class="dropdown-divider"></li>
                                                                                        <li><a class="dropdown-item d-flex align-items-center cancel-installment" id="{{ $expenseDetails->id }}" data-bs-toggle="modal" data-bs-target="#oneReasonCancellation">
                                                                                            <img class="me-2" src="{{ asset('/images/icons/icon-cancel.png') }}">
                                                                                            Cancelar parcela
                                                                                        </a></li>
                                                                                        <li><a class="dropdown-item d-flex align-items-center cancel-installments" id="{{ $expenseDetails->id }}" data-bs-toggle="modal" data-bs-target="#allReasonCancellation">
                                                                                            <img class="me-2" src="{{ asset('/images/icons/icon-cancel.png') }}">
                                                                                            Cancelar parcela atual e as próximas
                                                                                        </a></li>
                                                                                        <li><a class="dropdown-item d-flex align-items-center extend-installment" id="{{ $expenseDetails->id }}">
                                                                                            <svg class="me-2" height="14" width="14" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="fill: green"><title/><g data-name="fast forward" id="fast_forward"><path d="M23,16a1,1,0,0,1-.37.78L13,24.6,6.63,29.78A1,1,0,0,1,6,30a.94.94,0,0,1-.43-.1A1,1,0,0,1,5,29V3a1,1,0,0,1,.57-.9,1,1,0,0,1,1.06.12L13,7.4l9.63,7.82A1,1,0,0,1,23,16Z"/><path d="M30,16a1,1,0,0,1-.35.76l-15,13A1,1,0,0,1,14,30a1.06,1.06,0,0,1-.42-.09A1,1,0,0,1,13,29V25.89l10.26-8.34a2,2,0,0,0,0-3.1L13,6.11V3a1,1,0,0,1,1.65-.76l15,13A1,1,0,0,1,30,16Z"/></g></svg>
                                                                                            Prorrogar
                                                                                        </a></li>
                                                                                    @endif

                                                                                    @if ($expenseDetails->cancelled != 0)
                                                                                        <li><a class="dropdown-item d-flex align-items-center info-expense" id="{{ $expenseDetails->id }}" data-bs-toggle="modal" data-bs-target="#infoExpenseModal">
                                                                                            <svg class="me-2" enable-background="new 0 0 85 85" height="14" widht="14" id="Layer_1" version="1.1" viewBox="0 0 85 85" style="fill: blue" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M42.5,0.003C19.028,0.003,0,19.031,0,42.503s19.028,42.5,42.5,42.5S85,65.976,85,42.503S65.972,0.003,42.5,0.003z   M42.288,66.27c0,0-1.972,1.311-3.32,1.305c-0.12,0.055-0.191,0.087-0.191,0.087l0.003-0.087c-0.283-0.013-0.568-0.053-0.855-0.125  l-0.426-0.105c-2.354-0.584-3.6-2.918-3.014-5.271l3.277-13.211l1.479-5.967c1.376-5.54-4.363,1.178-5.54-1.374  c-0.777-1.687,4.464-5.227,8.293-7.896c0,0,1.97-1.309,3.319-1.304c0.121-0.056,0.192-0.087,0.192-0.087l-0.005,0.087  c0.285,0.013,0.57,0.053,0.857,0.124l0.426,0.106c2.354,0.584,3.788,2.965,3.204,5.318l-3.276,13.212l-1.482,5.967  c-1.374,5.54,4.27-1.204,5.446,1.351C51.452,60.085,46.116,63.601,42.288,66.27z M50.594,24.976  c-0.818,3.295-4.152,5.304-7.446,4.486c-3.296-0.818-5.305-4.151-4.487-7.447c0.818-3.296,4.152-5.304,7.446-4.486  C49.403,18.346,51.411,21.68,50.594,24.976z"/></svg>
                                                                                            Visualizar
                                                                                        </a></li>
                                                                                        <li><a class="dropdown-item d-flex align-items-center revert-installment" id="{{ $expenseDetails->id }}">
                                                                                            <svg class="me-2" height="14" width="14" version="1.1" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="#42c2d8" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#42c2d8" id="Core" transform="translate(-424.000000, -463.000000)"><g id="undo" transform="translate(424.000000, 464.000000)"><path d="M8,3 L8,-0.5 L3,4.5 L8,9.5 L8,5 C11.3,5 14,7.7 14,11 C14,14.3 11.3,17 8,17 C4.7,17 2,14.3 2,11 L0,11 C0,15.4 3.6,19 8,19 C12.4,19 16,15.4 16,11 C16,6.6 12.4,3 8,3 L8,3 Z" id="Shape"/></g></g></g></svg>
                                                                                            Reverter cancelamento
                                                                                        </a></li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="align-middle no-border-bottom justify-content-end">
                                                                <td></td>
                                                                <th></th>
                                                                <td></td>
                                                                <th id="total-amount-budgeted1-card" class="text-danger">{{ number_format($cardExpense->total_budgeted_amount, 2, ',', '.') }}</th>
                                                                <th id="total-amount-realized1-card" class="text-danger">{{ number_format($cardExpense->total_realized_amount, 2, ',', '.') }}</th>
                                                                <th id="total-amount-pending1-card" class="text-danger">{{ number_format($cardExpense->total_pending_amount, 2, ',', '.') }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('components.modals.add-recipe', ['recipeCategories' => $activeRecipeCategories])
@include('components.modals.edit-recipe', ['recipeCategories' => $allRecipeCategories])

@include('components.modals.add-expense', [
    'expenseCategories' => $activeExpenseCategories,
    'type_expenses' => $type_expenses,
    'cards' => $cards,
    'installments' => $config['installments']
])
@include('components.modals.edit-expense', [
    'expenseCategories' => $allExpenseCategories,
    'type_expenses' => $type_expenses,
    'installments' => $config['installments']
])

@include('components.modals.reason-cancellation')
@include('components.modals.reason-cancellation-all')
@include('components.modals.info-expense')

@component('components.fab')@endcomponent

@endsection
