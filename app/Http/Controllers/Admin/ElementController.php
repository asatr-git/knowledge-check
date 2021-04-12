<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Message;
use App\Relation;
use App\RelationType;
use App\Repositories\TaskRepository;
use App\Element;
use App\Schema;
use App\SchemaType;
use App\LinkType;
use App\Utils;
use App\GitWrapper;
use App\ElementComments;

class ElementController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * Create a new controller instance.
     *
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        //$this->middleware('auth');

        //$this->tasks = $tasks;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $schema_types = new SchemaType;
        //$schema_types->load();
    
        $schemes = Schema::getList();

        // количество элементов (всего и новых) в каждой схеме
        $schemes_el_count = [];
        foreach ($schemes as $schema) {
            try {
                $schema_count = Schema::getElementsCount($schema['id']);
                $schemes_el_count[(string)$schema['id']]["all"] = $schema_count["all"];
                $schemes_el_count[(string)$schema['id']]["new"] = $schema_count["new"];
            } catch (\Throwable $th) {
                $schemes_el_count[(string)$schema['id']]["all"] = 0;
                $schemes_el_count[(string)$schema['id']]["new"] = 0;
            }
        }

        $new_schemes = [];
        if (!Auth::guest()) {
            $id_schemes = [];
            $id_schemes = DB::select("select distinct schema_id  from viewed_messages where user_id=".Auth::user()->id);
            foreach ($id_schemes as $id_schema) {
                try {
                    $new_schemes[] = [$id_schema->schema_id, substr(Schema::getById($id_schema->schema_id)->name, 0, 200)];
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        return view('schema.list', [
            'current_element' => null,
            'schemes' => $schemes,
            'schema_types' => $schema_types,
            'element_types' => null,
            'new_schemes' => $new_schemes,
            'element_count' => $schemes_el_count,
        ]);
    }

    public function element(Request $request, $element_id)
    {
        $messages = Message::getList($element_id);
        $element_types = DB::select("select * from message_type");

        return view('element.list', [
            'current_element' => $messages['current_element'],
            'linked_elements' => $messages['linked_elements'],
            'element_types' => $element_types,
        ]);
    }


    /**
     * Возвращает html код списка дискуссий без текущего($element_id).
     */
    public function root_elements($element_id)
    {
        $schemes = Schema::getList();
        $html = '';
        foreach ($schemes as $schema) {
            if ($schema['id'] != $element_id) {
                $html .= '<div><a id="root_element_'.$schema['id'].'" onclick="addLinkElement('.$schema['id'].');" href="#">'.$schema->name.'</a></div>';
            }
        }
        return response()->json(['message'=> 'ok', 'html'=> $html], 200);
    }

    public function oneelement($schema_id, $element_id)
    {
        $page_url = $_SERVER["HTTP_REFERER"];
        if (strpos($page_url, 'schema-xml')  === false) {
            // work with db
            $element = Element::find($element_id);
            if (Auth::user()->is_admin == 1 && $element_id > 0) {
                $element->is_comment_changed = 0;
                $element->save();
            }
        } else {
            // work with xml
            $element_xml = Message::getElementFromXml($schema_id, $element_id);
            $element['id'] = $element_id;
            if (!$element_xml) {
                $element['message_text'] = '';
            } else {
                $element['message_text'] = (string)$element_xml->txt;
                $element['type_id'] = (string)$element_xml->type_id;
            }
        }
      
      
        //$qqq = LinkType::getById(2);
        //return response()->json(array('message'=> (string)$qqq->color, 'element'=> $element), 200);
      
        return response()->json(['message'=> 'ok', 'element'=> $element], 200);
    }

    /*
    Добавление/редактирование элемента
    */
    public function addelement(Request $request, $element_id = 0)
    {
        // Проверка на режим readonly
        if (Utils::getUserMode($request["schema_id"]) != 1) {
            return;
        }

        // Запрет на добавление если есть нарушение правил.
        if (!$request["id"] > 0) {
            if (DB::select("select count(*) as cnt from rule_violations")[0]->cnt > 0) {
                return response()->json(['message'=> 'rule_violation'], 200);
            }
        } else {
            $edited_element = Message::getElementFromXml($request["schema_id"], $request["id"]);
        }

        $selected_element_id = $request["selected_element_id"];

        $element_object = new \stdClass();
        $element_object->id = $request["id"];
        $element_object->txt = $request["message_text"];
        $element_object->type_id = $request["element_type_id"];
        if ($selected_element_id > 0) {
            $selected_element = Message::getElementFromXml($request["schema_id"], $selected_element_id);
            $element_object->pos_x = $selected_element->pos_x + 200;
            $element_object->pos_y = $selected_element->pos_y + 100;
        }
      
        // создаем элемент типа ссылка на схему
        if (!$request["id"] > 0 && $request["create_schema"] == 'true') {
            $new_schema_id = Schema::edit(0, $request["message_text"], 2);
            $element_object->schema_id = $new_schema_id;
        }

        $old_text = '';
        if ($request["id"] > 0) {
            $old_text = $edited_element->txt;

            //автоматом снимаем нарушение правила после редактирования элемента
            $element_object->rule_id = '';
            $sql_txt = "delete from rule_violations where schema_id=".$request["schema_id"]." and element_id=".$request["id"];
            DB::delete($sql_txt);
        }

        // сохранение элемента
        $element_id = Message::saveElementToXml($request["schema_id"], $element_object);

        // если редактируем текст существующего элемента типа ссылка на схему, то меняем название схемы
        if ($request["id"] > 0) {
            if ($edited_element->schema_id > 0) {
                Schema::edit((string)$edited_element->schema_id, $request["message_text"], 2);
            }
        }

        // Заносим элемент в Notification - New если изменился его текст
        if ($old_text != $request["message_text"]) {
            $user_list = DB::select("select * from users where id<>".Auth::user()->id);
            foreach ($user_list as $user) {
                $sql_txt = "insert into viewed_messages(schema_id, message_id, user_id) values(". $request["schema_id"] ." ,".$element_id.", ". $user->id .")";
                DB::insert($sql_txt);
            }
        }

        // Добавляем связь созданного элемента и выделенного
        if (!$request["id"] > 0 && $selected_element_id > 0) {
            $relationType = LinkType::getById($request["relation_type_id"]);
  
            $link_object = new \stdClass();
            $link_object->link_type_id = $request["relation_type_id"];
            $link_object->source_id = $selected_element_id;
            $link_object->target_id = $element_id;
            $link_object->color = $relationType->color;
            $link_object->label = $relationType->title;
            Message::xmlLinkAdd($request["schema_id"], $link_object);
        }
        //var_dump($request);
        
        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        // Git commit
        $commit_msg = $request["id"] > 0 ? 'edit_element' : 'add_element';
        GitWrapper::Commit($request["schema_id"], "element_contrloller_$commit_msg");

        return response()->json(['message'=> 'ok'], 200);
    }
  

    // Добавить в схему элемент типа ссылка на схему
    public function addLinkToSchema(Request $request)
    {
        $selected_element_id = $request["selected_element_id"];
        $schema_id = $request["schema_id"];
        $linked_schema_id = $request["linked_schema_id"];

        $element_object = new \stdClass();
        $element_object->id = 0;
        $element_object->txt = '';
        $element_object->type_id = $request["element_type_id"];
        $element_object->schema_id = $linked_schema_id;
        
        if ($selected_element_id > 0) {
            $selected_element = Message::getElementFromXml($request["schema_id"], $selected_element_id);
            $element_object->pos_x = $selected_element->pos_x + 200;
            $element_object->pos_y = $selected_element->pos_y + 100;
        }
      
        $element_id = Message::saveElementToXml($schema_id, $element_object);
        
        // Заносим элемент в Notification - New если изменился его текст
          $user_list = DB::select("select * from users where id<>".Auth::user()->id);
        foreach ($user_list as $user) {
            $sql_txt = "insert into viewed_messages(schema_id, message_id, user_id) values(". $request["schema_id"] ." ,".$element_id.", ". $user->id .")";
            DB::insert($sql_txt);
        }

        // Добавляем связь созданного элемента и выделенного
        if (!$request["id"] > 0 && $selected_element_id > 0) {
            $relationType = LinkType::getById($request["relation_type_id"]);
  
            $link_object = new \stdClass();
            $link_object->link_type_id = $request["relation_type_id"];
            $link_object->source_id = $selected_element_id;
            $link_object->target_id = $element_id;
            $link_object->color = $relationType->color;
            $link_object->label = $relationType->title;
            Message::xmlLinkAdd($request["schema_id"], $link_object);
        }
        //var_dump($request);
        
        // Git commit
        GitWrapper::Commit($request["schema_id"], 'element_contrloller_addLinkToSchema');

        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();
        
        return response()->json(['message'=> 'ok'], 200);
    }

    public function savecontext(Request $request, $element_id)
    {
          //$element = Element::find($request["id"]);
          $element = Element::find($element_id);
          $element->context = $request["context_text"];
          $element->save();
          return response()->json(['message'=> 'ok'], 200);
    }

    public function relationDel(Request $request, $schema_id, $source_id, $target_id)
    {
        $page_url = $_SERVER["HTTP_REFERER"];
        if (strpos($page_url, 'schema-xml')  === false) {
            Relation::where('message1_id', $source_id)
                ->where('message2_id', $target_id)
                ->delete();
        } else {
            //$shema_id = 1078;
            $link_object = new \stdClass();
            $link_object->source_id = $source_id;
            $link_object->target_id = $target_id;
            Message::xmlLinkDel($schema_id, $link_object);
        }

        // Git commit
        GitWrapper::Commit($schema_id, 'element_contrloller_relationDel');
      
        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        return response()->json(['message'=> 'ok'], 200);
    }

    public function relationAdd(Request $request, $sсhema_id, $source_id, $target_id, $relation_type)
    {
        $page_url = $_SERVER["HTTP_REFERER"];
        if (strpos($page_url, 'schema-xml')  === false) {
            $new_element = new Relation;
            $new_element->message1_id = $source_id;
            $new_element->message2_id = $target_id;
            $new_element->relation_type = $relation_type;
            $new_element->save();

            // Создаем бэкап для Ctrlz
            Utils::createCtrlzBackup();

            return response()->json(['message'=> 'ok'], 200);
        } else {
            $relationType = LinkType::getById($relation_type);

            //$shema_id = 1078;
            $link_object = new \stdClass();
            $link_object->link_type_id = $relation_type;
            $link_object->source_id = $source_id;
            $link_object->target_id = $target_id;
            $link_object->color = $relationType->color;
            $link_object->label = $relationType->title;
            Message::xmlLinkAdd($sсhema_id, $link_object);

            // Git commit
            GitWrapper::Commit($schema_id, 'element_contrloller_relationDel');

            // Создаем бэкап для Ctrlz
            Utils::createCtrlzBackup();

            return response()->json(['message'=> 'ok'], 200);
        }
    }

    public function relationEdit(Request $request, $sсhema_id, $source_id, $target_id, $relation_type)
    {
        $relationType = LinkType::getById($relation_type);
      
        //$shema_id = 1078;
        $link_object = new \stdClass();
        $link_object->source_id = $source_id;
        $link_object->target_id = $target_id;

        $link_type_object = new \stdClass();
        $link_type_object->color = $relationType->color;
        $title = $relationType->show_title == 1 ? $relationType->title : '';
        $link_type_object->label = $title;
        
        Message::xmlLinkEdit($sсhema_id, $link_object, $relation_type);

        // Git commit
        GitWrapper::Commit($schema_id, 'element_contrloller_relationEdit');

        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        return response()->json(['message'=> 'ok'], 200);
    }
    
    public function tranform_element_to_schema($sсhema_id, $element_id)
    {
        $element_txt = Message::getElementFromXml($sсhema_id, $element_id)->txt;
        $new_schema_id = Schema::edit(0, $element_txt, 2, $sсhema_id);
        
        $element_object = new \stdClass();
        $element_object->id = $element_id;
        $element_object->schema_id = $new_schema_id;
        //$element_object->txt = '<a href="'.$new_schema_id.'">'.$element_txt.'</a>';

        Message::saveElementToXml($sсhema_id, $element_object);

        // перемещение связанных элементов в созданную схему
        Message::moveToSchema($sсhema_id, $new_schema_id, $element_id);

        // Git commit
        GitWrapper::Commit($schema_id, 'element_contrloller_tranform_element_to_schema');

        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        return response()->json(['message'=> 'ok'], 200);
    }
    
    /**
     * Установливает нарушение правила у элемента.
     */
    public function setRuleViolation(Request $request, $sсhema_id, $element_id, $rule_id)
    {
        if ($rule_id == '-1') {
            $rule_id = ''; // удаляем $rule_id - элемент соответсвует правилам
            $sql_txt = "delete from rule_violations where schema_id=$sсhema_id and element_id=$element_id";
            DB::delete($sql_txt);
        } else {
            $sql_txt = "insert into rule_violations(schema_id, element_id, rule_id) values($sсhema_id, $element_id, $rule_id)";
            DB::insert($sql_txt);
        }

        Message::setRule($sсhema_id, $element_id, $rule_id);

        ElementComments::Add($element_id, $request['comment']);

        // Git commit
        GitWrapper::Commit($schema_id, 'element_contrloller_setRuleViolation');

        return response()->json(['message'=> 'ok'], 200);
    }

    // Удаление элемента
    public function delelement(Request $request, $schema_id, $element_id = 0)
    {
        // Проверка на режим readonly
        if (Utils::getUserMode($schema_id) != 1) {
            return;
        }
        
        Message::xmlElementDel($schema_id, $element_id);

        // Удаление из новых и нарушения правил
        $sql_txt = "delete from rule_violations where schema_id=$schema_id and element_id=$element_id";
        DB::delete($sql_txt);
        $sql_txt = "delete from viewed_messages where schema_id=$schema_id and message_id=$element_id";
        DB::delete($sql_txt);

        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        // Git commit
        GitWrapper::Commit($schema_id, 'element_contrloller_delelement()');

        return redirect('/elements');
        //return response()->json(array('message'=> 'ok'), 200);
        //return back()->withInput();
    }


    /*
    Если элемент есть в master - запрос на удаление элемента, иначе - удаляем
    */
    public function requestToDeleteElement(Request $request, $schema_id, $element_id = 0)
    {
        if (GitWrapper::isFileExistOnMaster("xml/$schema_id/elements/$element_id.xml")) {
            $element_object = new \stdClass();
            $element_object->id = ($element_id);
            $element_object->request_type_id = 'D';
  
            Message::saveElementToXml($schema_id, $element_object);
            $commit_msg = 'requestToDeleteElement_request';
        } else {
            Message::xmlElementDel($schema_id, $element_id);
            $commit_msg = 'requestToDeleteElement_deletion';
        }

        // Создаем бэкап для Ctrlz
        Utils::createCtrlzBackup();

        // Git commit
        GitWrapper::Commit($schema_id, "element_contrloller_$commit_msg");

        return response()->json(['message'=> 'ok'], 200);
    }
}
