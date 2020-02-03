@extends('front.base')

@section('content')
    <div class="max-w-5xl px-6 pt-16 mx-auto">
        <div>
            <h1 class="type-h1 text-center text-hms-navy my-20">{{ trans('contact.intro.heading') }}</h1>
            <p class="type-b1 text-hms-navy max-w-2xl mx-auto mb-8 text-center">{{ trans('contact.intro.text') }}</p>
        </div>

        @include('front.contact.contact-details')

        <div>
            <contact-form :labels='@json($labels)'></contact-form>
        </div>

    </div>

@endsection
