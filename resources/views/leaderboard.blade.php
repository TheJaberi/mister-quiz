@extends('app')

@section('content')
    <a class="top-right-corner red-btn" href="{{ route('home') }}">Back ></a>

    <div style="margin-top:100px; display: flex; justify-content: center;">
        <table style="border-collapse: collapse; width: 80%; text-align: center;">
            <thead>
                <tr>
                    <th style="border: 1px solid blue; padding: 8px; color:aqua">Rank</th>
                    <th style="border: 1px solid blue; padding: 8px; color:aqua">Username</th>
                    <th style="border: 1px solid blue; padding: 8px; color:aqua">Correct Answers Submitted</th>
                    <th style="border: 1px solid blue; padding: 8px; color:aqua">XP</th>
                    <th style="border: 1px solid blue; padding: 8px; color:aqua">Rank Title</th>
                </tr>
            </thead>
            <tbody style="color: white">
                @foreach ($TopTenUsers as $index => $user)
                    @php
                        $userxp = $user->xp;
                        $geographyscore = $user->geography;
                        $historyscore = $user->history;
                        $sciencescore = $user->science;
                        $sportscore = $user->sports;
                        $total = explode("/", $geographyscore)[0] +  explode("/", $historyscore)[0] +  explode("/", $sciencescore)[0] + explode("/", $sportscore)[0] ;
                    @endphp
                    <tr>
                        <td style="border: 1px solid blue; padding: 8px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid blue; padding: 8px;">{{ $user->username }}</td>
                        <td style="border: 1px solid blue; padding: 8px;">{{ $total }}</td>
                        <td style="border: 1px solid blue; padding: 8px;">{{ $userxp }} XP</td>
                        <td style="border: 1px solid blue; padding: 8px;">
                            @if ($userxp < 1500)
                                Quiz Apprentice
                            @elseif($userxp < 5000)
                                Average Quizer
                            @elseif($userxp < 10000)
                                Epic Quizer
                            @else
                                Quiz Master
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
