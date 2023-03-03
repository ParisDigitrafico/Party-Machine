@extends('website.layouts.cliente')

@push("main")
<style>
.h-custom {
    height: calc(100% - 73px);
}

@media (max-width: 450px) {
    .h-custom {
        height: 100%;
    }
}

.section-login {
    height: 75vh;
}
</style>


<div class="area_cliente">
    <div class="container">
        <div class="row">
            <section class="section-login">
                <div class="container-fluid h-custom">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-md-9 col-lg-6 col-xl-5">
                            <img src="\static\sistema\images\logo.png" class="img-fluid" alt="Sample image">
                        </div>
                        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                            <form action="/cliente/login/authentication/" method="post">
                                @csrf

                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input name="email" type="email" id="form3Example3"
                                        class="form-control form-control-lg" placeholder="{{ trans('auth.email') }}"
                                        value="<?= (!empty($cAux = request()->get("email64")) ? base64_decode($cAux):"") ?>" />
                                    <!-- <label class="form-label" for="form3Example3">Correo Electronico</label> -->
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-3">
                                    <input name="password" type="password" id="form3Example4"
                                        class="form-control form-control-lg" placeholder="{{ trans('auth.password') }}" />
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Checkbox -->
                                    <div class="form-check mb-0">
                                        <input class="form-check-input me-2" type="checkbox" value=""
                                            id="form2Example3" name="rememberme" />
                                        <label class="form-check-label" for="form2Example3">
                                        {{ trans('auth.rememberme') }}
                                        </label>
                                    </div>
                                    <a href="/cliente/login/forgot/" class="text-body">{{ trans('auth.forgot_password') }}</a>
                                </div>

                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <button type="submit" class="btn btn-primary btn-lg"
                                        style="padding: .5rem 2rem;width: 100%;">{{ trans('auth.login') }}</button>
                                    <p class="small fw-bold mt-2 pt-1 mb-0">{{ trans('auth.dont_have_an_account') }}
                                        <a href="/cliente/login/register/" class="link-dark">{{ trans('auth.register') }}</a>
                                    </p>
                                </div>
                            </form>
                            @if(request()->get("a"))
                            <div style="margin-top:15px;"><span style="color: #669933"><b>Su cuenta ha sido activada
                                        correctamente, favor de iniciar sesión para continuar.</b></span></div><br>
                            @elseif(request()->get("e"))
                            <div style="margin-top:15px;"><span style="color: #CC0000"><b>El correo electrónico o la
                                        contraseña son incorrectos, favor de volver a intentar.</b></span></div><br>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</div>
@endpush