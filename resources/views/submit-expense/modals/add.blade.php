<div class="modal fade" id="addSubmitExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Nova saída</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-submitted-expense">
                    @csrf

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição da saída*</label>
                        <input class="form-control mb-3" type="text" name="description" id="description"
                            placeholder="Informe a descrição" autocomplete="off" />
                    </div>
                    <div class="row">
                        <div class="col-6">
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
                        <div class="col-3">
                            <div class="b-3">
                                <label class="form-label" for="month">Mês de início*</label>
                                <select class="form-select mb-3" name="month" id="month">
                                    @foreach ($months as $month)
                                        <option
                                            value="{{ $month->id }}"
                                            {{ $month->id == $_SESSION['month'] ? 'selected' : '' }}
                                        >
                                            {{ $month->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="b-3">
                                <label class="form-label" for="year">Ano*</label>
                                <select class="form-select mb-3" name="year" id="year">
                                    @foreach ($years as $year)
                                        <option
                                            value="{{ $year->year }}"
                                            {{ $year->year == $_SESSION['year'] ? 'selected' : '' }}
                                        >
                                            {{ $year->year }}
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
                        <label for="amount" class="form-label">Valor total da saída*</label>
                        <input class="form-control" type="number" name="amount" id="amount" placeholder="Informe o valor" />
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-submitted-expense">Lançar saída</button>
            </div>
        </div>
    </div>
</div>
