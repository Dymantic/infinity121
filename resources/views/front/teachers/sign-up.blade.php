@extends('front.base')

@section('content')
    <div class="max-w-5xl px-6 pt-16 mx-auto">
        <div>
            <h1 class="type-h1 text-center text-hms-navy my-20">Come be a teacher, creeper.</h1>
            <p class="type-b1 text-hms-navy max-w-2xl mx-auto mb-8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam eos itaque iure maiores minima necessitatibus. A accusamus cumque delectus eligendi laudantium minima, omnis quaerat qui quibusdam quisquam repudiandae vero voluptate?</p>
        </div>

        <div>
            <teacher-signup :labels='@json($labels)'></teacher-signup>
        </div>

    </div>
@endsection