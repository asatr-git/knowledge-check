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
    public function index($psw='')
    {
        $user = DB::select("select u.name  from checkups c left JOIN users u on c.user_id=u.id where c.psw='".$psw."'");
        if (count($user) == 0) {
            // There no test with this psw
            return view('welcome');
        }

        $questions = DB::select("select * from questions order by sort_order");
        foreach ($questions as $question){
            $answers = DB::select("select * from answers where question_id=".$question->id." order by sort_order");
            $question->answers = $answers;
        }        

        return view('question.index', [
            'psw' => $psw,
            'questions' => $questions,
            'user_name' => $user[0]->name,
        ]);
    }

    // public function next(Request $request)
    // {
    //     $current_sortorder = 0;
    //     $first_article = DB::select("select * from articles  where sort_order > ".$current_sortorder." order by sort_order limit 1");
    //     return view('article.index', [
    //         'article' => $first_article,
    //     ]);
    // }

}
