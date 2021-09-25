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

            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-plus"></i>
                Cadastros
            </a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="{{ route('categories.index') }}">Categorias</a>
                </li>
                <li>
                    <a href="{{ route('cards.index') }}">Cartões de crédito</a>
                </li>
            </ul>

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
        </li>
        <hr />
        @if (isset($_SESSION['monthName']) && isset($_SESSION['year']))
            <li style="padding: 10px; font-size: 1rem;">
                Perído selecionado: <br />
                <span style="font-size: 0.95rem;"> >> {{ $_SESSION['monthName'] ?? '' }} de {{ $_SESSION['year'] ?? '' }}</span>
            </li>
        @endif
        <li class="logout-li">
            <a href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </li>
    </ul>
</nav>
