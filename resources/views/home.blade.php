@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Shopping') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2>product:moblie phone</h2>
                    <h3>Price : 100$</h3>
                    <form action="{{route('stripe')}}" method="POST">
                      @csrf
                    <input type="hidden" name="product_name" value="Mobile Phone"> 
                    <img style="width: 40%" src="https://image.made-in-china.com/2f0j00mtZGLEbKHior/1-1-Copy-Ipone-X-Mobile-Phone-5-0-Inch-HD-Screen-with-1g-RAM-16g-ROM-Cellphone.webp" alt="">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="price" value="100"> <br>
                    <button type="submit">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
