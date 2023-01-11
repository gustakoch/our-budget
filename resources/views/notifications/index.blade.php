@extends('layouts.app')
@section('title', 'Notificações')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Notificações</h3>
    <span>Todas as notificações recebidas pelo sistema.</span>
</nav>

Em construção...

{{-- <section id="investments" class="mt-5">
    <div class="container-custom">
        <div class="section-title d-flex align-items-end pb-2">
            <img class="me-2" src="{{ asset('/images/icons/resume-icon.png') }}" alt="Ícone de resumo">
            <h5 class="text-weight">Visão geral dos investimentos</h5>
        </div>
        <div class="accordion accordion-flush border shadow-sm mt-2" id="accordionInvestments">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button
                        class="accordion-button collapsed shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-investments"
                        aria-expanded="false"
                        aria-controls="collapse-investments"
                        style="background-color: #e7e188 !important;"
                    >
                        <strong>Meus investimentos</strong>
                    </button>
                </h2>
                <div id="collapse-investments" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionInvestments">
                    <div class="d-flex">
                        <div class="my-recipes-and-expenses">
                            <div class="recipes-expenses">
                                <strong>Total investido</strong>
                                <span class="value green-background">R$ {{ number_format(1000, 2, ',', '.')  }}</span>

                                <strong>Disponível para investir</strong>
                                <span class="value blue-background">R$ {{ number_format(2000, 2, ',', '.')  }}</span>
                            </div>
                        </div>
                        <div class="expenses-going-to">
                            <h5>Ondes estão os meus investimentos?</h5>
                            @if (0 > 0)
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
                                    Não há investimentos cadastrados!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

@endsection
