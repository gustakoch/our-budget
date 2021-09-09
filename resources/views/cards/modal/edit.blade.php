<div class="modal fade" id="editCardModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Editando cartão</h5>
            </div>
            <div class="modal-body">
                <form id="form-edit-card">
                    @csrf

                    <input type="hidden" name="id_card">
                    <div class="mb-3">
                        <label class="form-label" for="card_description_edit">Descrição*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="card_description_edit"
                            name="card_description_edit"
                            placeholder="Informe a descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número</label>
                        <input
                            class="form-control mb-3 border-red"
                            type="text"
                            id="card_number_edit"
                            placeholder="Informe o número"
                            name="card_number_edit"
                            autocomplete="off"
                            data-mask="0000-0000-0000-0000"
                            disabled
                        />
                        <small id="msg-no-card-number" class="text-danger" style="display: block; margin-top: -0.5rem !important;">Não é possível alterar o número! Caso esta seja a sua vontade, por favor, cadastre um novo cartão.</small>
                    </div>
                    <div class="mb-3">
                        <label for="card_flag_edit" class="form-label">Bandeira</label>
                        <select class="form-select mb-3" name="card_flag_edit" id="card_flag_edit">
                            <option value="0">Selecione a bandeira</option>
                            @foreach ($flags as $flag)
                                <option value="{{ $flag->id }}">{{ $flag->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-card">Atualizar cartão</button>
            </div>
        </div>
    </div>
</div>
