@extends('front.base')

@section('content')
<div class="max-w-5xl px-6 pt-16 mx-auto">
    <div>
        <h1 class="type-h1 text-center my-20">Enrol with iTC now, start tomorrow.</h1>
        <p class="type-b1 max-w-2xl mx-auto mb-8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam eos itaque iure maiores minima necessitatibus. A accusamus cumque delectus eligendi laudantium minima, omnis quaerat qui quibusdam quisquam repudiandae vero voluptate?</p>
    </div>

    <div>
        <student-signup :course="{{ $course }}" :subjects='@json($subjects)' :labels='@json($labels)'></student-signup>
    </div>

</div>

@endsection
