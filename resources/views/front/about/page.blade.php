@extends('front.base')

@section('title')
    {{ trans('about.meta.title') }}
@endsection

@section('head')
    @include('front.partials.ogmeta', [
      'ogTitle' => trans('about.meta.title'),
      'ogDescription' => trans('about.meta.description'),
    ])
@endsection

@section('content')
@include('front.about.intro')
@include('front.about.why-choose')
@include('front.about.team', ['team' => $team])
@include('front.about.affiliates')
@endsection
