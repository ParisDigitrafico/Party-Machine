@extends('website.layouts.cliente')

@push("content")
<div class="login">
<section>

  <div class="container h-custom">
      <div class="container">
        <div class="row d-flex align-items-center">
          <div class="col-md-12 offset-lg-6 col-lg-6 cont_form"> 
              <div class="cont">
      <form>
         <br>  
        <br>  
        @csrf

        @if(request()->get("code") == 201 && request()->get("message"))
        <div style="text-align: center">
            <h2 style="color:#669933;">
              <b>{{ request()->get("message") }}</b>
            </h2>
        </div>

        @elseif(request()->get("code") == 500 && request()->get("message"))
        <div style="text-align: center">
            <h2 style="color:#CC0000;">
              <b>{{ request()->get("message") }}</b>
            </h2>
        </div>

        @elseif(request()->get("message"))
        <div style="text-align: center">
          <p style="color:#102360;">
            <b>{{ request()->get("message") }}</b>
          </p>
          </div>
        @endif

        @if(request()->get("reenviar"))
          @php
          $cEmail = base64_decode(request()->get("reenviar"));
          @endphp

          <input type="hidden" name="email" value="{{ $cEmail }}" />

          <h2 style="text-align: center"><a href="#" id="btnResend">Reenviar Mensaje.</a></h2>

        @else
        <br>
           <div class="text-center">
             <a href="/" class="btn btn-primary btn-lg" >
                       <span>Volver al inicio</span>
                       <button type="submit" class="btn btn-primary btn-lg"></button>
                  </a>
           </div>
        @endif

        <br>  
        <br>  
      </form>
      </div>
      </div>

        <div class="fondo lazy" data-src="/static/website/images/temp/fondo_contacto.png">
            <img class="img" src="/static/website/images/temp/img_contacto.png">
          </div>
    </div>
  </div>

</section>
</div>
@endpush

@push('js')
<script type="text/javascript">
$(document).on("click", "#btnResend", function(evt){
  evt.preventDefault();

  $form = $(this).closest("form");

  $form.attr("action","/cliente/login/forgot/");
  $form.attr("method","post");

  $form.submit();
});
</script>
@endpush

