<nav class="navbar navbar-expand-lg bg-light navbar-light">
    <div class="container-fluid">
        <!-- Logo dan Judul -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('icon/logo.png') }}" alt="Logo" style="height: 32px; margin-right: 10px;">
            <span> {{ $title ?? 'RuangKita UGM' }}</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Kanan -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Menu Navigasi -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}"><i class="fa-solid fa-table"></i> Tabel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('map') }}"><i class="fa-solid fa-map"></i> Peta</a>
                </li>
            </ul>
            @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="nav-link btn btn-link" type="submit">
                                <i class="fa-solid fa-right-from-bracket"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            @endauth
            @guest
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-from-bracket"></i> Log In
                        </a>
                    </li>
                </ul>
            @endguest
        </div>
    </div>
</nav>
