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
                    @if (session('user')['role'] == '1' || session('user')['role'] == '2')
                    <li>
                        <a href="{{ route('users.index') }}">Usuários</a>
                    </li>
                    @endif
                </ul>

                <li>
                    <a href="#menuRelatorios" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-newspaper"></i>
                        Relatórios
                    </a>
                    <ul class="collapse list-unstyled" id="menuRelatorios">
                        <li>
                            <a href="{{ route('report.expenses.category') }}">Despesas por categorias</a>
                        </li>
                    </ul>
                </li>
            @endif

            <li>
                @if (session('user')['firstAccess'] != 1)
                    <a href="{{ route('config') }}">
                        <i class="fas fa-cogs"></i>
                        Configurações
                    </a>
                @else
                    <a href="{{ route('initial') }}">
                        <i class="fas fa-cogs"></i>
                        Configurações iniciais
                    </a>
                @endif
            </li>
            {{-- <li>
                <a href="{{ route('tickets') }}">
                    <i class="fas fa-ticket-alt"></i>
                    Tickets
                </a>
            </li> --}}
        </li>
        <hr />
        @if (isset($_SESSION['monthName']) && isset($_SESSION['year']))
            <li style="padding: 10px; font-size: 1rem;">
                Período selecionado: <br />
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
