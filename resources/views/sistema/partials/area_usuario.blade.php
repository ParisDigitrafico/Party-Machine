<div class="account-item clearfix js-item-menu">
<!--<div class="image">
<img src="{{ asset('/static/sistema/images/user_white.png') }}" />
</div> -->

<div class="menu__content">
<!--<i class="nav-icon fas fa-users"></i>  -->
<a class="js-acc-btn" href="#">{{ session('usuario') }}</a>
</div>
<div class="account-dropdown js-dropdown">
<!--<div class="info clearfix">
<div class="image">
<a href="#">
</a>
</div>
<div class="content">
<h5 class="name">
<a href="#">john doe</a>
</h5>
<span class="email">johndoe@example.com</span>
</div>
</div>-->
<div class="account-dropdown__body">
<div class="account-dropdown__item">
<a href="/sistema/usuario/editarcuenta/">
<i class="zmdi zmdi-account"></i>Editar Información</a>
</div>
<!--<div class="account-dropdown__item">
<a href="#">
<i class="zmdi zmdi-settings"></i>Configuraciones</a>
</div>-->
<!--<div class="account-dropdown__item">
<a href="#">
<i class="zmdi zmdi-money-box"></i>Mis Págos</a>
</div>-->
</div>
<div class="account-dropdown__footer">
<a href="/sistema/login/close/">
<i class="zmdi zmdi-power"></i>Cerrar Sesión</a>
</div>
</div>
</div>