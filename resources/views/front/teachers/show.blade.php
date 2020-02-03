@extends('front.base')

@section('content')
<div class="max-w-5xl mx-auto pt-16">
    <h1 class="type-h1 text-hms-navy my-20 text-center">{{ $teacher['name'] }}</h1>
    <div class="flex flex-col md:flex-row">
        <div class="w-0 md:w-2/5"></div>
        <div class="md:w-3/5">
            <img src="{{ $teacher['avatar_thumb'] }}" alt="{{ $teacher['name'] }}" class="block w-32 h-32 mx-auto md:mx-0 rounded-full mb-12">
        </div>
    </div>
    <div class="flex flex-col md:flex-row px-6">
        <div class="md:w-2/5 order-3 md:order-1 my-6 md:my-0">
            <p class="w-64">{!! $teacher['bio'] !!}</p>
        </div>
        <div class="md:w-3/5 order-2">
            <div>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.nationality') }}: </span>
                    <span>{{ $teacher['nationality'] }}</span>
                </p>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.qualifications') }}: </span>
                    <span>{{ $teacher['qualifications'] }}</span>
                </p>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.courses') }}: </span>
                    <span>{{ $teacher['nationality'] }}</span>
                </p>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.experience') }}: </span>
                    <span>{{ $teacher['teaching_since'] }}</span>
                </p>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.chinese-level') }}: </span>
                    <span>{{ $teacher['chinese_ability'] }}</span>
                </p>
                <p>
                    <span class="text-hms-navy type-h2">{{ trans('teachers.show.nationality') }}: </span>
                    <span>{{ $teacher['nationality'] }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
