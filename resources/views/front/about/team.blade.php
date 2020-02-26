<div class="wave-blue-top bg-white px-6">
    <div class="max-w-3xl mx-auto pt-48">
        <h1 data-usher class="type-h1 my-20 text-center">{{ trans('about.team.heading') }}</h1>
        <p class="type-b1 my-8 text-center">{{ trans('about.team.paragraph_one') }}</p>
        <div class="grid-2x py-20">
            @foreach($team as $member)
                @if($member['page-link'] ?? false) <a href="{{ localUrl($member['page-link']) }}"> @endif
                <div class="w-48 mx-auto" data-usher>
                    <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="w-28 h-28 mb-6 mx-auto rounded-full">
                    <p class="type-h2 text-center">{{ $member['name'] }}</p>
                    <p class="type-b1 text-center">{{ $member['role'] }}</p>
                </div>
                @if($member['page-link'] ?? false) </a> @endif
            @endforeach

        </div>
    </div>
</div>
