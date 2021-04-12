<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App; 
use App\Rule; 

class RuleController extends Controller
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
        return view('admin.rule', [
        ]);
    }
    
    public function getList(Request $request)
    {
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
        $item = Rule::getById($id);
        return response()->json(array('message'=> 'ok', 'item'=> $item), 200);
    }
    
    public function delitem(Request $request)
    {
        $rule = new Rule;
        $rule->delete($request['id']);        

        return response()->json(array('message'=> 'ok'), 200);
    }

    public function saveitem(Request $request) 
    {
        $rule_object = new \stdClass();
        $rule_object->id = $request["id"];
        $rule_object->title = $request["title"];
        $rule_object->description = $request["description"];

        $rule = new Rule;
        $rule->save($rule_object);        
    
    }

}
