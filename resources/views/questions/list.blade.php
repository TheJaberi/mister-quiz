@extends('app')

<a class="top-right-corner blue-btn" href="{{ route('home') }}">Home</a>
@section('content')

    <form action="{{ route('quiz') }}" method="post">
        @csrf

        @if ($questions)
            @foreach ($questions as $question)
                <x-question :question="$question" />
            @endforeach
        @endif

        <button type="submit" class="center green-btn">Submit</button>
    </form>


@endsection
