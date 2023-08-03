<div class="modal fade" id="adjustExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditExpense">
                <h5 class="modal-title" id="exampleModalLabel">Ajustes na saída</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerAdjustModal">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <form id="form-maintenance-expense">
                    @csrf

                    <input type="hidden" name="id_expense" />
                    <div class="row mb-3">
                        <div class="col-8">
                            <label for="description_expense" class="form-label">Descrição da saída*</label>
                            <input class="form-control mb-3" type="text" name="description_expense" id="description_expense" placeholder="Informe a descrição" />
                        </div>
                        <div class="col-4">
                            <label class="form-label" for="category_expense">Categoria*</label>
                            <select class="form-select mb-3" name="category_expense" id="category_expense">
                                @foreach ($expenseCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <label id="label-realized-value" for="amount_expense"class="form-label">Remover valor</label>
                            <input class="form-control" type="number" name="amount_expense" id="amount_expense" placeholder="Informe um valor" autocomplete="off" />
                            <small id="msg-info-lancar-valor">
                                (Obs: Informar valores negativos (Ex: -100) para subtrair do total acumulado)
                            </small>
                        </div>
                    </div>
                    <small>(* Dados obrigatórios)</small>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditExpense" style="display: flex; justify-content: space-between;">
                <div class="start-button">
                    <button type="button" class="btn btn-danger maintenance-btn-delete delete-expense" id="">Excluir saída</button>
                </div>
                <div class="end-buttons">
                    <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary update-maintenance-expense">Ajustar saída</button>
                </div>
            </div>
        </div>
    </div>
</div>
