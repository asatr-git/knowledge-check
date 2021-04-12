<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Rule; 

class QuestionController extends Controller
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
        return view('admin.question', [
        ]);
    }
    
    public function getList(Request $request)
    {
      $questions = DB::select("select * from questions order by sort_order");
      header("Content-Type: application/json");
      echo json_encode($questions);
    }

    public function getitem($id)
    {
        $question = DB::select("select * from questions where id=".$id);

        return view('admin.question_form', [
            'question' => $question,
        ]);
    }
    
    public function delitem(Request $request)
    {
        DB::delete("delete from answers where question_id=".$request['id']);
        DB::delete("delete from questions where id=".$request['id']);
        return response()->json(array('message'=> 'ok'), 200);
    }

    public function saveitem(Request $request) 
    {
        if ($request["id"] > 0) {
            DB::update("update questions set name = '".$request["name"]."', sort_order =".$request["sort_order"]." where id=".$request["id"]);
        } else{
            $max_sort_order = DB::select("select max(sort_order) as max_sort_order from questions")[0]->max_sort_order + 10;
            DB::insert("insert into questions (name, sort_order) values ('".$request["name"]."', ".$max_sort_order.")");
            // return redirect()->intended('admin/article');
        }
    }

    public function savebody(Request $request) 
    {
        DB::update("update articles set body = '".$request["body"]."' where id=".$request["id"]);
        return redirect()->intended('admin/article');

    }


}
