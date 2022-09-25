<div class="modal fade" id="infoExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditExpense">
                <h5 class="modal-title" id="exampleModalLabel">Informações da saída</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerInfo">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <form id="form-info-expense">
                    <div class="mb-3">
                        <label for="description_expense" class="form-label">Descrição da saída</label>
                        <input class="form-control mb-3" type="text" name="description_expense_info" disabled />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="category_expense">Categoria</label>
                                <select class="form-select mb-3" name="category_expense_info" disabled>
                                    <option selected>Selecione a categoria</option>
                                    @foreach ($activeExpenseCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($type_expenses == '15-30')
                                <div class="mb-3">
                                    <label class="form-label" for="period_expense">Período de vencimento</label>
                                    <select class="form-select mb-3" name="period_expense_info" disabled>
                                        <option selected value="0">Selecione o período</option>
                                        <option value="1">Precisa pagar até o dia 15</option>
                                        <option value="2">Pode pagar até o fim do mês</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="installments_expense" class="form-label">Parcela</label>
                            <select class="form-select mb-3" name="installments_expense_info" disabled>
                                <option selected value="1">1</option>
                                @for ($i = 2; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="budgeted_amount_expense" class="form-label">Valor orçado <small>(em R$)</small></label>
                            <input class="form-control" type="number" name="budgeted_amount_expense_info" disabled />
                        </div>
                        <div class="col-5">
                            <label for="realized_amount_expense_edit" class="form-label">Valor realizado <small>(em R$)</small></label>
                            <input class="form-control" type="number" name="realized_amount_expense_info" disabled />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo de cancelamento</label>
                                <textarea class="form-control" name="reason_cancelled_info" rows="3" disabled></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="my-3">
                                <strong>Observação!</strong><br />
                                <span>Esta saída possui um total de <span id="total_installments"></span> parcela(s).</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditExpense">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
