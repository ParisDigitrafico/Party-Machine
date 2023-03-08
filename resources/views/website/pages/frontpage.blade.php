@extends('website.layouts.default')

@push('main')

<!-- Carousel Start -->
<div class="container-fluid p-0 mb-5">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="/static/website/template/aircon/img/carousel-1.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 pt-5">
                                <h1 class="display-4 text-white mb-4 animated slideInDown">We Provide Best AC Repair
                                    Services</h1>
                                <p class="fs-5 text-body mb-4 pb-2 mx-sm-5 animated slideInDown">Dar la mejor
                                    presentación y experiencia de usuario posible por un costo muy accequible.</p>
                                <a href="" class="btn btn-primary py-3 px-5 animated slideInDown">Explore More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="/static/website/template/aircon/img/carousel-2.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 pt-5">
                                <h1 class="display-4 text-white mb-4 animated slideInDown">Quality Heating & Air
                                    Condition Services</h1>
                                <p class="fs-5 text-body mb-4 pb-2 mx-sm-5 animated slideInDown">Aliqu diam amet diam et
                                    eos. Clita erat ipsum et lorem sed stet lorem sit clita duo justo erat amet</p>
                                <a href="" class="btn btn-primary py-3 px-5 animated slideInDown">Explore More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!-- Carousel End -->


<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="h-100">
                    <h1 class="display-6 mb-5">Welcome To Best Cooling & Heating Service Center</h1>
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 me-3"
                                    src="/static/website/template/aircon/img/icon/icon-07-primary.png" alt="">
                                <h5 class="mb-0">Expert Technician</h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 me-3"
                                    src="/static/website/template/aircon/img/icon/icon-09-primary.png" alt="">
                                <h5 class="mb-0">Best Quality Services</h5>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p>
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
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-end">
                        <img class="img-fluid w-75 wow zoomIn" data-wow-delay="0.1s"
                            src="/static/website/template/aircon/img/about-1.jpg" style="margin-top: 25%;">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid w-100 wow zoomIn" data-wow-delay="0.3s"
                            src="/static/website/template/aircon/img/about-2.jpg">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid w-50 wow zoomIn" data-wow-delay="0.5s"
                            src="/static/website/template/aircon/img/about-3.jpg">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid w-75 wow zoomIn" data-wow-delay="0.7s"
                            src="/static/website/template/aircon/img/about-4.jpg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


<!-- Facts Start -->
<div class="container-fluid facts my-5 py-5" data-parallax="scroll"
    data-image-src="/static/website/template/aircon/img/carousel-1.jpg">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                <span class="text-primary">Happy Clients</span>
            </div>
            <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                <span class="text-primary">Projects Succeed</span>
            </div>
            <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                <span class="text-primary">Awards Achieved</span>
            </div>
            <div class="col-sm-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                <span class="text-primary">Team Members</span>
            </div>
        </div>
    </div>
</div>
<!-- Facts End -->


<!-- Features Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <h1 class="display-6 mb-5">Few Reasons Why People Choosing Us!</h1>
                <p class="mb-5">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                    eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                <div class="d-flex mb-5">
                    <div class="flex-shrink-0 btn-square bg-primary rounded-circle" style="width: 90px; height: 90px;">
                        <img class="img-fluid" src="/static/website/template/aircon/img/icon/icon-08-light.png" alt="">
                    </div>
                    <div class="ms-4">
                        <h5 class="mb-3">Trusted Service Center</h5>
                        <span>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                            eos.</span>
                    </div>
                </div>
                <div class="d-flex mb-5">
                    <div class="flex-shrink-0 btn-square bg-primary rounded-circle" style="width: 90px; height: 90px;">
                        <img class="img-fluid" src="/static/website/template/aircon/img/icon/icon-10-light.png" alt="">
                    </div>
                    <div class="ms-4">
                        <h5 class="mb-3">Reasonable Price</h5>
                        <span>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                            eos.</span>
                    </div>
                </div>
                <div class="d-flex mb-0">
                    <div class="flex-shrink-0 btn-square bg-primary rounded-circle" style="width: 90px; height: 90px;">
                        <img class="img-fluid" src="/static/website/template/aircon/img/icon/icon-06-light.png" alt="">
                    </div>
                    <div class="ms-4">
                        <h5 class="mb-3">24/7 Supports</h5>
                        <span>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                            eos.</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="position-relative rounded overflow-hidden h-100" style="min-height: 400px;">
                    <img class="position-absolute w-100 h-100" src="/static/website/template/aircon/img/feature.jpg"
                        alt="" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Features End -->


<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-6 mb-5">We Provide professional Heating & Cooling Services</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <img class="img-fluid" src="/static/website/template/aircon/img/service-1.jpg" alt="">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="/static/website/template/aircon/img/icon/icon-01-light.png"
                                alt="">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Plantillas</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <img class="img-fluid" src="/static/website/template/aircon/img/service-2.jpg" alt="">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="/static/website/template/aircon/img/icon/icon-02-light.png"
                                alt="">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Sitio Web</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->


<!-- Quote Start -->
<div class="container-fluid overflow-hidden my-5 px-lg-0">
    <div class="container quote px-lg-0">
        <div class="row g-0 mx-lg-0">
            <div class="col-lg-6 quote-text" data-parallax="scroll"
                data-image-src="/static/website/template/aircon/img/carousel-1.jpg">
                <div class="h-100 px-4 px-sm-5 ps-lg-0 wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="text-white mb-4">Si tienes alguna consulta, Por favor contactanos</h1>
                    <p class="text-light mb-5">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam
                        amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna
                        dolore erat amet</p>
                    <a href="" class="align-self-start btn btn-primary py-3 px-5">More Details</a>
                </div>
            </div>
            <div class="col-lg-6 quote-form" data-parallax="scroll"
                data-image-src="/static/website/template/aircon/img/carousel-2.jpg">
                <div class="h-100 px-4 px-sm-5 pe-lg-0 wow fadeIn" data-wow-delay="0.5s">
                    <div class="bg-white p-4 p-sm-5">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="gname" placeholder="Gurdian Name">
                                    <label for="gname">Your Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="gmail" placeholder="Gurdian Email">
                                    <label for="gmail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cname" placeholder="Child Name">
                                    <label for="cname">Your Mobile</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="cage" placeholder="Child Age">
                                    <label for="cage">Service Type</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message"
                                        style="height: 80px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary py-3 px-5" type="submit">Get A Free Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quote End -->


<!-- Team Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-6 mb-5">Meet Our Professional Team Members</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item">
                    <div class="team-img position-relative overflow-hidden">
                        <img class="img-fluid" src="/static/website/template/aircon/img/team-1.jpg" alt="">
                        <div class="team-social">
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="bg-light text-center p-4">
                        <h5>Full Name</h5>
                        <span class="text-primary">Designation</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="team-item">
                    <div class="team-img position-relative overflow-hidden">
                        <img class="img-fluid" src="/static/website/template/aircon/img/team-2.jpg" alt="">
                        <div class="team-social">
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="bg-light text-center p-4">
                        <h5>Full Name</h5>
                        <span class="text-primary">Designation</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="team-item">
                    <div class="team-img position-relative overflow-hidden">
                        <img class="img-fluid" src="/static/website/template/aircon/img/team-3.jpg" alt="">
                        <div class="team-social">
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="bg-light text-center p-4">
                        <h5>Full Name</h5>
                        <span class="text-primary">Designation</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="team-item">
                    <div class="team-img position-relative overflow-hidden">
                        <img class="img-fluid" src="/static/website/template/aircon/img/team-4.jpg" alt="">
                        <div class="team-social">
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-square btn-primary rounded-circle m-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="bg-light text-center p-4">
                        <h5>Full Name</h5>
                        <span class="text-primary">Designation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->


<!-- Testimonial Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-6 mb-5">What They Say About Our Services</h1>
        </div>
        <div class="row g-5">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="testimonial-left h-100">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-1.jpg" alt="">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-2.jpg" alt="">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-3.jpg" alt="">
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="owl-carousel testimonial-carousel">
                    <div class="testimonial-item text-center">
                        <img class="img-fluid mx-auto mb-4" src="/static/website/template/aircon/img/testimonial-1.jpg"
                            alt="">
                        <p class="fs-5">Dolores sed duo clita tempor justo dolor et stet lorem kasd labore dolore lorem
                            ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor erat.</p>
                        <h5>Client Name</h5>
                        <span>Profession</span>
                    </div>
                    <div class="testimonial-item text-center">
                        <img class="img-fluid mx-auto mb-4" src="/static/website/template/aircon/img/testimonial-2.jpg"
                            alt="">
                        <p class="fs-5">Dolores sed duo clita tempor justo dolor et stet lorem kasd labore dolore lorem
                            ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor erat.</p>
                        <h5>Client Name</h5>
                        <span>Profession</span>
                    </div>
                    <div class="testimonial-item text-center">
                        <img class="img-fluid mx-auto mb-4" src="/static/website/template/aircon/img/testimonial-3.jpg"
                            alt="">
                        <p class="fs-5">Dolores sed duo clita tempor justo dolor et stet lorem kasd labore dolore lorem
                            ipsum. At lorem lorem magna ut et, nonumy et labore et tempor diam tempor erat.</p>
                        <h5>Client Name</h5>
                        <span>Profession</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="testimonial-right h-100">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-1.jpg" alt="">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-2.jpg" alt="">
                    <img class="img-fluid animated pulse infinite"
                        src="/static/website/template/aircon/img/testimonial-3.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->

@endpush

@push('js')
<script type="text/javascript">

</script>
@endpush