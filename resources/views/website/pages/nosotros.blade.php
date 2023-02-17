@extends('website.layouts.default')

<style>
    .img-default
    {
        width: 50px;
        height: auto;
    }
</style>

@push("main")

<!-- Page Header Start -->
<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white animated slideInDown mb-4">Nosotros</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nosotros</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.5s">
                <div class="row h-100">
                    <h1 class="display-6 mb-5 at text-center">Bienvenidos a Party Machine</h1>
                    <div class="row g-4 mb-1">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0">Historia</h5>
                            </div>
                        </div>
                        <p class="mb-4">En el año 2023 como respuesta a la pandemia en curso y el impacto ambiental
                            actual, aprovechando todas las nuevas tendencias y formatos de creación nueva para eventos
                            sociales. Decidimos montarnos en este negocio para poder aprovechar todo nuestro
                            conocimiento y tecnologías, para crear invitaciones innovadoras, frescas y funcionales, que
                            les genere una empatía inmediata a todos nuestros clientes. Familiarizándolos así a su
                            evento de una manera digital y brindar ese plus extra a su evento. Así es como en enero de
                            2023 decidimos crear Party machine.</p>
                    </div>

                    <div class="row g-4 mb-1 col-lg-6">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                            <img class="img-default" src="/static/website/images/mision.png" alt="">&nbsp;&nbsp;
                                <h5 class="mb-0">Misión</h5>
                            </div>
                        </div>
                        <p>Ser una opción ecológica para generar productos para fiestas y eventos sociales de manera
                            personalizada, accesible para todos gustos y bolsillos. Disponibles en todo México como el
                            proveedor número 1 de invitaciones digitales.</p>
                    </div>

                    <div class="row g-4 mb-1 col-lg-6">
                        
                    </div>

                    <div class="row g-4 mb-1 col-lg-6">
                        
                    </div>

                    <div class="row g-4 mb-1 col-lg-6">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                            <img class="img-default" src="/static/website/images/vision.png" alt="">&nbsp;&nbsp;
                                <h5 class="mb-0">Visión</h5>
                            </div>
                        </div>
                        <p>Ser la empresa más importante de México en elaboración de invitaciones digitales y llegar a
                            miles de personas diariamente con nuestros diseños frescos, originales y sobre todo
                            funcionales.
                        </p>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0">Nuestros Valores</h5>
                            </div>
                        </div>

                    </div>

                    <ul>
                        <li>Seguridad</li>
                        <li>Honestidad</li>
                        <li>Innovación</li>
                        <li>Diseño</li>
                        <li>Sofisticación</li>
                        <li>Rapidez</li>
                    </ul>

                    <div class="border-top mt-4 pt-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3">
                                        <i class="fa fa-phone-alt text-white"></i>
                                    </div>
                                    <h5 class="mb-0">+012 345 6789</h5>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary rounded-circle me-3">
                                        <i class="fa fa-envelope text-white"></i>
                                    </div>
                                    <h5 class="mb-0">info@example.com</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-end">
                        <img class="img-fluid w-75 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg" style="margin-top: 25%;">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid w-100 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid w-50 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid w-75 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg">
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- About End -->

@endpush