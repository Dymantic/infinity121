@extends('front.base')

@section('content')
    <div class="pt-16 bg-shady-blue wave-white-bottom pb-48">
        <div class="px-6">
            <h1 class="type-h1 text-hms-navy text-center py-20 px-6">Affiliates</h1>
            <p class="max-w-lg mx-auto text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid cumque explicabo, iure laudantium nisi qui quidem quo saepe tempore vero! Delectus illo incidunt ipsum iste minus mollitia nemo qui sint.</p>
        </div>
    </div>
    <div class="max-w-5xl mx-auto grid-4x pb-20">
        @foreach($affiliates as $affiliate)
            @include('front.affiliates.affiliate-card', ['affiliate' => $affiliate])
        @endforeach
    </div>

@endsection
