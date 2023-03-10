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
</style>

<div class="area_cliente">
<div class="container">
<div class="row">
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
            <div class="only-robot">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 221.69 324.68"><defs><style>.cls-1{fill:none;stroke:#b3b3b2;stroke-linecap:round;stroke-linejoin:round;stroke-width:8.67px;}.cls-2{fill:#676767;}.cls-3{fill:#8c8c8c;}.cls-4{fill:#4ba5ce;}.cls-5{fill:#54bde4;}.cls-6{fill:#505050;}.cls-7{fill:#ffcd2c;}.cls-8{fill:#6cb544;}.cls-9{fill:#eb5f52;}.cls-10{fill:#b3b3b2;}.cls-11{fill:#ef7f70;}.cls-12{fill:#1e72b8;}</style></defs><g id="Capa_2" data-name="Capa 2"><g id="Capa_39" data-name="Capa 39"><path class="cls-1" d="M15.09,77.74c-3.53,1-11.15,1.48-10.74,6.83.56,7.33,2.82,12.77,10.1,14.48A13.86,13.86,0,0,0,31,89.28c1.19-4.55-5.1-9.36-8.72-11.25"/><circle class="cls-2" cx="57.96" cy="154.77" r="9.14"/><circle class="cls-2" cx="42.52" cy="148.76" r="9.14" transform="translate(-111.15 130.76) rotate(-67.37)"/><circle class="cls-2" cx="31.43" cy="136.49" r="9.14" transform="translate(-85.64 59.6) rotate(-43.74)"/><circle class="cls-2" cx="24.24" cy="120.99" r="9.14"/><circle class="cls-2" cx="11.06" cy="120.99" r="9.14"/><circle class="cls-2" cx="15.69" cy="134.85" r="9.14"/><circle class="cls-2" cx="22.73" cy="148.07" r="9.14"/><circle class="cls-2" cx="33.38" cy="160.37" r="9.14" transform="translate(-25.33 7.44) rotate(-9.24)"/><circle class="cls-2" cx="46.55" cy="169.52" r="9.14" transform="translate(-102.12 75.71) rotate(-42.39)"/><circle class="cls-2" cx="61.8" cy="171.68" r="9.14" transform="translate(-37.65 18.7) rotate(-13.23)"/><circle class="cls-2" cx="49.71" cy="161.52" r="9.14" transform="translate(-95.83 75.63) rotate(-42.35)"/><circle class="cls-2" cx="33.38" cy="149.93" r="9.14" transform="translate(-117.85 123.04) rotate(-67.37)"/><circle class="cls-2" cx="23.67" cy="136.49" r="9.14" transform="translate(-89.58 56.72) rotate(-45)"/><circle class="cls-2" cx="60.81" cy="163.91" r="9.14" transform="translate(-36.17 18.45) rotate(-13.34)"/><path class="cls-3" d="M1.92,108a6.44,6.44,0,0,0,6.44,6.44H28.68A6.44,6.44,0,0,0,35.11,108h0a6.43,6.43,0,0,0-6.43-6.43H8.36A6.43,6.43,0,0,0,1.92,108Z"/></g><g id="Capa_38" data-name="Capa 38"><circle class="cls-2" cx="83.82" cy="250.48" r="9.14" transform="translate(-89.94 51.55) rotate(-22.63)"/><circle class="cls-2" cx="97.78" cy="250.48" r="9.14" transform="translate(-168.84 285.36) rotate(-76.13)"/><circle class="cls-2" cx="89.3" cy="251.78" r="9.14" transform="translate(-173.55 299.48) rotate(-80.76)"/><circle class="cls-2" cx="82.35" cy="265.97" r="9.14" transform="translate(-195.59 282.39) rotate(-76.18)"/><circle class="cls-2" cx="94.57" cy="265.97" r="9.14" transform="translate(-185.98 297.16) rotate(-76.77)"/><circle class="cls-2" cx="81.98" cy="279.83" r="9.14" transform="translate(-209.19 295.59) rotate(-76.77)"/><circle class="cls-2" cx="91.88" cy="279.83" r="9.14" transform="translate(-109.58 64.98) rotate(-24.98)"/><circle class="cls-2" cx="81.31" cy="294.04" r="9.14" transform="translate(-223.56 305.34) rotate(-76.66)"/><circle class="cls-2" cx="91.12" cy="294.04" r="9.14" transform="translate(-213.74 336.74) rotate(-80.76)"/><circle class="cls-2" cx="90.45" cy="307.04" r="9.14"/><path class="cls-4" d="M72.24,292.91c-1.76-2.31-27.45,5.25-33.75,21.77-1.91,4.6,2.71,7.67,5.86,8.44,5.92,1.44,47.68,2.64,51.65,0,5-3.35,5.58-16,3.15-17.62-1.73-1.13-3.35.44-7.66-.15C87.89,304.86,79.41,302.31,72.24,292.91Z"/><path class="cls-5" d="M78,306.2a5.87,5.87,0,1,1-5.87-5.87A5.87,5.87,0,0,1,78,306.2Z"/></g><g id="Capa_37" data-name="Capa 37"><circle class="cls-2" cx="152.55" cy="250.48" r="9.14" transform="translate(-132.44 181.23) rotate(-45)"/><circle class="cls-2" cx="138.6" cy="250.48" r="9.14" transform="translate(-146.4 277.42) rotate(-66.44)"/><circle class="cls-2" cx="147.07" cy="251.78" r="9.14" transform="translate(-36.33 25.09) rotate(-8.68)"/><circle class="cls-2" cx="154.02" cy="265.97" r="9.14" transform="translate(-151.37 300.54) rotate(-66.38)"/><circle class="cls-2" cx="141.8" cy="265.97" r="9.14" transform="translate(-158.25 294.5) rotate(-67.37)"/><circle class="cls-2" cx="154.4" cy="279.83" r="9.14" transform="translate(-56.88 40.03) rotate(-12.49)"/><circle class="cls-2" cx="144.49" cy="279.83" r="9.14" transform="translate(-169.4 305.51) rotate(-67.37)"/><path class="cls-2" d="M145.92,294a9.14,9.14,0,1,0,9.14-9.14A9.15,9.15,0,0,0,145.92,294Z"/><circle class="cls-2" cx="145.25" cy="294.04" r="9.14" transform="translate(-42.75 25.32) rotate(-8.69)"/><circle class="cls-2" cx="145.92" cy="307.04" r="9.14"/><path class="cls-4" d="M164.13,292.91c1.76-2.31,27.46,5.25,33.75,21.77,1.91,4.6-2.7,7.67-5.86,8.44-5.92,1.44-47.68,2.64-51.65,0-5-3.35-5.58-16-3.15-17.62,1.74-1.13,3.35.44,7.66-.15C148.48,304.86,157,302.31,164.13,292.91Z"/><path class="cls-5" d="M158.33,306.2a5.87,5.87,0,1,0,5.87-5.87A5.87,5.87,0,0,0,158.33,306.2Z"/></g><g id="Capa_36" data-name="Capa 36"><path class="cls-1" d="M213.92,244.08a12.69,12.69,0,1,0-21.58-11.74,12.66,12.66,0,0,0,3.09,11.77"/><circle class="cls-2" cx="176.66" cy="156.63" r="9.42"/><circle class="cls-2" cx="189.9" cy="161.8" r="9.14"/><circle class="cls-2" cx="199.67" cy="171.68" r="9.14" transform="translate(-26.66 37.07) rotate(-9.95)"/><circle class="cls-2" cx="207.79" cy="184.55" r="9.14"/><circle class="cls-2" cx="210.22" cy="200.24" r="9.14" transform="translate(-80.02 207.29) rotate(-45)"/><circle class="cls-2" cx="172.31" cy="171.68" r="9.14" transform="translate(-34.72 43.99) rotate(-13.23)"/><circle class="cls-2" cx="187.47" cy="176.1" r="9.14"/><circle class="cls-2" cx="195.52" cy="188.32" r="9.14"/><circle class="cls-2" cx="199.67" cy="201.06" r="9.14" transform="translate(-69.08 290.87) rotate(-63.68)"/><circle class="cls-2" cx="201.08" cy="188.32" r="9.14"/><circle class="cls-2" cx="180.75" cy="169.52" r="9.14" transform="translate(-34 45.87) rotate(-13.23)"/><path class="cls-3" d="M188.21,214.25a6.43,6.43,0,0,0,6.43,6.44H215a6.44,6.44,0,0,0,6.44-6.44h0a6.43,6.43,0,0,0-6.44-6.43H194.64a6.43,6.43,0,0,0-6.43,6.43Z"/></g><g id="Capa_1-2" data-name="Capa 1"><g id="Capa_35" data-name="Capa 35"><rect class="cls-3" x="140.17" y="59.33" width="43.33" height="35.01" rx="5.89"/></g><g id="Capa_34" data-name="Capa 34"><rect class="cls-3" x="51.66" y="59.33" width="43.33" height="35.01" rx="5.89"/></g><g id="Capa_33" data-name="Capa 33"><rect class="cls-3" x="125.81" y="218.03" width="37.35" height="26.08" rx="4.01"/></g><g id="Capa_32" data-name="Capa 32"><rect class="cls-3" x="71.75" y="218.03" width="37.35" height="26.08" rx="4.01"/></g><g id="Capa_31" data-name="Capa 31"><rect class="cls-4" x="64.55" y="34.03" width="106.07" height="106.76" rx="19.7"/></g><g id="Capa_30" data-name="Capa 30"><rect class="cls-6" x="73.33" y="42.87" width="88.51" height="57.89" rx="16.44"/></g><g id="Capa_29" data-name="Capa 29"><rect class="cls-4" x="64.55" y="140.79" width="106.07" height="96.36" rx="19.7"/></g><g id="Capa_28" data-name="Capa 28"><rect class="cls-5" x="84.73" y="159.13" width="65.7" height="59.69" rx="12.2"/></g><g id="Capa_27" data-name="Capa 27"><path class="cls-3" d="M148.78,114.75a2.9,2.9,0,1,0,2.9-2.9A2.9,2.9,0,0,0,148.78,114.75Z"/></g><g id="Capa_26" data-name="Capa 26"><path class="cls-3" d="M140.29,114.75a2.9,2.9,0,1,0,2.9-2.9A2.9,2.9,0,0,0,140.29,114.75Z"/></g><g id="Capa_25" data-name="Capa 25"><path class="cls-3" d="M131.79,114.75a2.91,2.91,0,1,0,2.91-2.9A2.9,2.9,0,0,0,131.79,114.75Z"/></g><g id="Capa_24" data-name="Capa 24"><path class="cls-3" d="M148.78,122.81a2.9,2.9,0,1,0,2.9-2.9A2.9,2.9,0,0,0,148.78,122.81Z"/></g><g id="Capa_23" data-name="Capa 23"><path class="cls-3" d="M140.29,122.81a2.9,2.9,0,1,0,2.9-2.9A2.9,2.9,0,0,0,140.29,122.81Z"/></g><g id="Capa_22" data-name="Capa 22"><path class="cls-3" d="M132.62,180.83a5.94,5.94,0,1,0,5.93-5.94A5.93,5.93,0,0,0,132.62,180.83Z"/></g><g id="Capa_21" data-name="Capa 21"><path class="cls-3" d="M94.05,180.83a5.94,5.94,0,1,0,5.94-5.94A5.94,5.94,0,0,0,94.05,180.83Z"/></g><g id="Capa_20" data-name="Capa 20"><path class="cls-3" d="M131.79,122.81a2.91,2.91,0,1,0,2.91-2.9A2.9,2.9,0,0,0,131.79,122.81Z"/></g><g id="Capa_19" data-name="Capa 19"><path class="cls-3" d="M109.38,120.22a6.47,6.47,0,1,0,6.47-6.47A6.46,6.46,0,0,0,109.38,120.22Z"/></g><g id="Capa_18" data-name="Capa 18"><path class="cls-7" d="M111.63,120.22a4.22,4.22,0,1,0,4.22-4.21A4.22,4.22,0,0,0,111.63,120.22Z"/></g><g id="Capa_17" data-name="Capa 17"><path class="cls-3" d="M93.91,120.22a6.47,6.47,0,1,0,6.47-6.47A6.46,6.46,0,0,0,93.91,120.22Z"/></g><g id="Capa_16" data-name="Capa 16"><path class="cls-8" d="M96.16,120.22a4.22,4.22,0,1,0,4.22-4.21A4.22,4.22,0,0,0,96.16,120.22Z"/></g><g id="Capa_15" data-name="Capa 15"><path class="cls-3" d="M78.43,120.22a6.47,6.47,0,1,0,6.47-6.47A6.47,6.47,0,0,0,78.43,120.22Z"/></g><g id="Capa_14" data-name="Capa 14"><path class="cls-9" d="M80.69,120.22A4.22,4.22,0,1,0,84.9,116,4.21,4.21,0,0,0,80.69,120.22Z"/></g><g id="Capa_13" data-name="Capa 13"><path class="cls-3" d="M111.76,204.45a6.48,6.48,0,1,0,6.47-6.47A6.47,6.47,0,0,0,111.76,204.45Z"/></g><g id="Capa_12" data-name="Capa 12"><circle class="cls-7" cx="118.23" cy="204.45" r="4.22"/></g><g id="Capa_11" data-name="Capa 11"><path class="cls-3" d="M96.29,204.45a6.47,6.47,0,1,0,6.47-6.47A6.47,6.47,0,0,0,96.29,204.45Z"/></g><g id="Capa_10" data-name="Capa 10"><path class="cls-8" d="M98.55,204.45a4.22,4.22,0,1,0,4.21-4.21A4.21,4.21,0,0,0,98.55,204.45Z"/></g><g id="Capa_9" data-name="Capa 9"><path class="cls-3" d="M140.18,204.45a6.47,6.47,0,1,1-6.47-6.47A6.47,6.47,0,0,1,140.18,204.45Z"/></g><g id="Capa_8" data-name="Capa 8"><path class="cls-9" d="M137.92,204.45a4.22,4.22,0,1,1-4.21-4.21A4.21,4.21,0,0,1,137.92,204.45Z"/></g><g id="Capa_7" data-name="Capa 7"><path class="cls-10" d="M97,180.83a2.42,2.42,0,0,0,2.43,2.42h39.63a2.42,2.42,0,0,0,2.43-2.42h0a2.43,2.43,0,0,0-2.43-2.43H99.44A2.43,2.43,0,0,0,97,180.83Z"/></g></g><g id="Capa_6" data-name="Capa 6"><rect class="cls-3" x="58.52" y="11.96" width="4.04" height="49.22"/><path class="cls-9" d="M51.94,8.61A8.61,8.61,0,1,0,60.54,0,8.61,8.61,0,0,0,51.94,8.61Z"/><path class="cls-11" d="M56.27,3.36A2.86,2.86,0,1,0,59.56,1,2.86,2.86,0,0,0,56.27,3.36Z"/><path class="cls-11" d="M53.46,7a1.7,1.7,0,1,0,2-1.4A1.71,1.71,0,0,0,53.46,7Z"/></g><g id="Capa_5" data-name="Capa 5"><rect class="cls-3" x="173.1" y="11.96" width="4.04" height="49.22"/><path class="cls-9" d="M166.51,8.61A8.61,8.61,0,1,0,175.12,0,8.62,8.62,0,0,0,166.51,8.61Z"/><path class="cls-11" d="M170.84,3.36A2.87,2.87,0,1,0,174.14,1,2.88,2.88,0,0,0,170.84,3.36Z"/><path class="cls-11" d="M168,7a1.7,1.7,0,1,0,2-1.4A1.71,1.71,0,0,0,168,7Z"/></g><g id="Capa_4" data-name="Capa 4"><rect class="cls-12" x="94.08" y="60.5" width="6.29" height="18.23"/></g><g id="Capa_3" data-name="Capa 3"><rect class="cls-12" x="136.08" y="60.35" width="6.29" height="18.23"/></g></g></svg>
                 </div>
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                @if(request()->get("status") == 200 && request()->get("message"))
                <div><p><span style="color: #669933"
                ><b>{{ request()->get("message") }}</b></span></p></div><br>
                @elseif(request()->get("message"))
                <div><p><span style="color: #CC0000"
                ><b>{{ request()->get("message") }}</b></span></p></div><br>
                @endif

                <form action="/cliente/login/forgot/" method="post">
                    @csrf

                    <h2>RESTABLECER CONTRASE??A</h2>

                    <p>*Para restablecer su contrase??a ingrese su correo electr??nico y pulse Aceptar.</p>

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input name="email"
                                type="email"
                                id="form3Example3"
                                placeholder="{{ trans('auth.email') }}"
                                class="form-control form-control-lg"
                        />
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg"
                        style="padding-left: 2.5rem; padding-right: 2.5rem;">Aceptar</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </section>
</div>
</div>
</div>

@endpush
