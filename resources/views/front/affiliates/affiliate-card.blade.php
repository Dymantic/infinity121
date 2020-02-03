<div class="w-64 p-4 bg-cardy-blue shadow flex flex-col justify-between">
    <div>
        <img src="{{ $affiliate['logo_thumb'] }}" alt="{{ $affiliate['name'] }}">
        <p class="type-h2 border-b border-hms-navy my-6">{{ $affiliate['name'] }}</p>
        <p class="type-b2">{{ $affiliate['description'] }}</p>
    </div>
    <a href="{{ $affiliate['link'] }}" target="_blank" rel="nofollow noopener" class="type-a1 text-center text-hms-navy mt-4 block">Website &gt;</a>
</div>
