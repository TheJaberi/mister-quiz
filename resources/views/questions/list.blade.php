@extends('app')

<a class="top-right-corner blue-btn" href="{{ route('home') }}">Home</a>
@section('content')

    @error('error')
    <div class="error-msg2 mt2 center">
        {{ $message }}
    </div>
    @enderror

    <form action="{{ route('quiz') }}" method="post" id=quiz>
        @csrf
        @if ($questions)
            <div class="questionsquiz">
                @foreach ($questions as $question)
                    <x-question :question="$question" />
                @endforeach
                <input type="hidden" name="id" value={{ $quiz_id }}>
            </div>
        @endif
        <button type="submit" class="center green-btn">Submit</button>
    </form>

@endsection
