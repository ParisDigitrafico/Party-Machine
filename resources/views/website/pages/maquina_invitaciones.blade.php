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
        <h1 class="display-4 text-white animated slideInDown mb-4">{{ $pagina->nombre }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pagina->nombre }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container">
    <div class="row">

        <!-- Search Filter Start -->
        <div class="col-lg-12">
            <form class="form-search-filter">
                <div class="inner-form">
                    <div class="input-field first-wrap">
                        <div class="input-select">
                            <select class="select-category">
                                <option selected disabled placeholder="">Category</option>
                                <option>New Arrivals</option>
                                <option>Sale</option>
                                <option>Ladies</option>
                                <option>Men</option>
                                <option>Clothing</option>
                                <option>Footwear</option>
                                <option>Accessories</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-field second-wrap">
                        <input id="search" type="text" placeholder="Enter Keywords?" />
                    </div>
                    <div class="input-field third-wrap">
                        <button class="btn-search" type="button">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Search Filter End -->

        <!-- Card Start -->
        <div class="col-lg-12">
            <div class="row">
                @foreach($data as $item)
                <div class="card card_producto" style="width: 16rem;padding: 0;margin: 1rem 1rem;border: none;">
                    <div class="card-img">
                        <img class="card-img-top img-fluid" src="{{ $item->photo()->url}}" alt="Foto">
                    </div>

                    <div class="card-container">
                        <div class="card-info">
                            <div style="padding-bottom: 6px;">{{ $item->nombre }}</div>
                            <div style="letter-spacing: 1;color: black;">${{ $item->precio }}</div>
                        </div>

                        <div class="icon_cart">
                            <a href="#" class="btn btnAddCarrito" style="padding-top: 1rem;" data-loading=""
                                data-producto-id="{{ $item['id'] }}"><span><i
                                        class="fas fa-shopping-cart"></i></span></a>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        <!-- Card End -->

    </div>
</div>

@endpush