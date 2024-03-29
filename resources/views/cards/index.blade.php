@extends('layouts.app')
@section('title', 'Configurações')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Cartões de crédito</h3>
    <span>Cadastro de cartões de crédito</span>
</nav>

<button
    class="btn btn-success mb-4"
    data-bs-toggle="modal"
    data-bs-target="#addCardModal"
>
    Novo cartão
</button>

<table id="dataTable" class="table" style="vertical-align: middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Descrição</th>
            <th>Número</th>
            <th>Bandeira</th>
            <th>Vencimento da fatura</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cards as $card)
            <tr>
                <td>{{ $card->id }}</td>
                <td>{{ $card->description }}</td>
                <td>{{ $card->number ? substr_replace($card->number, '****-****-****-', 0, 15) : '--' }}</td>
                <td>{{ $card->card_flag_name ?? '--' }}</td>
                <td>Sempre dia {{ $card->invoice_day }}</td>
                <td>
                    <label class="switch" title="{{ $card->active == 1 ? 'Inativar cartão' : 'Ativar cartão'}}">
                        <input class="onoffelement" type="checkbox" value="{{ $card->active }}" id="{{ $card->id }}" {{ $card->active == 1 ? 'checked' : ''}} />
                        <span class="slider round"></span>
                    </label>
                </td>
                <td>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a
                                    class="dropdown-item d-flex align-items-center edit-card"
                                    id="{{ $card->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCardModal"
                                >
                                    <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                Editar
                            </a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@component('cards.modal.add', ['flags' => $flags])@endcomponent
@component('cards.modal.edit', ['flags' => $flags])@endcomponent

@endsection
