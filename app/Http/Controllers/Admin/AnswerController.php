<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Answer;
use App\Article;

class AnswerController extends Controller
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
    
    public function getList($question_id)
    {
      $answers = DB::select("select * from answers where question_id=".$question_id." order by sort_order");
      header("Content-Type: application/json");
      echo json_encode($answers);
    }

    // public function getitem($id)
    // {
    //     $question = DB::select("select * from questions where id=".$id);

    //     return view('admin.question_form', [
    //         'question' => $question,
    //     ]);
    // }
    
    public function delitem(Request $request)
    {

        DB::delete("delete from answers where id=".$request['id']);
        return response()->json(array('message'=> 'ok'), 200);
    }

    public function saveitem(Request $request) 
    {
        // $answer = new Answer;

        // $answer->name = $request["name"];
        // $answer->question_id = $request["question_id"];
        // $max_sort_order = DB::select("select max(sort_order) as max_sort_order from answers")[0]->max_sort_order + 10;
        // $answer->sort_order = $max_sort_order;
        // $answer->save();        
        if ($request["id"] > 0) {
            DB::update("update answers set name = '".$request["name"]."', is_right=".$request["is_right"].", sort_order =".$request["sort_order"]." where id=".$request["id"]);
        } else{
            $max_sort_order = DB::select("select max(sort_order) as max_sort_order from answers")[0]->max_sort_order + 10;
            DB::insert("insert into answers (name, question_id, sort_order) values ('".$request["name"]."', ".$request["question_id"].", ".$max_sort_order.")");
            // return redirect()->intended('admin/article');
        }
    }

    public function bulkadd($question_id)
    {
        $question = DB::table('questions')->where('id', $question_id)->first();

        return view('admin.answer_bulkadd', [
            'question' => $question,
        ]);
    }

    public function bulksave(Request $request)
    {
        $post = $request->all();
        $question_id = $post['question_id'];
        $answers = mb_split('\n', $post['answers']);

        foreach ($answers as $answer) {
            $max_sort_order = DB::select("select max(sort_order) as max_sort_order from answers")[0]->max_sort_order + 10;
            DB::table('answers')->insert(
                ['question_id' => $question_id, 'name' => $answer, 'sort_order' => $max_sort_order]
            );            
        }

        return redirect()->intended('admin/question/'.$question_id);
    }

}
