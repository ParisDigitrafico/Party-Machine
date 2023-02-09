@extends('website.layouts.default')

@push('main')

<div class="container">
    <div class="row">

        <div class="col-3">
            
        </div>
        <div class="col-9">

            @foreach($data as $item)
            <div class="card" style="width: 18rem;">
                <img class="card-img-top img-fluid" src="{{ $item->photo()->url}}" alt="Foto">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->nombre }}</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ultricies.</p>
                    <a href="#" class="btn btn-primary"><i class="fa fa-shopping-cart" aria-hidden="true"></i></i></a>
                </div>
            </div>
            @endForeach
        </div>

    </div>
</div>

@endpush