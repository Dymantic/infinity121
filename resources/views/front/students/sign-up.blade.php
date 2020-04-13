@extends('front.base')

@section('content')
<div class="max-w-5xl px-6 pt-16 mx-auto">
    <div>
        <h1 class="type-h1 text-center my-20">{{ trans('student-signup.intro.heading') }}</h1>
        <p class="type-b1 max-w-2xl mx-auto mb-8">{{ trans('student-signup.intro.text') }}</p>
    </div>

    <div>
        <student-signup :course="{{ $course }}" :subjects='@json($subjects)' :dialog='@json($dialog)' :labels='@json($labels)'></student-signup>
    </div>

</div>

@endsection
