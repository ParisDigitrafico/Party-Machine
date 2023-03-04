@extends('website.layouts.cliente')

@push("main")

<div class="form_cuenta">

    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-12 cont_form">
                <h3>Seguridad y datos personales</h3><br>

                @if(request()->get("status") == 200 && request()->get("message"))
                <div style="text-align: center"><span
                        style="color: #669933"><b>{{ request()->get("message") }}</b></span></div><br><br>
                @elseif(request()->get("message"))
                <div style="text-align: center"><span
                        style="color: #CC0000"><b>{{ request()->get("message") }}</b></span></div><br><br>
                @endif
            </div>

            <form action="/cliente/cuenta/" id="frmCliente" method="post">
                @csrf

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="Nombre">*Correo (No puede modificarse)</label>
                            <input type="text" class="form-control" value="{{ session('cliente_user') }}"
                                readonly="readonly" style="">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="Nombre">*Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{ $data->nombre }}"
                                data-validetta="required,minLength[3]" style="">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label>*Telefono</label>
                            <input type="text" class="form-control" name="phone"
                                value="{{ $data->telefono }}" maxlength="10"
                                data-validetta="required,number,minLength[10],maxLength[10]" style="">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label>*Apellidos</label>
                            <input type="text" class="form-control" name="lastname" value="{{ $data->apellidos }}"
                            data-validetta="required,minLength[3]" style="">
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label>Contraseña Anterior</label>
                            <input type="password" class="form-control noSpace" id="oldpswd" name="oldpswd" style="">
                        </div>
                    </div>

                    <div class="col-lg-6"></div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Nueva Contraseña</label>
                            <input type="password" class="form-control noSpace" id="newpswd" name="newpswd"
                                data-validetta="minLength[8]" style="">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Confirmar Contraseña</label>
                            <input type="password" class="form-control noSpace" id="cfmpswd"
                                data-validetta="minLength[8],equalTo[newpswd]" style="">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <br>
                        <a class="btn btn-primary btn-lg">
                            <span>Guardar</span>
                            <button type="submit" class="btn btn-primary btn-lg btnSubmit"></button>
                        </a>
                        <a href="/cliente/" class="btn btn-outline-dark btn-sm rojo">
                            <span>Cancelar</span>
                        </a>

                    </div>


                </div>

            </form>


        </div>

    </div>
</div>

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
    var $frmCliente = $('#frmCliente');

    $frmCliente.validetta({
        realTime: true,
        display: 'inline',
        errorTemplateClass: 'validetta-inline',
        onValid: function(evt) {
            $oldpswd = $frmCliente.find("#oldpswd");
            $newpswd = $frmCliente.find("#newpswd");

            if ($oldpswd.val().toString().length > 0) {
                if ($newpswd.val().toString().length == 0) {
                    evt.preventDefault();

                    swal({
                        type: 'warning',
                        html: '<p>Es necesario escribir una nueva contraseña.</p>',
                    });
                }
            } else {
                $frmCliente.find(".btnSubmit").first().text("Guardando...").prop('disabled', true);
            }
        },
    });

    $("#oldpswd").val("");
    $("#newpswd").val("");
    $("#cfmpswd").val("");

    util.noSpace("noSpace");
});
</script>
@endpush