@extends('front.base')

@section('title')
    {{ trans('contact.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('contact.meta.title'),
      'ogDescription' => trans('contact.meta.description'),
    ])
@endsection

@section('content')
    <div class="max-w-5xl px-6 pt-16 mx-auto">
        <div>
            <h1 class="type-h1 text-center my-20">{{ trans('contact.intro.heading') }}</h1>
            <p class="type-b1 max-w-2xl mx-auto mb-8 text-center">{{ trans('contact.intro.text') }}</p>
        </div>

        @include('front.contact.contact-details')

        <div>
            <contact-form :labels='@json($labels)' :dialog='@json($dialog)'></contact-form>
        </div>

    </div>

@endsection
