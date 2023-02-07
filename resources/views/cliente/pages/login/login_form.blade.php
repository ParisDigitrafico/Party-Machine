@extends('website.layouts.cliente')

@push("main")
<div class="login">
    <section>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 offset-lg-3 col-lg-6 cont_form">
                    <div class="cont">
                        <h2>INICIAR SESIÓN</h2>
                        <p>Si tienes una cuenta, inicia sesión con tu correo electrónico.</p>

                        <form action="/cliente/login/authentication/" method="post">
                            @csrf

                            <!-- Email input -->
                            <div class="form-outline">
                                <input type="email" id="form3Example3" class="form-control form-control-lg"
                                    placeholder="Correo Electrónico" name="email"
                                    value="<?= (!empty($cAux = request()->get("email64")) ? base64_decode($cAux):"") ?>" />
                                <label class="form-label" for="form3Example3"></label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-3">
                                <input type="password" id="form3Example4" class="form-control form-control-lg"
                                    placeholder="Contraseña" name="password" />
                                <label class="form-label" for="form3Example4"></label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Checkbox -->
                                <div class="form-check mb-0">
                                    <input class="form-check-input me-2" type="checkbox" value="" name="rememberme"
                                        id="form2Example3" />
                                    <label class="form-check-label" for="form2Example3">
                                        Recordarme
                                    </label>
                                </div>
                                <a href="/cliente/login/forgot/" class="text-body">¿Olvidó su contraseña?</a>
                            </div>

                            <div class="input">

                                <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>

                                <p class="small fw-bold mt-2 pt-1 mb-0">¿Aún no tienes cuenta? <a
                                        href="/cliente/login/register/" class="link-danger">Registrate</a></p>
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
        </div>

    </section>
</div>


@endpush

@push("css")
<style type="text/css">
.btn-social {
    width: 259px;
    height: 35px;
    border: none;
    border-radius: 15px;
    color: #FFF;
    display: block;
    margin: 0 auto;
    padding-left: 25px;
    text-align: left;
}

.btn-social-google {
    background-color: #DB4A39;
}

.btn-social-facebook {
    background-color: #3b5998;
}
</style>
@endpush