@extends('front.base')

@section('content')
<div class="max-w-5xl mx-auto pt-16">
    <h1 class="type-h1 text-hms-navy my-20 text-center">{{ $teacher['name'] }}</h1>
    <div class="five-seven-spaced">
        <div></div>
        <div>
            <img src="{{ $teacher['avatar_thumb'] }}" alt="{{ $teacher['name'] }}" class="block w-32 h-32 rounded-full mb-12">
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
