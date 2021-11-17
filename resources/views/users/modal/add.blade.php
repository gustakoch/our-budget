<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar novo usuário</h5>
            </div>
            <div class="modal-body">
                <form id="form-new-user">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name_user">Nome*</label>
                        <input
                            class="form-control mb-3"
                            type="text"
                            id="name_user"
                            name="name_user"
                            placeholder="Informe o nome do usuário"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email_user">E-mail*</label>
                        <input
                            class="form-control mb-3"
                            type="email"
                            id="email_user"
                            name="email_user"
                            placeholder="Informe o e-mail do usuário"
                            autocomplete="off"
                        />
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label for="role_user" class="form-label">Permissão*</label>
                                <select class="form-select mb-3" name="role_user" id="role_user">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="first_access_user" class="form-label">1º acesso?*</label>
                                <select class="form-select mb-3" name="first_access_user" id="first_access_user">
                                    <option selected value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="active_user" class="form-label">Ativar usuário?*</label>
                                <select class="form-select mb-3" name="active_user" id="active_user">
                                    <option selected value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="password_user">Senha*</label>
                                <div class="input-password-eye-sign">
                                    <input
                                        class="form-control mb-3"
                                        type="password"
                                        id="password_user"
                                        name="password_user"
                                        placeholder="Informe a senha do usuário"
                                        autocomplete="off"
                                        value="{{ $password }}"
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

                <small>(* Dados obrigatórios)</small>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary cancel-user" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary save-user">
                    <i class="fas fa-save"></i> Salvar usuário
                </button>
            </div>
        </div>
    </div>
</div>
