@extends('front.base')

@section('content')
<div>
    <div class="max-w-5xl mx-auto py-16 px-6">
        <div>
            <h1 class="my-20 type-h1 text-center">{{ trans('courses.index.intro.heading') }}</h1>
            <p class="type-b1 max-w-2xl mx-auto text-center">{{ trans('courses.index.intro.text') }}</p>
        </div>
    </div>
    <div class="grid-4x max-w-5xl mx-auto pb-20">
    @foreach($courses as $course)
            <div class="flex flex-col justify-between card w-64 mx-auto">
                <div>
                    <img class="max-w-full" src="{{ $course['title_image']['thumb'] }}" alt="">
                    <p class="type-h2 mt-4 border-b border-hms-navy">{{ $course['title'] }}</p>
                    <p class="type-b2 my-4">{{ $course['description'] }}</p>
                </div>
                <div>
                    <div class="text-center mb-4">
                        <a class="type-a2 arrow-link-left" href="{{ localUrl("courses/{$course['slug']}") }}">{!! trans('home.courses.card-link') !!}</a>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
</div>
@endsection
