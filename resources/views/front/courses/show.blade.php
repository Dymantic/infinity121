@extends('front.base')

@section('content')
<div class="pt-16 bg-mild-yellow">
    <h1 class="type-h1 text-hms-navy text-center py-20 px-6">{{ $course['title'] }}</h1>
</div>
<div class="wave-yellow-top bg-white pt-48 px-6">
    <div class="type-b1 max-w-2xl mx-auto">{!! $course['writeup'] !!}</div>
</div>
<div class="flex flex-col items-center pt-32 pb-48 wave-blue-bottom">
    <a href="{{ localUrl("/students/sign-up?course={$course['slug']}") }}" class="btn btn-dark mb-12">{!! trans('courses.show.course_link') !!}</a>
    <a href="{{ localUrl("/courses") }}" class="type-a1 text-hms-navy">{!! trans('courses.show.back_link') !!}</a>
</div>
@endsection
