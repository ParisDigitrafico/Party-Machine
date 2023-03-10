<!-- Navbar Start -->
<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top px-4 px-lg-5">
        <a href="/" class="navbar-brand d-flex align-items-center">
            <img class="img-fluid" src="/static/sistema/images/logo.png" alt="">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav pe-4 py-3 py-lg-0 me-5 m-auto">
                @php
                $arrMenu = get_menu_website();
                @endphp

                @foreach($arrMenu as $Menu)

                @php
                $Menu["nombre"] = $Menu["nombre_" . app()->getLocale()] ?? $Menu["nombre"];
                @endphp

                <a href="{{ get_locale_url($Menu['url']) }}" class="nav-item nav-link text-uppercase {{ $Menu["nombre"] }}">{{ $Menu["nombre"] }}</a>

                @endforeach
            </div>
            <div class="h-100 d-lg-inline-flex align-items-center d-none">
                <a class="btn btn-square rounded-circle bg-light text-primary me-2 btnCart"
                    data-pedido="{{ session('ckeypedido') }}" href=""><i class="fa fa-shopping-cart"
                        aria-hidden="true"></i>
                    <span class="text total" style="font-size: 10pt;padding-bottom: ;padding: 0 0 1rem 3px;"></span>
                </a>

                @if(session("app") == "cliente" && intval(session("usuario_id")) > 0)

                <!-- <a class="btn btn-square rounded-circle bg-light text-primary me-0" href="/cliente/login/close/"
                title="Salir"><i class="fas fa-sign-out-alt"></i></a> -->

                <div class="dropdown">
                    <a class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ session("usuario_nombre_completo") }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/cliente/">Mi Perfil</a>
                        <a class="dropdown-item" href="/cliente/pedidos/">Mis Pedidos</a>
                        <a class="dropdown-item" href="/cliente/login/close/">Cerrar Sesión</a>
                    </div>
                </div>

                @else
                <a class="btn btn-square rounded-circle bg-light text-primary me-0" href="/cliente/login/"
                    title="Sign In"><i class="fas fa-sign-in-alt"></i></a>
                @endif

            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->