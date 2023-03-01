@extends('website.layouts.cliente')

<style>
    .div-icon
    {
    border: 1px solid #ced4da;
    border-right: none;
    width: 50px;
    text-align: center;
    display: flex;
    justify-content: center;
    }
    .icon-icon
    {
    font-size : 1.4rem;
    padding-top: 10px;
    }
    </style>


    @push("main")
    <div class="login">
        <section>
            @if(request()->get("code") == 201 && !empty(request()->get("message")))
            <div style="text-align: center"><span style="color:#669933;"><b>{{ request()->get("message") }}</b></span>
            </div>
            <br>
            @elseif(request()->get("code") == 500 && !empty(request()->get("message")))
            <div style="text-align: center"><span style="color:#CC0000;"><b>{{ request()->get("message") }}</b></span>
            </div>
            <br>
            @elseif(!empty(request()->get("message")))
            <div style="text-align: center"><span style="color:#102360;"><b>{{ request()->get("message") }}</b></span>
            </div>
            <br>
            @endif

            <!-- @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error )
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif -->

            <div class="container">
                <div class="row d-flex align-items-center">
                    <div class="col-md-12 offset-lg-3 col-lg-6 cont_form">


                        <div class="cont">
                            <form action="/cliente/login/register/" method="post" id="frmRegister">
                                @csrf

                                <h2>CREAR UNA CUENTA</h2>

                                <!-- <p> Información Personal</p> -->

                                <div class="form-outline mb-3 mt-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-envelope icon-icon"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" name="email"
                                        value="{{ old('email') }}" placeholder="Correo Electrónico"
                                        data-validetta="required,email" style="" />

                                    @if($errors->has('email'))
                                    <span style="color: #CC0000;">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="form-outline mb-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-lock icon-icon"></i>
                                    </div>
                                    <input type="password" class="form-control form-control-lg noSpace" name="pswd"
                                        id="txtPswd" placeholder="Contraseña" data-validetta="required,minLength[8]"
                                        style="" />
                                </div>

                                <div class="form-outline mb-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-lock icon-icon"></i>
                                    </div>
                                    <input type="password" class="form-control form-control-lg noSpace"
                                        id="txtConfirmPswd" placeholder="Confirmar Contraseña"
                                        data-validetta="required,equalTo[pswd],minLength[8]" style="" />
                                </div>

                                <div class="form-outline mb-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-user icon-icon"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" name="name"
                                        value="{{ old('name') }}" placeholder="Nombre"
                                        data-validetta="required,minLength[3]" style="" />
                                </div>

                                <div class="form-outline mb-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-user icon-icon"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" name="lastname"
                                        placeholder="Apellidos" data-validetta="required,minLength[3]" style="" />
                                </div>

                                <div class="form-outline mb-3 d-flex">
                                    <div class="div-icon">
                                        <i class="fa fa-phone icon-icon"></i>
                                    </div>
                                    <input type="text" class="form-control form-control-lg noSpace" name="phone"
                                        placeholder="Teléfono" maxlength="10"
                                        data-validetta="required,minLength[10],maxLength[10],number" style="" />
                                </div>


                                <div class="text-lg-start">
                                    <!--  <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Registrarme</button> -->
                                    <button type="submit" class="btn btn-primary btn-lg"
                                        style="padding: .5rem 2rem;">Registrarme</button>
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