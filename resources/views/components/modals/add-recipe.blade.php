<div class="modal fade" id="addRecipeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar nova entrada</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-recipe">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="description">Descrição da entrada*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="description"
                            name="description"
                            placeholder="Informe o descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Categoria*</label>
                        <select class="form-select mb-3" name="category" id="category">
                            <option selected>Selecione a categoria</option>
                            @foreach ($recipeCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Valor* <small>(em R$)</small></label>
                        <input class="form-control" type="number" name="budgeted_amount" placeholder="Informe o valor" />
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" name="repeat_next_months" value="1" id="loopingCheckboxAdd">
                        <label class="form-check-label" for="loopingCheckboxAdd">
                            Repetir esta entrada até o final do ano
                        </label>
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary cancel-recipe" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-recipe-continue">
                    Salvar e continuar
                </button>
                <button type="button" class="btn btn-primary save-recipe">
                    Salvar e fechar
                </button>
            </div>
        </div>
    </div>
</div>
