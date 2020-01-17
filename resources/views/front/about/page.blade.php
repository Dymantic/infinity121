@extends('front.base')

@section('content')
@include('front.about.intro')
@include('front.about.why-choose')
@include('front.about.team', ['team' => $team])
@include('front.about.affiliates')
@endsection
