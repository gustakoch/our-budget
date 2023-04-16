<div class="modal fade" id="editSubmitExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderSubmittedExpense">
                <h5 class="modal-title" id="exampleModalLabel">Editar cobrança</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerSubmittedExpense">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <form id="form-update-submitted-expense">
                    @csrf

                    <input type="hidden" name="id_submitted_expense" />
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição da cobrança*</label>
                        <input class="form-control mb-3" type="text" name="description" id="description"
                            placeholder="Informe a descrição" autocomplete="off" />
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="b-3">
                                <label class="form-label" for="category">Categoria*</label>
                                <select class="form-select mb-3" name="category" id="category">
                                    <option selected>Selecione a categoria</option>
                                    @foreach ($expenseCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="b-3">
                                <label class="form-label" for="month">Mês inicial*</label>
                                <select class="form-select mb-3" name="month" id="month">
                                    @foreach ($months as $month)
                                        <option
                                            value="{{ $month->id }}"
                                            {{ $month->id == $_SESSION['month']['id'] ? 'selected' : '' }}
                                        >
                                            {{ $month->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="to_user" class="form-label">Para usuário*</label>
                                <select class="form-select mb-3" name="to_user" id="to_user">
                                    <option value="">Selecione um usuário</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="installments" class="form-label">Parcela(s)*</label>
                                <select class="form-select mb-3" name="installments" id="installments">
                                    <option selected value="1">Parcela única</option>
                                    @for ($i = 2; $i <= $installments; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor total da cobrança* <small>(em R$)</small></label>
                        <input class="form-control" type="number" name="amount" id="amount" placeholder="Informe o valor" />
                    </div>

                    <small>(* Dados obrigatórios)</small>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterSubmittedExpense">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-submitted-expense">Atualizar cobrança</button>
            </div>
        </div>
    </div>
</div>
