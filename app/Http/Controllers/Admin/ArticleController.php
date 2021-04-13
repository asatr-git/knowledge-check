<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Article; 

class ArticleController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

    }

    /**
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('admin.article', []);
    }

    public function getAjaxList(Request $request)
    {
        $result = [];
      
        $articles =DB::select("select * from articles");
      
        $article_object = new \stdClass();
        $article_object->id = 0;
        $article_object->name = "";
        array_push($result, $article_object);

        foreach ($articles as $article) {
            $article_object = new \stdClass();
            $article_object->id = (integer)$article->id;
            $article_object->name = (string)$article->name;

            array_push($result, $article_object);
        }

        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function getList(Request $request)
    {
        $articles = DB::select("select * from articles order by sort_order");
        header("Content-Type: application/json");
        echo json_encode($articles);
    }

    public function getitem($id)
    {
        $article = DB::select("select * from articles where id=".$id);

        return view('admin.article_form', [
            'article' => $article,
        ]);
    }
    
    public function delitem(Request $request)
    {
        DB::delete("delete from articles where id=".$request['id']);
        return response()->json(array('message'=> 'ok'), 200);
    }

    public function saveitem(Request $request) 
    {
        if ($request["id"] > 0) {
            DB::update("update articles set name = '".$request["name"]."', sort_order =".$request["sort_order"]." where id=".$request["id"]);
        } else{
            $max_sort_order = DB::select("select max(sort_order) as max_sort_order from articles")[0]->max_sort_order + 10;
            DB::insert("insert into articles (name, sort_order) values ('".$request["name"]."', ".$max_sort_order.")");
            // return redirect()->intended('admin/article');
        }
    }

    public function savebody(Request $request) 
    {
        $article = Article::find($request["id"]);
        $article->body = $request["body"];
        $article->save();

        return redirect()->intended('admin/article');
    }
}
