<div class="w-64 card flex flex-col justify-between" data-usher>
    <div>
        <img src="{{ $affiliate['logo_thumb'] }}" alt="{{ $affiliate['name'] }}">
        <p class="type-h2 border-b border-hms-navy my-6">{{ $affiliate['name'] }}</p>
        <p class="type-b2">{{ $affiliate['description'] }}</p>
    </div>
    <a href="{{ $affiliate['link'] }}" target="_blank" rel="nofollow noopener" class="type-a1 text-center mt-4 block arrow-link-left hover:text-hms-navy">Website</a>
</div>
