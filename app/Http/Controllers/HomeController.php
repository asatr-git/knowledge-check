<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Grids\LogGrid; 
use App\Log; 


class HomeController extends Controller
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
            $user_name = '';
        }else{
            $user_name = $user[0]->name;
        }

        return view('welcome', [
            'user_name' => $user_name,
            'psw' => $psw,
        ]);
    }

}
