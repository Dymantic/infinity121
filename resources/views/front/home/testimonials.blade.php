<div class="px-6 wave-white-top bg-mild-yellow">
    <div class="pt-64 md:pt-80">
        <div class="max-w-5xl mx-auto">
            <p data-usher class="type-h1 max-w-2xl mb-20">{{ trans('home.testimonials.heading') }}</p>
            <div data-flickity='{"cellAlign": "left", "contain": true, "prevNextButtons": false, "autoPlay": 5000, "wrapAround": true}'>
                @foreach($testimonials as $testimonial)
                    <div class="w-full py-4">
                        <div class="max-w-xl mx-auto p-8 bg-opaque quote-box relative">
                            <p class="type-b3 max-w-md mx-auto">{{ $testimonial['testimonial_content'] }}</p>
                            <p class="type-h2 text-right">{{ $testimonial['testimonial_author'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
<div class="splash py-80 px-6">
    <div class="md:pt-48">
        <div data-usher class="max-w-2xl mx-auto p-8 bg-opaque">
            <p class="type-h1 text-center mb-6">{!! trans('home.sign-up.heading') !!}</p>
            <p class="type-b1 text-center">{{ trans('home.sign-up.text') }}</p>
        </div>
        <div class="text-center py-32 md:py-48">
            <a href="" class="btn btn-bright">{!! trans('home.sign-up.button') !!}</a>
        </div>
    </div>
</div>
