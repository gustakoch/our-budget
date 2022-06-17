@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Dashboard</h3>
    <span>"Um plano não é nada, mas o planejamento é tudo." <small>(Dwight D. Eisenhower)</small></span>
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
@include('components.modals.edit-recipe', ['recipeCategories' => $allRecipeCategories])
@include('components.modals.add-expense', [
    'expenseCategories' => $activeExpenseCategories,
    'type_expenses' => $type_expenses,
    'cards' => $cards,
    'installments' => $config['installments']
])
@include('components.modals.edit-expense', [
    'expenseCategories' => $allExpenseCategories,
    'type_expenses' => $type_expenses,
    'installments' => $config['installments']
])
@include('components.modals.reason-cancellation')
@include('components.modals.reason-cancellation-all')
@include('components.modals.info-expense')
@include('components.modals.history-expenses')

@component('components.fab')@endcomponent

@endsection
