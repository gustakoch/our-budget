@extends('layouts.app')
@section('title', 'Página não encontrada')

@section('content')

<div class="container-404">
    <img src="{{ asset('images/mike.png') }}" alt="Mike Wazowski">
    <p class="h2">Oops! Não encontramos essa página.</p>
    <p>Acho que você escolheu a porta errada, porque eu não consegui dar uma olhada na que você está procurando.</p>

    <a class="btn btn-primary" href="{{ route('dashboard') }}">Voltar ao Dashboard</a>
</div>

@endsection
