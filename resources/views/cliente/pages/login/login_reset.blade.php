@extends('website.layouts.cliente')

@push("main")
<div class="login">
<section>
   <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-12 offset-lg-6 col-lg-6 cont_form">
              <div class="cont">
          {!! $message !!}
        </div>
         <div class="fondo lazy" data-src="/static/website/images/robot_globo.png">
            <img class="img" src="/static/website/images/robot_globo.png">
          </div>
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
