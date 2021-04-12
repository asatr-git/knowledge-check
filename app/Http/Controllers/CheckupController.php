<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Grids\LogGrid; 
use App\Log; 


class CheckupController extends Controller
{
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $first_article = DB::select("select * from articles order by sort_order limit 1");
        return view('article.index', [
            'article' => $first_article,
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

    public function article($psw, $article_id=0)
    {
        $user = DB::select("select u.name  from checkups c left JOIN users u on c.user_id=u.id where c.psw='".$psw."'");
        if (count($user) == 0) {
            // There no test with this psw
            return view('welcome');
        }

        if ($article_id == 0) {
            // first article 
            $article = DB::select("select * from articles order by sort_order limit 1");
        }else{
            $article = DB::select("select * from articles where id=".$article_id);
        }

        $next_article = DB::select("select id from articles where sort_order>".$article[0]->sort_order." order by sort_order limit 1");
        if(count($next_article) == 0){
            $next_article_id = 0;
        }else{
            $next_article_id = $next_article[0]->id;
        }
        
        return view('article.index', [
            'psw' => $psw,
            'article' => $article,
            'next_article_id' => $next_article_id,
            'user_name' => $user[0]->name,
        ]);
    }

    public function checkanswers(Request $request)
    {
        $not_right_questions = array();
        $post = $request->all();
        foreach ($post as $key => $value) { // $key => $value is $question_id => $answer_id
            if($key == "psw"){ // password input from form
                continue;
            }
            $is_right = DB::select("SELECT * FROM answers where id=".$value)[0]->is_right;
            if($is_right != 1){
                $not_right_questions[] = DB::select("SELECT * FROM questions where id=".$key)[0]->name;
            }
        }

        DB::update("update checkups set finish_date=now() where psw='".$post['psw']."'");

        header("Content-Type: application/json");
        echo json_encode($not_right_questions);
    }

    public function result(Request $request)
    {
        return view('result.index', [
            'result' => 'result',
        ]);
    }
}
