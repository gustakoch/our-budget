<section id="resume" class="mt-5">
    <div class="container-custom">
        <div class="section-title d-flex align-items-end pb-2">
            <img class="me-2" src="{{ asset('/images/icons/resume-icon.png') }}" alt="Ícone de resumo">
            <h5 class="text-weight">Visão geral do mês</h5>
        </div>
        <div class="accordion accordion-flush border shadow-sm mt-2" id="accordionResume">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button collapsed shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-resume"
                        aria-expanded="false"
                        aria-controls="collapse-resume"
                        style="background-color: #e7e188 !important;"
                    >
                        <strong>Resumo</strong>
                    </button>
                </h2>
                <div id="collapse-resume" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionResume">
                    <div class="d-flex">
                        <div class="my-recipes-and-expenses">
                            <h5>Minhas receitas e despesas</h5>
                            <div class="recipes-expenses">
                                <strong>Total de receitas <i class="fas fa-level-up-alt fa-lg text-success"></i></strong>
                                <span class="value green-background">R$ {{ number_format($resumeData['totals']['recipes'], 2, ',', '.')  }}</span>

                                <strong>Total de despesas <i class="fas fa-level-down-alt fa-lg text-danger"></i></strong>
                                <span class="value red-background">R$ {{ number_format($resumeData['totals']['expenses'], 2, ',', '.')  }}</span>

                                <strong>Saldo</i></i></strong>
                                <span
                                    class="value {{ $resumeData['totals']['diff'] >= 0 ? 'text-success' : 'text-danger' }}"
                                >
                                    R$ {{ number_format($resumeData['totals']['diff'], 2, ',', '.')  }}
                                </span>
                            </div>
                        </div>
                        <div class="expenses-going-to">
                            <h5>Para onde meus gastos estão indo?</h5>
                            @if (count($resumeData['expensesCategories']) > 0)
                                <div class="table-graph d-flex">
                                    <table class="table-resume">
                                        @foreach ($resumeData['expensesCategories'] as $expenseCategory)
                                            <tr class="table-tr">
                                                <td class="info-category-name">{{ $expenseCategory->category }}</td>
                                                <td
                                                    class="info-percentage"
                                                    style="
                                                        background-color: {{ $expenseCategory->color }} !important;
                                                        color: {{ $expenseCategory->color_brightness >= 128 || $expenseCategory->color_brightness == 0 ? '#333' : '#fff' }}"
                                                >
                                                    {{ number_format($expenseCategory->percentage, 2, ',', '.') }}%
                                                </td>
                                                <td class="info-value">R$ {{ number_format($expenseCategory->total, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="graph">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                            @else
                                <div class="no-expenses-yet">
                                    Não há despesas cadastradas! Para iniciar, cadastre uma despesa.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
