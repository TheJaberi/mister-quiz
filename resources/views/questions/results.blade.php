@extends('app')

@section('content')

<a class="top-left-corner blue-btn" href="{{ route('profile') }}"> {{auth()->user()->username}} </a>

<a class="top-right-corner blue-btn" href="{{ route('home') }}">
    < Home</a>

        <div class="center text-center content">
            <div>
                <p class="title">Your score was</p>
                <p class="title" style="font-size:70px; font-style:bold;">
                    {{ $results['overall'] }} / 10
                </p>
            </div>

            <div class="results-wrapper">
                <div class="result">
                    <p>Art</p>
                    <p class="title">{{ $results['art'] }} / 2</p>
                </div>
                <div class="result">
                    <p>Geography</p>
                    <p class="title">{{ $results['geography'] }} / 2</p>
                </div>
                <div class="result">
                    <p>History</p>
                    <p class="title">{{ $results['history'] }} / 2</p>
                </div>
                <div class="result">
                    <p>Science</p>
                    <p class="title">{{ $results['science'] }} / 2</p>
                </div>
                <div class="result">
                    <p>Sports</p>
                    <p class="title">{{ $results['sports'] }} / 2</p>
                </div>

            </div>
        </div>

        @endsection