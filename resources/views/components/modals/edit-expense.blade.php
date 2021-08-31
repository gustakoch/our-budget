<div class="modal fade" id="editExpenseModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditExpense">
                <h5 class="modal-title" id="exampleModalLabel">Editar despesa</h5>
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
                    <div class="mb-3">
                        <label for="description_expense" class="form-label">Descrição da despesa</label>
                        <input class="form-control mb-3" type="text" name="description_expense_edit" id="description_expense" placeholder="Informe a descrição" />
                    </div>
                    <div class="b-3">
                        <label class="form-label" for="category_expense">Categoria</label>
                        <select class="form-select mb-3" name="category_expense_edit" id="category_expense">
                            @foreach ($expenseCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($type_expenses == '15/30')
                        <div class="mb-3">
                            <label class="form-label" for="period_expense">Período de vencimento</label>
                            <select class="form-select mb-3" name="period_expense_edit" id="period_expense">
                                <option value="1">Precisa pagar até o dia 15</option>
                                <option value="2">Pode pagar até o fim do mês</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="period_expense_edit" id="period_expense_edit" />
                    @endif
                    <div class="mb-3 col-4">
                        <label for="installments_expense" class="form-label">Parcela <small>(Não pode ser alterado)</small></label>
                        <select class="form-select mb-3" name="installments_expense_edit" id="installments_expense">
                            <option value="1">1</option>
                            @for ($i = 2; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="budgeted_amount_expense" class="form-label">Valor orçado <small>(em R$)</small></label>
                            <input class="form-control" type="number" name="budgeted_amount_expense_edit" id="budgeted_amount_expense" placeholder="Informe o valor" />
                        </div>
                        <div class="col-md-6">
                            <label for="realized_amount_expense_edit" class="form-label">Valor realizado <small>(em R$)</small></label>
                            <input class="form-control" type="number" name="realized_amount_expense_edit" id="realized_amount_expense_edit" placeholder="Informe o valor" />
                        </div>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" name="repeat_next_months_expense_edit" value="1" id="repeat_next_months_expense">
                        <label class="form-check-label form-check-label-expense" for="repeat_next_months_expense">
                            Repetir esta despesa nos próximos meses
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditExpense">
                <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-expense">Atualizar despesa</button>
            </div>
        </div>
    </div>
</div>
