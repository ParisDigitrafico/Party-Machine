@extends('website.layouts.cliente')

@push("main")
<div class="login">
    <section>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 offset-lg-6 col-lg-6 cont_form">
                    <div class="cont">

                        @if(request()->get("code") == 200 && request()->get("message"))
                        <div>
                            <p><span style="color: #669933"><b>{{ request()->get("message") }}</b></span></p>
                        </div><br>
                        @elseif(request()->get("message"))
                        <div>
                            <p><span style="color: #CC0000"><b>{{ request()->get("message") }}</b></span></p>
                        </div><br>
                        @endif

                        <form action="/cliente/login/forgot/" method="post" id="frmRegister">
                            @csrf

                            <h2>RESTABLECER CONTRASEÑA</h2>

                            <div class="form-outline mb-2">
                                <div style="text-align: left">
                                    <p style="font-size: 10pt; text-align:">
                                        *Para restablecer su contrase&ntilde;a ingrese su correo electr&oacute;nico y
                                        pulse Aceptar.</p>
                                </div>
                            </div>

                            <div class="form-outline">
                                <input type="text" class="form-control form-control-lg" name="email"
                                    value="{{ old('email') }}" placeholder="Correo Electrónico"
                                    data-validetta="required,email" style="" />
                            </div>

                            <div class="text-lg-start">
                                <a class="btn btn-primary btn-lg">
                                    <span>Aceptar</span>
                                    <button id="btnSubmit" type="submit" class="btn btn-primary btn-lg"></button>
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

                @desktop
                <div class="fondo lazy" data-src="/static/website/images/temp/fondo_contacto.png">
                    <img class="img" src="/static/website/images/temp/img_contacto.png">
                </div>

                @enddesktop
            </div>
        </div>

    </section>
</div>
@endpush

@push("css")
<style type="text/css">
.form-outline {
    margin-bottom: 16px;
}

.validetta-bubble,
.validetta-inline {
    font-size: 11pt;
    line-height: 1.4;
}
</style>
@endpush

@push("js")
<script src="/static/generico/plugins/validetta-1.0.1/dist/validetta.min.js"></script>
<script src="/static/generico/plugins/validetta-1.0.1/localization/validettaLang-es-ES.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#frmRegister').validetta({
        realTime: true,
        display: 'inline',
        errorTemplateClass: 'validetta-inline',
    });

    $("#txtPswd").val("");
    $("#txtConfirmPswd").val("");

    util.noSpace("noSpace");
});
</script>
@endpush