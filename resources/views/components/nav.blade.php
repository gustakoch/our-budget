<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex flex-column justify-content-start align-items-start">
    <div class="d-flex justify-content-between w-100">
        <div class="title-subtitle">
            <h3 class="mb-3">{{ $titleNav }}</h3>
            <span>{{ $subtitleNav }}</span>
        </div>

        <div class="btn-group notification-area">
            <button
                type="button"
                class="btn not-clickable"
                data-bs-toggle="popover"
                data-bs-placement="left"
                title="Notificações"
                data-bs-content="Você tem novas notificações. Clique no balão para visualizar."
                id="toggle-new-system-notifications"
            ></button>
            <button
                class="btn"
                type="button"
                id="notification-button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="color: #4b5da7"
            >
                <i class="fas fa-bell fa-2x"></i>
                @if ($numberNotifications > 0)
                    <span class="badge badge-notification">
                        {{ $numberNotifications }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end text-muted list-notification" aria-labelledby="notification-button" style="">
                <h3 class="p-3 popover-header" style="font-size: 1.15rem;">Central de Notificações</h3>

                @if (count($notifications) > 0)
                    @foreach ($notifications as $notification)
                        <li class="p-3" style="background-color: {{ $notification->viewed == 0 ? '#d4dcfd' : 'none' }}">
                            <div>
                                <strong>{{ $notification->title }}</strong><br />
                                <span>{{ $notification->text }}</span>
                                <small class="message-datetime">{{ Helper::getElapsedTime($notification->created_at) }}</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider p-0 m-0"></li>
                    @endforeach

                    <li><hr class="dropdown-divider p-0 m-0"></li>
                    <li class="text-center" style="padding: 0.75rem;">
                        <a href="{{ route('notifications.index') }}" style="font-size: 14px;">Ver todas as notificações</a>
                    </li>
                @else
                    <li class="p-3">Não há notificações para serem exibidas.</li>
                @endif
            </ul>
        </div>
    </div>
</nav>
