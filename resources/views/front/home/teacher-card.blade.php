<div class="little-slider-card mx-4 md:mx-2 flex flex-col justify-between card w-68 mx-auto">
    <div>
        <img src="{{ $teacher['avatar_thumb'] }}" alt="" class="w-32 h-32 rounded-full mb-8 mx-auto object-cover">
        <p class="type-h2 border-b border-hms-navy">{{ $teacher['name'] }}</p>
        <p class="type-b2 mt-4">{{ $teacher['bio'] }}</p>
    </div>

    <div class="text-center my-8">
        <a href="{{ localUrl("/teachers/{$teacher['slug']}") }}" class="type-a2 arrow-link-left hover:text-hms-navy">Bio</a>
    </div>
</div>
