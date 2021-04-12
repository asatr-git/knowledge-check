<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Rule; 

class CheckupController extends Controller
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
        return view('admin.checkup', [
        ]);
    }
    
    public function getList(Request $request)
    {
      $checkups = DB::select("SELECT c.*, u.name as user_name, u.email, concat('http://knowledge-check/psw/', c.psw) as link  FROM checkups c left join users u on c.user_id=u.id order by c.created_at desc");
      header("Content-Type: application/json");
      echo json_encode($checkups);
    }

    // public function getitem($id)
    // {
    //     $question = DB::select("select * from questions where id=".$id);

    //     return view('admin.question_form', [
    //         'question' => $question,
    //     ]);
    // }
    
    // public function delitem(Request $request)
    // {

    //     DB::delete("delete from answers where id=".$request['id']);
    //     return response()->json(array('message'=> 'ok'), 200);
    // }

    public function create(Request $request)
    {
        DB::insert("insert into checkups(user_id, psw) values(".$request['user_id'].",'".uniqid() ."')");
        return redirect()->intended('admin/checkup');
    }

    // public function saveitem(Request $request) 
    // {
    //     if ($request["id"] > 0) {
    //         DB::update("update answers set name = '".$request["name"]."', is_right=".$request["is_right"].", sort_order =".$request["sort_order"]." where id=".$request["id"]);
    //     } else{
    //         $max_sort_order = DB::select("select max(sort_order) as max_sort_order from answers")[0]->max_sort_order + 10;
    //         DB::insert("insert into answers (name, question_id, sort_order) values ('".$request["name"]."', ".$request["question_id"].", ".$max_sort_order.")");
    //         // return redirect()->intended('admin/article');
    //     }
    // }

}
