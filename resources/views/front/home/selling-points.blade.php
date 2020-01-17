<div class="wave-white-bottom bg-shady-blue px-6">
    <div class="">
        <div class="max-w-5xl mx-auto">
            <p class="type-h1 text-hms-navy mb-20">{{ trans('home.selling-points.heading') }}</p>
            <div class="grid-3x pb-64">
                @foreach($selling_points as $point)
                <div class="w-68 mx-auto">
                    <p>@include("svg.icons.homepage.{$point}")</p>
                    <p class="type-h2 text-hms-navy">{!! trans("selling-points.{$point}.heading") !!}</p>
                    <p class="type-b2 text-hms-navy">{!! trans("selling-points.{$point}.text") !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="h-80"></div>
</div>
