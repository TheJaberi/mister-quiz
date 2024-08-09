<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Question_Quiz;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function page()
    {

        // get quiz that user is currently taking
        $quiz = Quiz::where('user_id', auth()->user()->id)->where('completed', false)->first();
        $questions = collect();

        if ($quiz) {
            $questions = $quiz->getQuestions();
        } else {

            $categories = ['Art', 'History', 'Geography', 'Science', 'Sports'];

            foreach ($categories as $category) {
                $categoryQuestions = Question::where('category', $category)->inRandomOrder()->limit(2)->get();
                $questions = $questions->concat($categoryQuestions);
            }
            $questions->map(function ($question) {
                $answers = Answer::where('question_id', $question->id)->get();
                $question->answers = $answers;
                return $question;
            });


            //save in question_quiz and in quiz table
            $quiz = new Quiz();
            $quiz->user_id = auth()->user()->id;
            $quiz->completed = false;
            $quiz->save();

            $questionIds = $questions->pluck('id')->toArray();
            foreach ($questionIds as $questionId) {
                $question_quiz = new Question_Quiz();
                $question_quiz->question_id = $questionId;
                $question_quiz->quizzes_id = $quiz->id;
                $question_quiz->save();
            }
        }


        return view('questions/list', [
            'questions' => $questions
        ]);
    }
    public function store(Request $request)
    {
        $questions = $request->answers;
        // ddd($questions);
        $score = 0;
        $user = User::find(auth()->user()->id);
        $artScore = explode('/', $user->art);
        $historyScore = explode('/', $user->history);
        $geographyScore = explode('/', $user->geography);
        $scienceScore = explode('/', $user->science);
        $sportsScore = explode('/', $user->sports);
        foreach ($questions as $questionId => $answerId) {
            $answer = Answer::find($answerId);
            if ($answer) {
                $question = Question::find($questionId);
                if ($answer->correct) {
                    $point = 1;
                } else {
                    $point = 0;
                }
                $category = $question->category;
                if ($category == 'Art') {
                    $artScore[0] += $point;
                    $artScore[1] += 1;
                } elseif ($category == 'History') {
                    $historyScore[0] += $point;
                    $historyScore[1] += 1;
                } elseif ($category == 'Geography') {
                    $geographyScore[0] += $point;
                    $geographyScore[1] += 1;
                } elseif ($category == 'Science') {
                    $scienceScore[0] += $point;
                    $scienceScore[1] += 1;
                } elseif ($category == 'Sports') {
                    $sportsScore[0] += $point;
                    $sportsScore[1] += 1;
                }
                
                $user->xp += $question->xp;

            }
        }

        $user->art = implode('/', $artScore);
        $user->history = implode('/', $historyScore);
        $user->geography = implode('/', $geographyScore);
        $user->science = implode('/', $scienceScore);
        $user->sports = implode('/', $sportsScore);


        $user->save();
        $quiz = Quiz::where('user_id', auth()->user()->id)->first();
        $quiz->completed = true;
        $quiz->save();
        return redirect()->route('leaderboard');
    }
}
