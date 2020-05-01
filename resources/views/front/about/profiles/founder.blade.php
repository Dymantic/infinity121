@extends('front.base')

@section('title')
    {{ trans('founder.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('founder.meta.title'),
      'ogDescription' => trans('founder.meta.description'),
    ])
@endsection

@section('content')
    <div class="h-16"></div>
    <div class="pt-4 pb-32 wave-yellow-top px-6">
        <h1 class="type-h1 md:text-center mt-20 mb-12">{{ trans('founder.heading') }}</h1>
        <div class="type-b1 max-w-2xl mx-auto mt-12 md:mt-20">
            @foreach(trans('founder.text') as $paragraph)
            <p class="my-6">{!! $paragraph !!}</p>
            @endforeach
        </div>
        <div class="text-center my-20">
            <a href="{{ localUrl('/about-us') }}" class="hover:text-hms-navy type-a2 arrow-link-right">{!! trans('about.back-link') !!}</a>
        </div>
    </div>

    <div class="flex flex-col items-center pt-48 pb-48 wave-blue-bottom">
    </div>
@endsection
