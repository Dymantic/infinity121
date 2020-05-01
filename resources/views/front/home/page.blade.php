@extends('front.base')

@section('title')
    {{ trans('home.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('home.meta.title'),
      'ogDescription' => trans('home.meta.description'),
    ])
@endsection

@section('content')
    @include('front.home.banner')
    @include('front.home.about')
    @include('front.home.courses')
    @include('front.home.selling-points')
    @include('front.home.teachers')
    @include('front.home.testimonials')
    @include('front.home.want-teachers')
@endsection

