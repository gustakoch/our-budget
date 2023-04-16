@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@include('components.nav', [
    'titleNav' => 'Dashboard',
    'subtitleNav' => '"Um plano não é nada, mas o planejamento é tudo." (Dwight D. Eisenhower)'
])

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
    'cards' => $activeCards,
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
