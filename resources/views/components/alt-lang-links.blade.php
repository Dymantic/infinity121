@foreach($alternatives as $alt)
    <link rel="alternate" hreflang="{{ $alt['locale'] }}"
          href="{{ url(translatedUrl(Request::path(), $alt['locale'])) }}">
@endforeach
