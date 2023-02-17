@extends('website.layouts.default')

<style type="text/css">
.card-container {
    display: flex;
}

.card-info {
    width: calc(100% - 40px);
    font-size: 14px;
    padding-top: 14px;
    line-height: 1.4;
}

.icon_cart {
    width: 40px;
}
</style>

@push('main')

<!-- Page Header Start -->
<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white animated slideInDown mb-4">Productos</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Productos</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container">
    <div class="row">

        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100"
                data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categorias</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0"
                id="navbar-vertical">

                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-none text-white w-100"
                    data-toggle="collapse" href="#list-plantillas"
                    style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Plantillas</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border-top-0 border-bottom-0"
                    id="list-plantillas">

                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        <a href="/productos/plantilla" class="nav-item nav-link">Todos</a>
                        @foreach($categorias as $item)
                        <a href="" class="nav-item nav-link">{{ $item->nombre }}</a>
                        @endforeach
                    </div>
                </nav>
        </div>
        </nav>


        <!-- <div class="col-3">

            <form action="/productos/$tipo/" . method="GET">
                <select class="categorias">
                    <option value="" disabled selected hidden>Categorias</option>
                    @foreach($categorias as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
                <button type="submit" class="search"><i class="fa fa-search"></i></button>
            </form>

        </div> -->

        <div class="col-9">
            <div class="row">
                @foreach($data as $item)
                <div class="card card_producto_" style="width: 16rem;padding: 0;margin: 1rem 1rem;border: none;">
                    <div class="card-img">
                        <img class="card-img-top img-fluid" src="{{ $item->photo()->url}}" alt="Foto">
                    </div>

                    <div class="card-container">
                        <div class="card-info">
                            <div style="padding-bottom: 6px;">{{ $item->nombre }}</div>
                            <div style="letter-spacing: 1;color: black;">${{ $item->precio }}</div>
                        </div>

                        <div class="icon_cart">
                            <!-- <a href="" class="btn btnAddCarrito" style="padding-top: 1rem;"><i
                                    class="fas fa-shopping-cart"></i></a> -->
                                    
                            <a href="#" class="btn btnAddCarrito" data-loading=""
                                data-producto-id="{{ $item['id'] }}"><span><i
                                    class="fas fa-shopping-cart"></i></span></a>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endpush

@push("js")

<script type="text/javascript">
$(document).ready(function() {

    $(".btnAddCarrito").click(function() {
        alert("ok");
    })
});
</script>

@endpush