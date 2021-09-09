<div class="modal fade" id="addCardModal" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar novo cartão</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-card">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="card_description">Descrição*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="card_description"
                            name="card_description"
                            placeholder="Informe a descrição"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="card_number">Número</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="card_number"
                            name="card_number"
                            placeholder="Informe o número"
                            autocomplete="off"
                            data-mask="0000-0000-0000-0000"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="card_flag" class="form-label">Bandeira</label>
                        <select class="form-select mb-3" name="card_flag" id="card_flag">
                            <option selected value="0">Selecione a bandeira</option>
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
                <button type="button" class="btn btn-primary save-card">Salvar cartão</button>
            </div>
        </div>
    </div>
</div>
