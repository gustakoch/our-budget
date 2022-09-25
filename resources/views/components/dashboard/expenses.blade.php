<section id="expenses" class="mt-5">
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
                        @if (count($expenses) > 0)
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
                                        <th style="width: 100px;" scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-expenses-type-0">
                                    @foreach ($expenses as $expense)
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
                                                <div class="actions-pay d-flex align-items-center">
                                                    <div class="input-group" style="width: auto !important;">
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
                                                                <li><a class="dropdown-item d-flex align-items-center delete-total-expense" id="{{ $expense->id }}">
                                                                    <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                                                    Excluir parcelamento
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

                                                    @if (($expense->budgeted_amount - $expense->realized_amount) != 0)
                                                        <div class="mx-3">
                                                            <span
                                                                span class="d-inline-block"
                                                                tabindex="0"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $expense->installments > 1 ? 'Pagar parcela' : 'Pagar despesa' }}"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-success pay-expense"
                                                                    id="{{ $expense->id  }}"
                                                                    style="line-height: 1;"
                                                                >
                                                                    <svg height="24" viewBox="0 0 24 24" width="24" style="fill: white" xmlns="http://www.w3.org/2000/svg"><path d="M12,23 C5.92486775,23 1,18.0751322 1,12 C1,5.92486775 5.92486775,1 12,1 C18.0751322,1 23,5.92486775 23,12 C23,18.0751322 18.0751322,23 12,23 Z M12,21 C16.9705627,21 21,16.9705627 21,12 C21,7.02943725 16.9705627,3 12,3 C7.02943725,3 3,7.02943725 3,12 C3,16.9705627 7.02943725,21 12,21 Z M10,13.5857864 L15.2928932,8.29289322 L16.7071068,9.70710678 L10,16.4142136 L6.29289322,12.7071068 L7.70710678,11.2928932 L10,13.5857864 Z" fill-rule="evenodd"/></svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="align-middle no-border-bottom justify-content-end">
                                        <td></td>
                                        <th></th>
                                        <td></td>
                                        <th id="total-amount-budgeted0" class="text-danger">{{ number_format($budgeted_amount_expenses, 2, ',', '.') }}</th>
                                        <th id="total-amount-realized0" class="text-danger">{{ number_format($realized_amount_expenses, 2, ',', '.') }}</th>
                                        <th id="total-amount-pending0" class="text-danger">{{ number_format($pending_amount_expenses, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        @elseif (count($creditCardExpenses) > 0)
                        {{--  --}}
                        @else
                            <span>Não há despesas lançadas.</span>
                        @endif

                        @include('components.dashboard.credit-cards', [$creditCardExpenses])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
