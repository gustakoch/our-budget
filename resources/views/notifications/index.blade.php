@extends('layouts.app')
@section('title', 'Notificações')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Central de Notificações</h3>
    <span>Todas as notificações recebidas pelo sistema.</span>
</nav>

<table id="dataTable" class="table" style="vertical-align: middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Visualizado?</th>
            <th>Recebido em</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($notifications as $notification)
            <tr>
                <td>{{ $notification->id }}</td>
                <td>{{ $notification->title }}</td>
                <td>{{ $notification->text }}</td>
                <td>{{ $notification->viewed == 1 ? 'Sim' : 'Não' }}</td>
                <td>{{ $notification->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
