@extends('website.layouts.default')

<style>
.card-registration .select-input.form-control[readonly]:not([disabled]) {
    font-size: 1rem;
    line-height: 2.15;
    padding-left: .75em;
    padding-right: .75em;
}

.card-registration .select-arrow {
    top: 13px;
}

.bg-grey {
    background-color: #eae8e8;
}

@media (min-width: 992px) {
    .card-registration-2 .bg-grey {
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
    }
}

@media (max-width: 991px) {
    .card-registration-2 .bg-grey {
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
    }
}
</style>

@push("main")
<section class="area_carrito" style="background-color: transparent;">
    <div class="container py-5 ">
        <div class="row d-flex justify-content-center align-items-center ">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-8">
                                <div class="p-5">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h1 class="fw-bold mb-0 text-black">Carrito</h1>
                                        <!-- <h6 class="mb-0 text-muted total">3 items</h6> -->

                                    </div>
                                    <hr class="my-4">

                                    <div class="items-card">

                                        @if(!empty($detalles = $data->detalles))
                                        @foreach($detalles as $item)

                                        <div
                                            class="row mb-4 d-flex justify-content-between align-items-center div-item">
                                            <div class="col-md-2 col-lg-2 col-xl-2">
                                                <img src="{{ $item->producto->photo()->url }}"
                                                    class="img-fluid rounded-3" alt="Foto">
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-xl-3">
                                                <h6 class="text-muted">{{ $item->producto->tipo }}</h6>
                                                <h6 class="text-black mb-0">{{$item->producto_nombre}}</h6>
                                            </div>

                                            <!-- <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                            <button class="btn btn-link px-2"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <input id="form1" min="0" name="quantity" value="1" type="number"
                                                class="form-control form-control-sm" />

                                            <button class="btn btn-link px-2"
                                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div> -->

                                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                <h6 class="mb-0">${{ number_format($item->precio, 2) }} MXN</h6>
                                            </div>
                                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                <a href="#!" class="text-muted btnRemove"
                                                    data-ckey="{{ $data['ckey'] }}"
                                                    data-producto-id="{{ $item['producto_id'] }}"><i
                                                        class="fas fa-times"></i></a>
                                            </div>
                                        </div>

                                        @endforeach
                                        @endif
                                    </div>

                                    <hr class="my-4">

                                    <div class="pt-5">
                                        <h6 class="mb-0"><a href="/productos/" class="text-body"><i
                                                    class="fas fa-long-arrow-alt-left me-2"></i>Regresar</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 bg-grey">
                                <div class="p-5">
                                    <h3 class="fw-bold mb-5 mt-2 pt-1">Resumen</h3>
                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-4">
                                        <h5 class="text-uppercase">items</h5>
                                        <h5>{{ $data->cantidad }}</h5>
                                    </div>

                                    <!-- <h5 class="text-uppercase mb-3">Shipping</h5>

                                    <div class="mb-4 pb-2">
                                        <select class="select">
                                            <option value="1">Standard-Delivery- €5.00</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                            <option value="4">Four</option>
                                        </select>
                                    </div>

                                    <h5 class="text-uppercase mb-3">Give code</h5>

                                    <div class="mb-5">
                                        <div class="form-outline">
                                            <input type="text" id="form3Examplea2"
                                                class="form-control form-control-lg" />
                                            <label class="form-label" for="form3Examplea2">Enter your code</label>
                                        </div>
                                    </div> -->

                                    <hr class="my-4">

                                    <div class="d-flex justify-content-between mb-5">
                                        <h5 class="text-uppercase">total</h5>
                                        <h5>${{ $data->total }} MXN</h5>
                                    </div>

                                    <!-- <button type="button" class="btn btn-dark btn-block btn-lg"
                                        data-mdb-ripple-color="dark">Register</button> -->

                                    <h6><a href="#" class="text-body btnSiguiente"
                                            data-cliente_id="{{ intval(session('usuario_id')) }}"
                                            data-url="/carrito/{{ $data['ckey'] }}/resumen/">
                                            <span>Siguiente Paso</span><i class="fas fa-long-arrow-alt-right ms-2"></i>
                                        </a>
                                    </h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endpush