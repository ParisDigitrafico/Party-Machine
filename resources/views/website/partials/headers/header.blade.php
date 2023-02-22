    <!-- Topbar Start -->
    <!--<div class="container-fluid bg-dark text-white-50 py-2 px-0 d-none d-lg-block">
    <div class="row gx-0 align-items-center">
        <div class="col-lg-7 px-5 text-start">
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="fa fa-phone-alt me-2"></small>
                <small>+012 345 6789</small>
            </div>
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="far fa-envelope-open me-2"></small>
                <small>info@example.com</small>
            </div>
        </div>
        <div class="col-lg-5 px-5 text-end">
            <ol class="breadcrumb justify-content-end mb-0">
                <li class="breadcrumb-item"><a class="text-white-50 small" href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a class="text-white-50 small" href="#">Quienes Somos</a></li>
                <li class="breadcrumb-item"><a class="text-white-50 small" href="#">Contacto</a></li>
            </ol>
        </div>
    </div>
</div>-->
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5">
        <a href="index.html" class="navbar-brand d-flex align-items-center">
            <img src="/static/sistema/images/logo.png" class="img-fluid" alt="Sample image">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto bg-light pe-4 py-3 py-lg-0">
                @php
                $arrMenu = get_menu_website();
                @endphp

                @foreach($arrMenu as $Menu)

                @php
                $Menu["nombre"] = $Menu["nombre_" . app()->getLocale()] ?? $Menu["nombre"];
                @endphp

                <a href="{{ get_locale_url($Menu['url']) }}" class="nav-item nav-link">{{ $Menu["nombre"] }}</a>

                @endforeach
            </div>
            <div class="h-100 d-lg-inline-flex align-items-center d-none">
                <a class="btn btn-square rounded-circle bg-light text-primary me-2 btnCart"
                    data-pedido="{{ session('ckeypedido') }}" href=""><i class="fa fa-shopping-cart"
                        aria-hidden="true"></i>
                    <span class="text total" style="font-size: 16pt"></span>
                </a>
                <a class="btn btn-square rounded-circle bg-light text-primary me-0" href="/cliente/login/"
                    title="Sign In"><i class="fas fa-sign-in-alt"></i></a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->