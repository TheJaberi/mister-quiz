@props(['question' => $question])

<div class="mb4">
    <p class="center title"> Category: {{ $question->category }} - {{ $question->xp }}XP</p>
    <p class="center title">{{ $question->question }} </p>

    <div class="checkboxes-wrapper" class="center">
        @foreach ($question->answers as $answer)
            <li>
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}"
                    id="{{ $answer->id }}">
                <label for="{{ $answer->id }}">{{ $answer->answer }}</label>
            </li>
        @endforeach


    </div>
</div>