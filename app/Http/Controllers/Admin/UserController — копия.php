<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Grids\UsersGrid; 
use App\User; 

class UserController extends Controller
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
        //dd(User::query());
        return (new UsersGrid())
        ->create(['pagination1' => "2", 'query' => User::query(), 'request' => $request])
        ->renderOn('admin.users');    

        $qqq= (new UsersGrid())
        ->create(['query' => User::query(), 'request' => $request]);
        var_dump($qqq->getData());
    }

}
