<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Rule; 

class UserController extends Controller
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
        return view('admin.user', [
        ]);
    }
    
    public function getList(Request $request)
    {
      $articles_cnt = DB::select("select count(*) as cnt from articles")[0]->cnt;  
      $checkups = DB::select("
        SELECT u.*, c.last_activity, c.is_completed, c.wrong_attempts, 
            concat(articles_completed, '/',".$articles_cnt.") as completed,
            concat('http://knowledge-check/psw/', c.psw) as link  
            FROM  users u  left join checkups c on c.user_id=u.id 
            order by c.created_at desc");

        header("Content-Type: application/json");
        echo json_encode($checkups);
    }

    public function delitem(Request $request)
    {
        DB::delete("delete from users where is_admin<>1 and id=".$request['id']);
        return response()->json(array('message'=> 'ok'), 200);
    }

    public function saveitem(Request $request) 
    {
        if ($request["id"] > 0) {
            DB::update("update users set name = '".$request["name"]."', email=".$request["email"]." where id=".$request["id"]);
        } else{
            DB::insert("insert into users (password, name, email) values ('psw', '".$request["name"]."', '".$request["email"]."')");
            $last_id = DB::select("select max(id) as last_id from users")[0]->last_id;
            DB::insert("insert into checkups (user_id, psw) values (".$last_id.", '".uniqid()."')");
        }
    }

}
