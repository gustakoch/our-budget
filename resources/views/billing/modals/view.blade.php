<div class="modal fade" id="viewSubmitExpenseModal" data-bs-backdrop="static" data-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1440px;">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Lançamentos de saídas recebidos</h5>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Descrição da saída</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Mês início</th>
                            <th scope="col">Parcelas</th>
                            <th scope="col">Valor total</th>
                            <th scope="col">Registrado em</th>
                            <th scope="col">Recebido de</th>
                            <th scope="col">Informações</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-recipes">
                        @foreach ($receivedExpenses as $expense)
                            <tr class="align-middle">
                                <td>{{ $expense->description }}</td>
                                <td>{{ $expense->category }}</td>
                                <td>{{ $expense->month_name }}</td>
                                <td>{{ ($expense->installments == 1) ? 'Parcela única' : $expense->installments }}</td>
                                <td>{{ number_format($expense->value, 2, ',', '.') }}</td>
                                <td>{{ $expense->received_at }}</td>
                                <td>{{ $expense->from_user }}</td>
                                <td>
                                    @if (!$expense->document && !$expense->generate_receipt)
                                        -
                                    @endif

                                    @if ($expense->document)
                                        <span
                                            span class="d-inline-block"
                                            tabindex="0"
                                            data-bs-toggle="tooltip"
                                            title="Um documento foi anexado! Clique para visualizar."
                                        >
                                            <a href="{{ url("storage/documents/{$expense->document}") }}" target="_blank" style="margin-right: 5px;">
                                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                                            </a>
                                        </span>
                                    @endif

                                    @if ($expense->generate_receipt)
                                        <span
                                            span class="d-inline-block"
                                            tabindex="0"
                                            data-bs-toggle="tooltip"
                                            title="Esta despesa irá gerar uma entrada para o usuário remetente."
                                        >
                                            <i class="fas fa-info-circle fa-2x text-warning"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item d-flex align-items-center confirm-submitted-expense" id="{{ $expense->id }}">
                                                <img class="me-2" src="{{ asset('/images/icons/icon-confirm.png') }}" alt="Confirmar">
                                                Confirmar
                                            </a></li>
                                            <li><button class="dropdown-item d-flex align-items-center get-submitted-expense-id" id="{{ $expense->id }}" data-bs-dismiss="modal" data-toggle="modal" data-target="#refuseSubmittedExpense">
                                                <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Recusar">
                                                Recusar
                                            </button></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refuseSubmittedExpense" data-bs-backdrop="static" data-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Recusar lançamento de saída</h5>
            </div>
            <div class="modal-body">
                <form id="form-refuse-detail">
                    @csrf

                    <input type="hidden" name="id_submitted_expense" />
                    <div class="mb-3">
                        <label for="refuse_detail" class="form-label">Informe abaixo o motivo da recusa*</label>
                        <textarea class="form-control" name="refuse_detail" rows="3" minlength="3" placeholder="Descreva aqui..."></textarea>
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-toggle="modal" data-bs-target="#viewSubmitExpenseModal">Cancelar</button>
                <button type="button" class="btn btn-primary refuse-submitted-expense">Recusar lançamento</button>
            </div>
        </div>
    </div>
</div>
