@extends('front.base')

@section('content')
    <div class="pt-16 bg-shady-blue wave-white-bottom pb-48">
        <div class="px-6">
            <h1 class="type-h1 text-center py-20 px-6">{{ trans('affiliates.intro.heading') }}</h1>
            <p class="max-w-2xl mx-auto text-center type-b1">{{ trans('affiliates.intro.text') }}</p>
        </div>
    </div>
    <div class="max-w-5xl mx-auto grid-4x pb-40">
        @foreach($affiliates as $affiliate)
            @include('front.affiliates.affiliate-card', ['affiliate' => $affiliate])
        @endforeach
    </div>

@endsection
