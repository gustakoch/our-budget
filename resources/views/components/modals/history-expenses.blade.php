<div class="modal fade" id="modalHistoryExpenses" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-warning">
                <h6 class="modal-title" id="exampleModalLabel">Histórico de lançamentos</h6>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerHistory">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <table class="table table-history" style="display: none;">
                    <thead>
                        <tr>
                            <th><small>Valor lançado</small></th>
                            <th><small>Registrado em</small></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-table-history"></tbody>
                    <tfoot>
                        <tr>
                            <th style="border-bottom: 0;"><small>Total lançado</small></th>
                            <td id="total-history" style="border-bottom: 0;"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
