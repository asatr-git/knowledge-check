<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 

class ServiceController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */

    /**
     * Create a new controller instance.
     *
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');

    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('admin.service');
    }

    public function setAllToReadmode(Request $request)
    {
        DB::update('update user_mode set mode_id=0');
        return redirect()->intended('admin/service');    
    }
}
