<div class="modal fade" id="editExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditExpense">
                <h5 class="modal-title" id="exampleModalLabel">Editando saída</h5>
                <small class="message-done text-success" style="display: none;">(Saída concluída)</small>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinner">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <form id="form-edit-expense">
                    @csrf

                    <input type="hidden" name="id_expense">
                    <input type="hidden" name="category_active">
                    <div class="mb-3">
                        <label for="description_expense" class="form-label">Descrição da saída*</label>
                        <input class="form-control mb-3" type="text" name="description_expense_edit"
                            id="description_expense" placeholder="Informe a descrição" />
                    </div>
                    <div class="b-3">
                        <label class="form-label" for="category_expense">Categoria*</label>
                        <select class="form-select mb-3" name="category_expense_edit" id="category_expense">
                            @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->description }}</option>
                            @endforeach
                        </select>
                        <small class="msg-category-cancelled"
                            style="color: red; margin-top: -0.5rem !important; margin-bottom: 1rem; display: none;">Esta
                            categoria foi excluída! Sua alteração náo é possível.</small>
                    </div>
                    @if ($type_expenses == '15/30')
                    <div class="mb-3">
                        <label class="form-label" for="period_expense">Período de vencimento*</label>
                        <select class="form-select mb-3" name="period_expense_edit" id="period_expense">
                            <option value="1">Precisa pagar até o dia 15</option>
                            <option value="2">Pode pagar até o fim do mês</option>
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="period_expense_edit" id="period_expense_edit" />
                    @endif
                    <div class="mb-3 col-3">
                        <label for="installments_expense" class="form-label">Parcela*</label>
                        <select class="form-select mb-3" name="installments_expense_edit" id="installments_expense">
                            <option value="1">1</option>
                            @for ($i = 2; $i <= 12; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budgeted_amount_expense" class="form-label">Valor orçado* <small>(em
                                    R$)</small></label>
                            <input class="form-control" type="number" name="budgeted_amount_expense_edit"
                                id="budgeted_amount_expense" placeholder="Informe o valor" />
                        </div>
                        <div class="col-md-6" style="margin-top: -12px;">
                            <div class="d-flex align-items-end">
                                <label id="label-realized-value" for="realized_amount_expense_edit"
                                    class="form-label">Lançar valor</label>
                                <div style="height: 42px;">
                                    <button class="history-btn" data-toggle="modal" data-target="#modalHistoryExpenses"
                                        title="Visualizar histórico de lançamentos" style="display: none;">
                                        <i class="fas fa-history fa-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <input class="form-control" type="number" name="realized_amount_expense_edit"
                                id="realized_amount_expense_edit" placeholder="Informe um valor" autocomplete="off" />
                            <input type="hidden" name="total_realized_amount" />
                            <small id="msg-info-lancar-valor" style="display: block; margin-top: 0.5rem;">(Ex: 100 para
                                somar, -100 para
                                subtrair)</small>
                        </div>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input was_with_credit_card_edit" type="checkbox"
                            id="was_with_credit_card_edit">
                        <label class="form-check-label" for="was_with_credit_card_edit">
                            Saída do cartão de crédito
                        </label>
                    </div>
                    <div class="mb-3" id="expense_card_selection_edit">
                        <label class="form-label" for="credit_card_edit">Diz aí, qual cartão foi?*</label>
                        <select class="form-select mb-3" name="credit_card_edit" id="credit_card_edit" disabled>
                            @if (count($cards))
                            <option selected value="0">Selecione o cartão</option>
                            @foreach ($cards as $card)
                            <option value="{{ $card->id }}">{{ $card->description }}</option>
                            @endforeach
                            @else
                            <option value="0">Não há cartões cadastrados</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" value="1" name="expense_paid" id="expense_paid">
                        <label class="form-check-label" for="expense_paid">
                            Marcar saída como paga
                        </label>
                    </div>

                    <small>(* Dados obrigatórios)</small>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditExpense">
                <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-expense">Atualizar saída</button>
            </div>
        </div>
    </div>
</div>
