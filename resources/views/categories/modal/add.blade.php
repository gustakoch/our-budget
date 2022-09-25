<div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar nova categoria</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-category">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="description_category">Descrição da categoria*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="description_category"
                            name="description_category"
                            placeholder="Informe o descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="belongs_to" class="form-label">Pertencente a*</label>
                        <select class="form-select mb-3" name="belongs_to" id="belongs_to">
                            <option selected>Selecione o vínculo</option>
                            <option value="1">Entrada</option>
                            <option value="2">Saída</option>
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
                <button type="button" class="btn btn-secondary cancel-recipe" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-category">Salvar categoria</button>
            </div>
        </div>
    </div>
</div>
