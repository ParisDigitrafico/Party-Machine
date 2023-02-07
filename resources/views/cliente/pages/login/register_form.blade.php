@extends('website.layouts.cliente')

@push("main")
<div class="login">
    <section>
        @if(request()->get("code") == 201 && !empty(request()->get("message")))
        <div style="text-align: center"><span style="color:#669933;"><b>{{ request()->get("message") }}</b></span></div>
        <br>
        @elseif(request()->get("code") == 500 && !empty(request()->get("message")))
        <div style="text-align: center"><span style="color:#CC0000;"><b>{{ request()->get("message") }}</b></span></div>
        <br>
        @elseif(!empty(request()->get("message")))
        <div style="text-align: center"><span style="color:#102360;"><b>{{ request()->get("message") }}</b></span></div>
        <br>
        @endif

        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 offset-lg-3 col-lg-6 cont_form">


                    <div class="cont">
                        <form action="/cliente/login/register/" method="post" id="frmRegister">
                            @csrf

                            <h2>CREAR UNA CUENTA</h2>

                            <!-- <p> Información Personal</p> -->

                            <div class="form-outline">
                                <input type="text" class="form-control form-control-lg" name="email"
                                    value="{{ old('email') }}" placeholder="Correo Electrónico"
                                    data-validetta="required,email" style="" />
                            </div>

                            <div class="form-outline">
                                <input type="text" class="form-control form-control-lg" name="name"
                                    value="{{ old('name') }}" placeholder="Nombre"
                                    data-validetta="required,minLength[3]" style="" />
                            </div>

                            <div class="form-outline">
                                <input type="text" class="form-control form-control-lg" name="lastname"
                                    placeholder="Apellidos" data-validetta="required,minLength[3]" style="" />
                            </div>

                            <div class="form-outline">
                                <input type="text" class="form-control form-control-lg noSpace" name="phone"
                                    placeholder="Teléfono" maxlength="10"
                                    data-validetta="required,minLength[10],maxLength[10],number" style="" />
                            </div>

                            <div class="form-outline">
                                <input type="password" class="form-control form-control-lg noSpace" name="pswd"
                                    id="txtPswd" placeholder="Contraseña" data-validetta="required,minLength[8]"
                                    style="" />
                            </div>

                            <div class="form-outline">
                                <input type="password" class="form-control form-control-lg noSpace" id="txtConfirmPswd"
                                    placeholder="Confirmar Contraseña"
                                    data-validetta="required,equalTo[pswd],minLength[8]" style="" />
                            </div>

                            <div class="text-lg-start">
                                <!--  <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Registrarme</button> -->

                                <a class="btn btn-primary btn-lg">
                                    <button type="submit" class="btn btn-primary btn-lg">Registrarme</button>
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

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