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
    public function cleardb(Request $request)
    {
        $first_article = DB::select("select * from articles order by sort_order limit 1");
        DB::delete("delete from checkups");
        DB::delete("delete from articles");
        DB::delete("delete from answers");
        DB::delete("delete from questions");
        DB::delete("delete from users where is_admin<>1");

        return redirect()->intended("/");
    }


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
        // No test with this psw
        $user = DB::select("select u.name, c.current_article_id  from checkups c left JOIN users u on c.user_id=u.id where c.psw='".$psw."'");
        if (count($user) == 0) {
            // There no test with this psw
            return view('welcome');
        }

        // Test already comleted
        $is_test_completed = DB::select("select is_completed from checkups where psw='".$psw."'")[0]->is_completed;
        if ($is_test_completed == 1) {
            return redirect()->intended("/checkup-result");
        } 

        if ($article_id == 0) {
            $current_article_id = $user[0]->current_article_id;
            if ($current_article_id == 0) {
                // first article 
                $first_article_id = DB::select("select * from articles order by sort_order limit 1")[0]->id;
                return redirect()->intended("/psw/$psw/article/$first_article_id");
            } else {
                return redirect()->intended("/psw/$psw/article/$current_article_id");
            }
        }else{
            $article = DB::select("select * from articles where id=".$article_id);
        }

        $next_article = DB::select("select id from articles where sort_order>".$article[0]->sort_order." order by sort_order limit 1");
        if(count($next_article) == 0){
            $next_article_id = 0;
        }else{
            $next_article_id = $next_article[0]->id;
        }
        
        $questions = DB::select("select * from questions where article_id=".$article_id." order by sort_order");
        foreach ($questions as $question){
            $answers = DB::select("select * from answers where question_id=".$question->id." order by sort_order");
            $question->answers = $answers;
        }        

        return view('article.index', [
            'psw' => $psw,
            'user_name' => $user[0]->name,
            'article' => $article,
            'next_article_id' => $next_article_id,
            'questions' => $questions,
        ]);
    }

    public function checkanswers(Request $request)
    {
        $wrong_questions = array();
        $post = $request->all();
        foreach ($post as $key => $value) { // $key => $value is $question_id => $answer_id
            if($key == "psw" || $key == "article_id" ){ // password and article_id input from form
                continue;
            }
            $is_right = DB::select("SELECT * FROM answers where id=".$value)[0]->is_right;
            if($is_right != 1){
                $wrong_questions[] = DB::select("SELECT * FROM questions where id=".$key)[0]->name;
            }
        }

        if (count($wrong_questions) == 0) {
            // successfull attempt
            $current_article = DB::select("select * from articles where id=".$post['article_id']);
            $next_article = DB::select("select id from articles where sort_order>".$current_article[0]->sort_order." order by sort_order limit 1");

            if(count($next_article) == 0){
                $next_article_id = 0;
                DB::update("update checkups set is_completed=1 where psw='".$post['psw']."'");
            }else{
                $next_article_id = $next_article[0]->id;
            }

            DB::update("update checkups set current_article_id=".$next_article_id." where psw='".$post['psw']."'");
            DB::update("update checkups set articles_completed = articles_completed+1 where psw='".$post['psw']."'");
        }else{
            // wrong attempt
            DB::update("update checkups set wrong_attempts = wrong_attempts+1 where psw='".$post['psw']."'");
        }

        DB::update("update checkups set last_activity=now() where psw='".$post['psw']."'");

        header("Content-Type: application/json");
        echo json_encode($wrong_questions);
    }

    public function result(Request $request)
    {
        return view('result.index', [
            'result' => 'result',
        ]);
    }
}
