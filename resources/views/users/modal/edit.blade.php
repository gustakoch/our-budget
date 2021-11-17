<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0" id="modalHeaderEditUser">
                <h5 class="modal-title" id="exampleModalLabel">Editando usuário</h5>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loadingSpinnerUser">
                    <div class="spinner-border text-secondary my-5" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <form id="form-edit-user">
                    @csrf

                    <input type="hidden" name="id_user">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nome*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Informe o nome do usuário"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">E-mail*</label>
                        <input
                            class="form-control mb-3"
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Informe o e-mail do usuário"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label for="role" class="form-label">Permissão*</label>
                                <select class="form-select mb-3" name="role" id="role">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="first_access" class="form-label">1º acesso?*</label>
                                <select class="form-select mb-3" name="first_access" id="first_access">
                                    <option selected value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="active" class="form-label">Ativar usuário?*</label>
                                <select class="form-select mb-3" name="active" id="active">
                                    <option selected value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="password_user">
                                    Nova senha
                                    <span
                                        span class="d-inline-block"
                                        tabindex="0"
                                        data-bs-toggle="tooltip"
                                        title="Caso uma nova senha não seja informada, o usuário permanecerá com a senha atual."
                                    >
                                        <i
                                            class="fas fa-question-circle"

                                        ></i>
                                    </span>
                                </label>
                                <div class="input-password-eye-sign">
                                    <input
                                        class="form-control mb-3"
                                        type="password"
                                        id="password_user"
                                        name="password_user"
                                        placeholder="Nova senha do usuário"
                                        autocomplete="off"
                                    />
                                    <button type="button" class="eye-icon-password" title="Exibir/ocultar senha">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">&nbsp;</label>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="generate-new-password btn btn-info">
                                            <i class="fas fa-key"></i> Gerar senha
                                        </button>

                                        <span
                                            class="d-inline-block tooltip-copy"
                                            tabindex="0"
                                            data-bs-toggle="tooltip"
                                            data-bs-trigger="manual"
                                            title="Copiado!"
                                        >
                                            <button type="button" class="copy-to-clipboard btn btn-info">
                                                <i class="fas fa-copy"></i> Copiar
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0" id="modalFooterEditUser">
                <button type="button" class="btn btn-secondary cancel-user" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary update-user">Atualizar usuário</button>
            </div>
        </div>
    </div>
</div>
