<nav id="sidebar">
    <div class="sidebar-header">
        <h3>Our <br /> Budget</h3>
        <strong>OB</strong>

        <div class="user-info">
            <small>Olá, {{ session('user')['name'] }}</small>
        </div>
    </div>

    <ul class="list-unstyled components">
        <li>
            @if (session('user')['firstAccess'] != 1)
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            @endif
            {{-- <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-copy"></i>
                Pages
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
            </ul> --}}
        </li>
        <li>
            @if (session('user')['firstAccess'] != 1)
                <a href="{{ route('config') }}">
                    <i class="fas fa-cogs"></i>
                    Configurações
                </a>
            @else
                <a href="{{ route('initial') }}">
                    <i class="fas fa-cogs"></i>
                    Bem vindo
                </a>
            @endif

        </li>
        <hr />
        <li>

            <a href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </li>
    </ul>
</nav>
