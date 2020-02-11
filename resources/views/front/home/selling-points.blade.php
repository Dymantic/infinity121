<div class="wave-white-top bg-shady-blue px-6">
    <div class="pt-64">
        <div class="max-w-5xl mx-auto">
            <p class="type-h1 mb-20" data-usher>{{ trans('home.selling-points.heading') }}</p>
            <div class="grid-3x">
                @foreach($selling_points as $point)
                <div class="w-68 mx-auto" data-usher data-usher-delay="{{ $loop->index * .125 }}">
                    <p>@include("svg.icons.homepage.{$point}")</p>
                    <p class="type-h2">{!! trans("selling-points.{$point}.heading") !!}</p>
                    <p class="type-b2">{!! trans("selling-points.{$point}.text") !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
