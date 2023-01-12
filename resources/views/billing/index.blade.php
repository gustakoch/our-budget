@extends('layouts.app')
@section('title', 'Cobranças enviadas')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Cobranças enviadas</h3>
    <span>Painel de envio de despesas para os usuários da plataforma.</span>
</nav>

<button
    class="btn btn-success mb-4"
    data-bs-toggle="modal"
    data-bs-target="#addSubmitExpenseModal"
>
    Nova cobrança
</button>

<h4 class="my-3">Pendentes</h4>

@if (count($pendingSubmittedExpenses) > 0)
    <table id="dataTableSaidasPendentes" class="table" style="vertical-align: middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Mês</th>
                <th>Nº de parcelas</th>
                <th>Valor total</th>
                <th>Para quem</th>
                <th>Data de envio</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingSubmittedExpenses as $expense)
                <tr style="{{ $expense->status == 0 ? 'height: 55.7px' : ''}}">
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ $expense->month }}</td>
                    <td>{{ ($expense->installments == 1) ? 'Parcela única' : $expense->installments }}</td>
                    <td>{{ number_format($expense->value, 2, ',', '.') }}</td>
                    <td>{{ $expense->to_user }}</td>
                    <td>{{ $expense->created_at }}</td>
                    <td>
                        @if ($expense->status == 0)
                            <span class="text-warning">Aguardando aprovação</span>
                        @elseif ($expense->status == 2)
                            <div class="d-flex align-items-center">
                                <span class="text-warning">Recusado</span>
                                <div>
                                    <span
                                        span class="d-inline-block"
                                        tabindex="0"
                                        data-bs-toggle="tooltip"
                                        title="{{ $expense->refuse_details }}"
                                    >
                                        <button class="btn">
                                            <svg class="me-2" enable-background="new 0 0 85 85" height="18" widht="18" id="Layer_1" version="1.1" viewBox="0 0 85 85" style="fill: #ffc107" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M42.5,0.003C19.028,0.003,0,19.031,0,42.503s19.028,42.5,42.5,42.5S85,65.976,85,42.503S65.972,0.003,42.5,0.003z   M42.288,66.27c0,0-1.972,1.311-3.32,1.305c-0.12,0.055-0.191,0.087-0.191,0.087l0.003-0.087c-0.283-0.013-0.568-0.053-0.855-0.125  l-0.426-0.105c-2.354-0.584-3.6-2.918-3.014-5.271l3.277-13.211l1.479-5.967c1.376-5.54-4.363,1.178-5.54-1.374  c-0.777-1.687,4.464-5.227,8.293-7.896c0,0,1.97-1.309,3.319-1.304c0.121-0.056,0.192-0.087,0.192-0.087l-0.005,0.087  c0.285,0.013,0.57,0.053,0.857,0.124l0.426,0.106c2.354,0.584,3.788,2.965,3.204,5.318l-3.276,13.212l-1.482,5.967  c-1.374,5.54,4.27-1.204,5.446,1.351C51.452,60.085,46.116,63.601,42.288,66.27z M50.594,24.976  c-0.818,3.295-4.152,5.304-7.446,4.486c-3.296-0.818-5.305-4.151-4.487-7.447c0.818-3.296,4.152-5.304,7.446-4.486  C49.403,18.346,51.411,21.68,50.594,24.976z"/></svg>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($expense->status == 2)
                            <div class="input-group">
                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item d-flex align-items-center edit-submitted-expense" id="{{ $expense->id }}" data-bs-toggle="modal" data-bs-target="#editSubmitExpenseModal">
                                        <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                    <li><a class="dropdown-item d-flex align-items-center delete-submitted-expense" id="{{ $expense->id }}">
                                        <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                        Excluir
                                    </a></li>
                                </ul>
                            </div>
                        @else
                            <div class="input-group" style="width: 72.46px !important;">
                                <span
                                    span class="d-inline-block"
                                    tabindex="0"
                                    data-bs-toggle="tooltip"
                                    title="Não há ações disponíveis para este item!"
                                    style="width: 100% !important;"
                                >
                                    <button disabled class="btn btn-outline-secondary d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 100% !important;"></button>
                                </span>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <span>Nenhum registro encontrado.</span>
@endif

<h4 class="mt-4 mb-3">Aprovadas</h4>

@if (count($approvedSubmittedExpenses) > 0)
    <table id="dataTableSaidasAprovadas" class="table" style="vertical-align: middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Mês</th>
                <th>Nº de parcelas</th>
                <th>Valor total</th>
                <th>Para quem</th>
                <th>Data de criação</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($approvedSubmittedExpenses as $expense)
                <tr style="height: 55.7px">
                    <td>{{ $expense->id }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ $expense->month }}</td>
                    <td>{{ ($expense->installments == 1) ? 'Parcela única' : $expense->installments }}</td>
                    <td>{{ number_format($expense->value, 2, ',', '.') }}</td>
                    <td>{{ $expense->to_user }}</td>
                    <td>{{ $expense->created_at }}</td>
                    <td>@if ($expense->status == 1) <span class="text-success">Aprovado</span> @endif</td>
                    <td>
                        <div class="input-group" style="width: 72.46px !important;">
                            <span
                                span class="d-inline-block"
                                tabindex="0"
                                data-bs-toggle="tooltip"
                                title="Não há ações disponíveis para este item!"
                                style="width: 100% !important;"
                            >
                                <button disabled class="btn btn-outline-secondary d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 100% !important;"></button>
                            </span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <span>Nenhum registro encontrado.</span>
@endif

@include('billing.modals.add', [
    'expenseCategories' => $expenseCategories,
    'installments' => $installments,
    'years' => $years
])

@include('billing.modals.edit', [
    'expenseCategories' => $expenseCategories,
])

@endsection
