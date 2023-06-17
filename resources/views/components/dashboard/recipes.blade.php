<section id="recipes" class="mt-5">
    <div class="container-custom">
        <div class="section-title d-flex align-items-end pb-2">
            <img class="me-2" src="{{ asset('/images/icons/up-icon.png') }}" alt="Ícone de cadastro de entradas">
            <h5 class="text-weight me-4">Entradas</h5>
            <button
                class="btn btn-primary"
                id="add-recipe"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#addRecipeModal"
                style="display: none"
            >
                Adicionar entrada
            </button>
        </div>
        <div class="accordion accordion-flush border shadow-sm mt-2" id="accordionResume">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-recipes"
                        aria-expanded="false"
                        aria-controls="collapse-recipes"
                        style="background-color: #9ac09a !important"
                    >
                        <strong>Todas as entradas</strong>
                    </button>
                </h2>
                <div id="collapse-recipes" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionResume">
                    <div class="accordion-body accordion-recipes">
                        @if (count($recipes) > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descrição da entrada</th>
                                        <th scope="col">Categoria</th>
                                        <th scope="col">Orçado (R$)</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-recipes">
                                    @foreach ($recipes as $recipe)
                                    <tr class="align-middle">
                                        <td>{{ $recipe->id }}</td>
                                        <td>{{ $recipe->description }}</td>
                                        <td>{{ $recipe->category_description }}</td>
                                        <td>{{ number_format($recipe->budgeted_amount, 2, ',', '.') }}</td>
                                        <td>
                                            @if (!$recipe->submitted_expense_id)
                                                <div class="input-group">
                                                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item d-flex align-items-center edit-recipe" id="{{ $recipe->id }}" data-bs-toggle="modal" data-bs-target="#editRecipeModal">
                                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                                            Editar
                                                        </a></li>
                                                        <li><a class="dropdown-item d-flex align-items-center delete-recipe" id="{{ $recipe->id }}">
                                                            <img class="me-2" src="{{ asset('/images/icons/del-icon.png') }}" alt="Remover">
                                                            Excluir
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            @else
                                            <div class="input-group" style="width: 72.46px !important;">
                                                <span
                                                    span class="d-inline-block"
                                                    tabindex="0"
                                                    data-bs-toggle="tooltip"
                                                    title="Receita com vínculo de recebimento por outro usuário. Não pode ser removida!"
                                                    style="width: 100% !important;"
                                                >
                                                    <button disabled class="btn btn-outline-secondary d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 100% !important;"></button>
                                                </span>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="align-middle no-border-bottom justify-content-end">
                                        <th></th>
                                        <td></td>
                                        <th id="total-amount" class="text-success">{{ number_format($budgeted_amount, 2, ',', '.') }}</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <span>Não há entradas lançadas.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
