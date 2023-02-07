@php $captchaId = get_random_string(); @endphp
<input type="hidden" class="captcha_id" name="captcha_id" value="{{ $captchaId }}" />
<a href="javascript:void(0);" onclick="document.getElementById('{{ $captchaId }}').src='/ajax/captcha/?namespace={{ $captchaId }}&v=' + Math.random(); return false"
  title="{{ trans("general.formulario.pulsar_actualizar_codigo") }}">
<img id="{{ $captchaId }}" class="captcha_img"
     style="width: 240px; height: 80px;"
     src="/ajax/captcha/?namespace={{ $captchaId }}"
     data-src="/ajax/captcha/?namespace={{ $captchaId }}"
     alt="CAPTCHA Image" />
</a>