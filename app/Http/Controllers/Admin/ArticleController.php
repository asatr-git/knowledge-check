<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Rule; 

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
    
    public function getList(Request $request)
    {
      $articles = DB::select("select * from articles order by sort_order");
      header("Content-Type: application/json");
      echo json_encode($articles);


      return;  
      $result = array();
      
      $rules = new Rule; 
      $rule_list = $rules->getList();
      
      foreach ($rule_list as $rule) {
        $rule_object = new \stdClass();
        $rule_object->id = (string)$rule["id"];
        $rule_object->title = (string)$rule->title;
        $rule_object->description = (string)$rule->description;
  
        array_push($result, $rule_object);
      }
      
      header("Content-Type: application/json");
      echo json_encode($result);
    }

    public function getitem($id)
    {
        $article = DB::select("select * from articles where id=".$id);

        return view('admin.article_form', [
            'article' => $article,
        ]);


        // $item = Rule::getById($id);
        return response()->json(array('message'=> 'ok', 'item'=> $item), 200);
    }
    
    public function delitem(Request $request)
    {

        DB::delete("delete from articles where id=".$request['id']);
        // $rule = new Rule;
        // $rule->delete($request['id']);        

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
        DB::update("update articles set body = '".$request["body"]."' where id=".$request["id"]);
        return redirect()->intended('admin/article');

    }


}
