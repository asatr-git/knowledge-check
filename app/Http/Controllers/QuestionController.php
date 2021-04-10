<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Grids\LogGrid; 
use App\Log; 


class QuestionController extends Controller
{
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $questions = DB::select("select * from questions order by sort_order");
        foreach ($questions as $question){
            $answers = DB::select("select * from answers where question_id=".$question->id." order by sort_order");
            $question->answers = $answers;
        }        

        return view('question.index', [
            'questions' => $questions,
        ]);
    }

    public function next(Request $request)
    {
        $current_sortorder = 0;
        $first_article = DB::select("select * from articles  where sort_order > ".$current_sortorder." order by sort_order limit 1");
        return view('article.index', [
            'article' => $first_article,
        ]);
    }

}
