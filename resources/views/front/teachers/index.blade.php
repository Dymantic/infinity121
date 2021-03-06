@extends('front.base')

@section('title')
    {{ trans('teachers.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('teachers.meta.title'),
      'ogDescription' => trans('teachers.meta.description'),
    ])
@endsection

@section('content')
<div>
    <div class="max-w-5xl mx-auto py-16 px-6">
        <div>
            <h1 class="my-20 type-h1 text-center">{{ trans('teachers.index.intro.heading') }}</h1>
            <p class="type-b1 max-w-2xl mx-auto text-center">{{ trans('teachers.index.intro.text') }}</p>
        </div>
    </div>
    <div class="grid-4x max-w-5xl mx-auto pb-40">
        @foreach($teachers as $teacher)
            @include('front.home.teacher-card', ['teacher' => $teacher])
        @endforeach
    </div>
</div>
@endsection
