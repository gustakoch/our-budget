<div class="modal fade" id="editRecipeModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditRecipe">
                <h5 class="modal-title" id="exampleModalLabel">Editar receita</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerRecipe">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <form id="form-edit-recipe">
                    @csrf

                    <input type="hidden" name="id_recipe">
                    <input type="hidden" name="category_active">
                    <div class="mb-3">
                        <label for="description_edit" class="form-label">Descrição da receita</label>
                        <input class="form-control mb-3" type="text" name="description_edit" id="description_edit" placeholder="Informe a descrição" />
                    </div>
                    <div class="mb-3">
                        <label for="category_edit" class="form-label">Categoria</label>
                        <select class="form-select mb-3" name="category_edit" id="category_edit">
                            @foreach ($allRecipeCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->description }}</option>
                            @endforeach
                        </select>
                        <small class="msg-category-cancelled" style="color: red; margin-top: -0.5rem !important; display: none;">Esta categoria foi excluída! Sua alteração náo é possível.</small>
                    </div>
                    <div class="mb-3">
                        <label for="budgeted_amount_edit" class="form-label">Valor</label>
                        <input class="form-control" type="number" name="budgeted_amount_edit" id="budgeted_amount_edit" placeholder="Valor orçado (R$)" />
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="repeat_next_months" value="1" id="loopingCheckboxEdit">
                        <label class="form-check-label label-checkbox-repeat" for="loopingCheckboxEdit">
                            Repetir esta receita nos próximos meses
                        </label>
                      </div>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditRecipe">
                <button type="button" class="btn btn-secondary cancel-recipe" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-recipe">Atualizar receita</button>
            </div>
        </div>
    </div>
</div>
