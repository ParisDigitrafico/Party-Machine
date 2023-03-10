@extends('website.layouts.cliente')

@push("main")
<div class="content dash pt-4">
    <div class="container">
        <div class="row d-flex">


            <div class="col-12 py-4">
                <h3>Mi Perfil</h3>
            </div>

            <div class="col-12 col-sm-6 col-lg-4 mb-2">
            <a href="/cliente/pedidos" style="color: black;text-decoration: none;">
                <div class="card flex-row" style="width:100%; border-radius: 10px;">
                <div class="card-body">
                <h5 class="card-title"><i class="icon-shopping-bag"></i>Mis Pedidos</h5>
                <p class="card-text">Brinda seguimiento a tus compras.</p>
                <a href="" class="btn border">
                    <span>Ir</span>
                </a>
                </div>
                </div>
            </a>
            </div>


            <div class="col-12 col-sm-6 col-lg-4 mb-2">
            <a href="/cliente/cuenta/" style="color: black;text-decoration: none;">
            <div class="card" style="width:100%; border-radius: 10px;">
            <!--<img class="card-img-top" src="..." alt="Card image cap"> -->
            <div class="card-body">
            <h5 class="card-title"><i class="icon-lock"></i> Perfil y seguridad</h5>
            <p class="card-text">Cambiar datos personales.</p>
            <a href="/cliente/cuenta/" class="btn">
                            <span>Ir</span>
                </a>
            </div>
            </div>
            </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4 mb-2">
            <a href="/cliente/login/close/" style="color: black;text-decoration: none;">
            <div class="card" style="width:100%; margin-top: 14px;border-radius: 10px;">
            <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
            <div class="card-body">
            <h5 class="card-title"><i class="icon-sign-out"></i> Cerrar sesión</h5>
            <p class="card-text">Cerrar sesión en este dispositivo o navegador.</p>
            <a href="/cliente/login/close/" class="btn">
                            <span>Ir</span>
                </a>
            </div>
            </div>
            </a>
            </div>

        </div>
    </div>
</div>
@endpush