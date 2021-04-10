<?php

namespace App\Http\Controllers;

use App\Grids\UsersGrid; 
use App\User; 
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Task;
use App\Message;
use App\Repositories\TaskRepository;

class GridController extends Controller
{
    public function index(Request $request)
    {
        return (new UsersGrid())
        ->create(['query' => User::query(), 'request' => $request])
        ->renderOn('admin.users');    

        $qqq= (new UsersGrid())
        ->create(['query' => User::query(), 'request' => $request]);
        var_dump($qqq->getData());
    }

}
