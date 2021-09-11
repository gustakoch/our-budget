<div class="modal fade" id="editCardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditCard">
                <h5 class="modal-title" id="exampleModalLabel">Editando cartão</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerCard">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

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
                            @foreach ($flags as $flag)
                                <option value="{{ $flag->id }}">{{ $flag->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="invoice_day">Dia vencimento da fatura*</label>
                        <input
                            class="form-control mb-3"
                            type="number"
                            id="invoice_day"
                            name="invoice_day"
                            placeholder="Informe o dia"
                            autocomplete="off"
                            data-mask="00"
                        />
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditCard">
                <button type="button" class="btn btn-secondary cancel-card" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-card">Atualizar cartão</button>
            </div>
        </div>
    </div>
</div>
