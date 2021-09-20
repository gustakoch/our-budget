<div class="modal fade" id="editCategoryModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Editando categoria</h5>
            </div>
            <div class="modal-body">
                <form id="form-edit-category">
                    @csrf

                    <input type="hidden" name="id_category">
                    <div class="mb-3">
                        <label class="form-label" for="description_category_edit">Descrição da categoria*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="description_category_edit"
                            name="description_category_edit"
                            placeholder="Informe o descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="belongs_to_edit" class="form-label">Pertencente a*</label>
                        <select class="form-select mb-3" name="belongs_to_edit" id="belongs_to_edit">
                            <option value="1">Receita</option>
                            <option value="2">Despesa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="color">Cor*</label><small> (A cor selecionada será apresentada no gráfico.)</small>
                        <input
                            class="form-control mb-3 w-25"
                            type="color"
                            id="color"
                            name="color"
                        />
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-category">Atualizar categoria</button>
            </div>
        </div>
    </div>
</div>
