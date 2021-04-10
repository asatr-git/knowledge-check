<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Grids\LogGrid; 
use App\Log; 


class CheckupController extends Controller
{
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $first_article = DB::select("select * from articles order by sort_order limit 1");
        return view('article.index', [
            'article' => $first_article,
        ]);
    }

    public function next(Request $request)
    {
        $current_sortorder = 0;
        $first_article = DB::select("select * from articles  where sort_order > ".$current_sortorder." order by sort_order limit 1");
        return view('article.index', [
            'article' => $first_article,
        ]);
    }

    public function result(Request $request)
    {
        return view('result.index', [
            'result' => 'result',
        ]);
    }

}
