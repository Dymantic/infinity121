<div class="little-slider-card flex flex-col justify-between card mx-4 md:mx-2 w-64 mb-6 md:mb-0 @if(!$loop->first) md:ml-8 @endif">
    <div>
        <img class="max-w-full" src="{{ $subject['title_image']['thumb'] }}" alt="">
        <p class="type-h2 mt-4 border-b border-hms-navy">{{ $subject['title'] }}</p>
        <p class="type-b2 my-4">{{ $subject['description'] }}</p>
    </div>
    <div>
        <div class="text-center mb-4">
            <a class="type-a2 arrow-link-left hover:text-hms-navy" href="{{ localUrl('/courses/' . $subject['slug']) }}">{!! trans('home.courses.card-link') !!}</a>
        </div>
    </div>
</div>
