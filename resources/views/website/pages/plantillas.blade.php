@extends('website.layouts.default')

@push('main')

<div class="container">
    <div class="row">

        <div class="col-3">
          <label>Categorias</label>

        </div>

        <div class="col-9">
           <div class="row">
              @foreach($data as $item)
              <div class="card" style="width: 16rem;padding: 0;margin: 1rem 1rem;">
                <img class="card-img-top img-fluid" src="{{ $item->photo()->url}}" alt="Foto">
                <div class="card-body">
                  <h5 class="card-title">{{ $item->nombre }}</h5>
                  <p class="card-text">{!! $item->descripcion !!}</p>
                </div>
                <div class="card-footer text-muted">
                  <a href="#" class="btn btn-primary"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                </div>
              </div>
              @endForeach
          </div>
         </div>

    </div>
</div>

@endpush