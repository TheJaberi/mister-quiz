@extends('app')

@section('content')

<a class="top-right-corner red-btn" href="{{ route('home') }}">Back ></a>

<div style="margin-top:100px">
    <div class="profile-header">
        <p class="title profile-name">{{ auth()->user()->username }}</p>
        <p class="title profile-email">{{ auth()->user()->email }}</p>
    </div>

    <div class="profile-header">
        @php
            $userxp = auth()->user()->xp; 
            $geographyscore = auth()->user()->geography;
            $historyscore = auth()->user()->history;
            $sciencescore = auth()->user()->science;
            $sportscore = auth()->user()->sports;
            function calculatePercentage($score) {
                list($correct, $total) = explode('/', $score);
                if ($total == 0) {
                    return '0%'; 
                }
                return round(($correct / $total) * 100, 2) . '%';
            }
        @endphp
        <p class="title profile-xp">{{ $userxp }} XP</p>
        <p class="title profile-rank">
          
            @if($userxp < 1500)
                Quiz Apprentice
            @elseif($userxp < 5000)
                Average Quizer
            @elseif($userxp < 10000)
                Epic Quizer
            @else
                Quiz Master
            @endif
        </p>
        <br>
        <div>
            <p class="title scores">Geography: {{calculatePercentage($geographyscore)}} at {{$geographyscore}}</p>
            <p class="title scores">    History: {{calculatePercentage($historyscore)}} at {{$historyscore}}</p>
            <p class="title scores"> Science: {{calculatePercentage($sciencescore)}}    at {{$sciencescore}}</p>
            <p class="title scores"> Sports: {{calculatePercentage($sportscore)}}       at {{$sportscore}}</p>
        </div>
    </div>
</div>



@endsection