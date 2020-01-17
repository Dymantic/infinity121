<div class="flex flex-col justify-between p-2 bg-cardy-blue shadow w-64 mb-6 md:mb-0 @if(!$loop->first) md:ml-8 @endif">
    <div>
        <img class="max-w-full" src="{{ $subject['title_image']['thumb'] }}" alt="">
        <p class="type-h2 text-hms-navy mt-4 border-b border-hms-navy">{{ $subject['title'] }}</p>
        <p class="type-b2 text-hms-navy my-4">{{ $subject['description'] }}</p>
    </div>
    <div>
        <div class="text-center mb-4">
            <a class="type-a2 text-hms-navy" href="">{!! trans('home.courses.card-link') !!}</a>
        </div>
    </div>
</div>
