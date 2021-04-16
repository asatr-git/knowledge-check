@extends('admin.main')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Ответы к вопросу {{$question[0]->name}}</h1></div>
                <a href="/admin/answer-bulkadd/{{$question[0]->id}}">Создать ответы</a>
                <div class="panel-body">
                    <div id="jsGrid"></div>
                </div>

                <div class="col-md-2 col-md-offset-4">
                    <a href="/admin/question">
                        <br><button class="btn btn-primary"><i class="fa fa-btn fa-sign-in"></i>Back</button>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- bootstrap js (required) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<script>

      $("#jsGrid").jsGrid({
          width: "100%",
        //   height: "400px",
        //   filtering: true,
          autoload: true,
          inserting: true,
          editing: true,
          sorting: true,
          paging: true,
          pageSize: 50,
          deleteConfirm: "Удалить вопрос?",    
          
            // onItemInserted: function(args) {
            //     $("#grid").jsGrid("loadData");
            //     alert('onItemInserted');
            // },          
          controller: {
              loadData: function(filter) {
                  return $.ajax({
                      type: "GET",
                      url: "/admin/answer-getList/{{$question[0]->id}}",
                      dataType: "json",
                      data: filter
                  });
              },
            insertItem: function(item) {
                item.question_id = '{{$question[0]->id}}';
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                    $.ajax({
                        type: "POST",
                        url: "/admin/answer",
                        data: item
                    }).done(function(updatedItemReturnedFromServer) {
                        location.reload();
                    });
            },
            updateItem: function(item) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                // console.log(item.id);
                  return $.ajax({
                      type: "post",
                      url: "/admin/answer",
                      data: item
                  });
              },
              deleteItem: function(item) {
                  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                  return $.ajax({
                      type: "DELETE",
                      url: "/admin/answer",
                      data: item
                  });
              }
          },
          fields: [
              { name: "id", title: "Id", type: "number", width: 1, readOnly: true, sorting: false, filtering: false},
              { name: "name", title: "Название", type: "text", width: 100},
              { name: "sort_order", title: "Сортировка", type: "number", width: 1, filtering: false},
              { name: "is_right", title: "Верный", type: "checkbox", width: 20, sorting: false, filtering: false},
              { type: "control", width: 10, editButton: false, 
            }
        ]
      });
      
    
</script>
  
@endsection