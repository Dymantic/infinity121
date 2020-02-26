<div class="px-6">
    <div class="max-w-5xl mx-auto">
        <p class="type-h1 max-w-2xl mb-12" data-usher>{!! trans('home.courses.heading') !!}</p>
        <p class="type-b1 max-w-2xl">{!! trans('home.courses.intro') !!}</p>
        <div class="little-slider mb-32 mt-12 md:flex md:flex-row justify-between items-center md:items-stretch"
             data-flickity='{"cellAlign": "center", "contain": false, "watchCSS": true, "prevNextButtons": false}'
        >
{{--        <div data-flickity='{"cellAlign": "left", "contain": true}'>--}}
            @foreach($subjects as $subject)
                @include('front.home.course-card')
            @endforeach
        </div>

{{--        </div>--}}
        <div class="text-center">
            <a href="{{ localUrl('/courses') }}" class="btn btn-dark">{!! trans('home.courses.button') !!}</a>
        </div>
    </div>
</div>
