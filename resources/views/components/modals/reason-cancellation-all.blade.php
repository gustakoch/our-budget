<div class="modal fade" id="allReasonCancellation" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Motivo de cancelamento em massa</h5>
            </div>
            <div class="modal-body">
                <form id="form-expense-cancellation-all">
                    @csrf

                    <input type="hidden" name="id_expense" />
                    <div class="mb-3">
                        <label for="reason" class="form-label">Informe abaixo o motivo do cancelamento das parcelas*</label>
                        <textarea
                            class="form-control"
                            name="reason_cancelled_all"
                            rows="3"
                            placeholder="Descreva aqui toda a sua motivação em querer cancelar as parcelas..."
                        ></textarea>
                    </div>
                </form>
                <small class="text-danger"><strong>Atenção: </strong>Esta ação não poderá ser desfeita e irá cancelar a parcela atual e TODAS as seguintes.</small><br /><br />

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-reason-cancellation-all">Cancelar parcelas</button>
            </div>
        </div>
    </div>
</div>
