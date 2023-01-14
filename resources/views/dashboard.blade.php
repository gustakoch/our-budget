@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <div class="d-flex justify-content-between w-100">
        <div class="title-subtitle">
            <h3 class="mb-3">Dashboard</h3>
            <span>"Um plano não é nada, mas o planejamento é tudo." <small>(Dwight D. Eisenhower)</small></span>
        </div>

        {{-- <div class="btn-group notification-area">
            <button class="btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="color: #4b5da7">
                <i class="fas fa-bell fa-2x"></i> <span class="badge badge-notification">4</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-3 text-muted" aria-labelledby="dropdownMenuButton" style="width: 400px;">
                <p>Você recebeu uma nova notificação!</p>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ route('notifications.index') }}">Ver todas as notificações</a></li>
            </ul>
        </div> --}}
    </div>
</nav>

@include('components.dashboard.filters', [
    'years' => $years,
    'months' => $months
])
@include('components.dashboard.resume', [$resumeData['totals']])
@include('components.dashboard.recipes', [$recipes])
@include('components.dashboard.expenses', [
    'expenses' => $expenses,
    'creditCardExpenses' => $creditCardExpenses
])
@include('components.modals.add-recipe', ['recipeCategories' => $activeRecipeCategories])
@include('components.modals.edit-recipe', ['recipeCategories' => $activeRecipeCategories])
@include('components.modals.add-expense', [
    'expenseCategories' => $activeExpenseCategories,
    'type_expenses' => $type_expenses,
    'cards' => $cards,
    'installments' => $config['installments']
])
@include('components.modals.edit-expense', [
    'expenseCategories' => $activeExpenseCategories,
    'type_expenses' => $type_expenses,
    'installments' => $config['installments']
])
@include('components.modals.reason-cancellation')
@include('components.modals.reason-cancellation-all')
@include('components.modals.info-expense')
@include('components.modals.history-expenses')

@component('components.fab')@endcomponent

@endsection
