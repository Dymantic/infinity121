@extends('front.base')

@section('content')
<div class="h-16"></div>
<div class="pt-4 pb-32 wave-yellow-top px-6">
    <h1 class="type-h1 text-center py-20 px-6">{{ $course['title'] }}</h1>
    <div class="type-b1 max-w-2xl mx-auto mt-20">{!! $course['writeup'] !!}</div>
</div>
<div class="bg-white px-6">

</div>
<div class="flex flex-col items-center pt-32 pb-48 wave-blue-bottom px-6">
    <a href="{{ localUrl("/students/sign-up?course={$course['slug']}") }}" class="btn btn-dark mb-12">{!! trans('courses.show.course_link') !!}</a>
    <a href="{{ localUrl("/courses") }}" class="type-a1 arrow-link-right hover:text-hms-navy">{!! trans('courses.show.back_link') !!}</a>
</div>
@endsection
