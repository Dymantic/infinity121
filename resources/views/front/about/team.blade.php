<div class="wave-blue-top bg-white">
    <div class="max-w-3xl mx-auto pt-48">
        <h1 class="type-h1 my-20 text-center">{{ trans('about.team.heading') }}</h1>
        <p class="type-b1 my-8 text-center">{{ trans('about.team.paragraph_one') }}</p>
        <div class="grid-2x py-20">
            @foreach($team as $member)
                <div class="w-48 mx-auto">
                    <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="w-28 h-28 mb-6 mx-auto rounded-full">
                    <p class="type-h2 text-center">{{ $member['name'] }}</p>
                    <p class="type-b1 text-center">{{ $member['role'] }}</p>
                </div>
            @endforeach

        </div>
    </div>
</div>
