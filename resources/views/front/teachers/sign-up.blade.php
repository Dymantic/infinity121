@extends('front.base')

@section('title')
    {{ trans('teacher-signup.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('teacher-signup.meta.title'),
      'ogDescription' => trans('teacher-signup.meta.description'),
    ])
@endsection

@section('content')
    <div class="max-w-5xl px-6 pt-16 mx-auto">
        <div>
            <h1 class="type-h1 text-center my-20">{{ trans('teacher-signup.intro.heading') }}</h1>
            <p class="type-b1 max-w-2xl mx-auto mb-8">{{ trans('teacher-signup.intro.text') }}</p>
        </div>

        <div>
            <teacher-signup :labels='@json($labels)' :dialog='@json($dialog)'></teacher-signup>
        </div>

    </div>
@endsection
