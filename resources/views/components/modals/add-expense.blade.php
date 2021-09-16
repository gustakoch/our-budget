<div class="modal fade" id="addExpenseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar nova despesa</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-expense">
                    @csrf

                    <div class="mb-3">
                        <label for="description_expense" class="form-label">Descrição da despesa*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            name="description_expense"
                            id="description_expense"
                            placeholder="Informe a descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="b-3">
                        <label class="form-label" for="category_expense">Categoria*</label>
                        <select class="form-select mb-3" name="category_expense" id="category_expense">
                            <option selected>Selecione a categoria</option>
                            @foreach ($expenseCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($type_expenses == '15/30')
                        <div class="mb-3">
                            <label class="form-label" for="period_expense">Período de vencimento*</label>
                            <select class="form-select mb-3" name="period_expense" id="period_expense">
                                <option selected value="0">Selecione o período</option>
                                <option value="1">Precisa pagar até o dia 15</option>
                                <option value="2">Pode pagar até o fim do mês</option>
                            </select>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="installments_expense" class="form-label">Parcelas</label>
                        <select class="form-select mb-3" name="installments_expense" id="installments_expense">
                            <option selected value="1">Parcela única</option>
                            @php
                                $currentMonth = (int) $_SESSION['month'];
                                $showMonths = (12 - $currentMonth) + 1;
                            @endphp
                            @for ($i = 2; $i <= $showMonths; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budgeted_amount_expense" class="form-label">Valor orçado* <small>(em R$)</small></label>
                        <input class="form-control" type="number" name="budgeted_amount_expense" id="budgeted_amount_expense" placeholder="Informe o valor" />
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" name="repeat_next_months_expense" value="1" id="repeat_next_months_expense">
                        <label class="form-check-label" for="repeat_next_months_expense">
                            Repetir esta despesa nos próximos meses
                        </label>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input was_with_credit_card" type="checkbox" id="was_with_credit_card">
                        <label class="form-check-label" for="was_with_credit_card">
                            Despesa do cartão de crédito
                        </label>
                    </div>
                    <div class="mb-3" id="expense_card_selection">
                        <label class="form-label" for="credit_card">Diz aí, qual cartão foi?*</label>
                        <select class="form-select mb-3" name="credit_card" id="credit_card" disabled>
                            @if (count($cards) > 0)
                                <option selected value="0">Selecione o cartão</option>
                                @foreach ($cards as $card)
                                    <option value="{{ $card->id }}">{{ $card->description }}</option>
                                @endforeach
                            @else
                                <option selected value="0">Não foram localizados cartões de crédito</option>
                            @endif
                        </select>
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary cancel-expense" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-expense-continue">Salvar e continuar</button>
                <button type="button" class="btn btn-primary save-expense">Salvar e fechar</button>
            </div>
        </div>
    </div>
</div>
