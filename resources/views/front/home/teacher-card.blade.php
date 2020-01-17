<div class="flex flex-col justify-between p-4 bg-cardy-blue shadow w-68 mx-auto">
    <div>
        <img src="{{ $teacher['avatar_thumb'] }}" alt="" class="w-32 h-32 rounded-full mb-8 mx-auto object-cover">
        <p class="text-hms-navy type-h2 border-b border-hms-navy">{{ $teacher['name'] }}</p>
        <p class="text-hms-navy type-b2 mt-4">{{ $teacher['bio'] }}</p>
    </div>

    <div class="text-center my-8">
        <a href="{{ localUrl("/teachers/{$teacher['slug']}") }}" class="type-a2 text-hms-navy">Bio &gt;</a>
    </div>
</div>
