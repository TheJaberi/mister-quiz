<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Question_Quiz;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            'quiz_id' => $quiz->id,
            'questions' => $questions
        ]);
    }
    public function store(Request $request)
    {
        $questions = $request->answers;
        
        if (empty($questions) || count($questions) != 10 ){
            throw ValidationException::withMessages([
                'error' => 'Answers all questions before submitting.'
            ]);
        }

        $results = [
            'art' => 0,
            'history' => 0,
            'geography' => 0,
            'science' => 0,
            'sports' => 0,
            'overall' => 0
        ];    

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
                    $results['overall']++;
                    $user->xp += $question->xp;

                } else {
                    $point = 0;
                }
                $category = $question->category;
                if ($category == 'Art') {
                    $artScore[0] += $point;
                    $artScore[1] += 1;
                    $results['art'] += $point;
                } elseif ($category == 'History') {
                    $historyScore[0] += $point;
                    $historyScore[1] += 1;
                    $results['history'] += $point;
                } elseif ($category == 'Geography') {
                    $geographyScore[0] += $point;
                    $geographyScore[1] += 1;
                    $results['geography'] += $point;
                } elseif ($category == 'Science') {
                    $scienceScore[0] += $point;
                    $scienceScore[1] += 1;
                    $results['science'] += $point;
                } elseif ($category == 'Sports') {
                    $sportsScore[0] += $point;
                    $sportsScore[1] += 1;
                    $results['sports'] += $point;
                }
                


            }
        }

        $user->art = implode('/', $artScore);
        $user->history = implode('/', $historyScore);
        $user->geography = implode('/', $geographyScore);
        $user->science = implode('/', $scienceScore);
        $user->sports = implode('/', $sportsScore);
        $user->save();

        $quiz = Quiz::where('id', $request->id)->first();
        $quiz->completed = true;
        $quiz->save();
        return redirect()->route('results')->with('results', $results);
    }
}
