<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function page()
    {

        // get quiz that user is currently taking
        $questions = Question::inRandomOrder()->limit(10)->get();
        $categories = ['Art', 'History', 'Geography', 'Science', 'Sports'];
        $questions = collect();

        foreach ($categories as $category) {
            $categoryQuestions = Question::where('category', $category)->inRandomOrder()->limit(2)->get();
            $questions = $questions->concat($categoryQuestions);
        }
        $questions->map(function ($question) {
            $answers = Answer::where('question_id', $question->id)->get();
            $question->answers = $answers;
            return $question;
        });
        // if (!$quiz) {
        //     $quiz = new Quiz();
        //     $quiz->user_id = auth()->user()->id;
        //     $quiz->completed = false;
        //     $questions = Question::inRandomOrder()->limit(10)->get();
        //     $quiz->save();
        // }


        return view('questions/list', [
            'questions' => $questions
        ]);
    }
    public function store(Request $questions)
    {
        $data = $questions->all();
        $score = 0;
        foreach ($data as $questionId => $answerId) {
            $question = Question::find($questionId);
            if ($question->correct_answer_id == $answerId) {
                $score++;
            }
        }
        $quiz = Quiz::where('user_id', auth()->user()->id)->first();
        $quiz->score = $score;
        $quiz->completed = true;
        $quiz->save();
        return redirect()->route('home');
    }
}
