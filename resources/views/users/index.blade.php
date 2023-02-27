@extends('layouts.app')
@section('title', 'Usuários')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <h3 class="mb-3">Usuários</h3>
    <span>Cadastro de usuários</span>
</nav>

<button
    class="btn btn-primary mb-4"
    data-bs-toggle="modal"
    data-bs-target="#addUserModal"
>
    Novo usuário
</button>

<table id="dataTable" class="table" style="vertical-align: middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Permissão</th>
            <th>1º acesso</th>
            <th>Ativo</th>
            <th>Data de cadastro</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            @if (session('user')['role'] == '1')
                @if ($user->id == session('user')['id'])
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} <strong>(Eu)</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a
                                            class="dropdown-item d-flex align-items-center edit-user"
                                            id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                        >
                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a
                                            class="dropdown-item d-flex align-items-center edit-user"
                                            id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                        >
                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif
            @elseif (session('user')['role'] == '2')
                @if ($user->role == '1')
                    <tr class="text-gray">
                        <td>-</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <span
                                    span class="d-inline-block"
                                    tabindex="0"
                                    data-bs-toggle="tooltip"
                                    title="Você não possui permissão de editar este usuário."
                                >
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                        disabled="disabled"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="cursor: not-allowed;"
                                    >
                                        <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                    </button>
                                </span>
                            </div>
                        </td>
                    </tr>
                @elseif ($user->id == session('user')['id'])
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} <strong>(Eu)</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a
                                            class="dropdown-item d-flex align-items-center edit-user"
                                            id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                        >
                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @elseif ($user->role == '2' && $user->id != session('user')['id'])
                    <tr class="text-gray">
                        <td>-</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <span
                                    span class="d-inline-block"
                                    tabindex="0"
                                    data-bs-toggle="tooltip"
                                    title="Você não possui permissão de editar este usuário."
                                >
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                        disabled="disabled"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="cursor: not-allowed;"
                                    >
                                        <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                    </button>
                                </span>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a
                                            class="dropdown-item d-flex align-items-center edit-user"
                                            id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                        >
                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif
            @elseif (session('user')['role'] == '3')
                @if ($user->id == session('user')['id'])
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} <strong>(Eu)</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a
                                            class="dropdown-item d-flex align-items-center edit-user"
                                            id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                        >
                                            <img class="me-2" src="{{ asset('/images/icons/edit-icon.png') }}" alt="Editar">
                                        Editar
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="text-gray">
                        <td>-</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_name }}</td>
                        <td>{{ $user->first_access }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="input-group">
                                <span
                                    span class="d-inline-block"
                                    tabindex="0"
                                    data-bs-toggle="tooltip"
                                    title="Você não possui permissão de editar este usuário."
                                >
                                    <button
                                        class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center btn-options"
                                        disabled="disabled"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="cursor: not-allowed;"
                                    >
                                        <svg height="14" widht="14" style="enable-background:new 0 0 24 24;" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="info"/><g id="icons"><path d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z" id="settings"/></g></svg>
                                    </button>
                                </span>
                            </div>
                        </td>
                    </tr>
                @endif
            @endif
        @endforeach
    </tbody>
</table>

@component('users.modal.add', [
    'roles' => $roles,
    'password' => $password
])@endcomponent
@component('users.modal.edit', ['roles' => $roles])@endcomponent

@endsection
