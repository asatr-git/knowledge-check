@extends('admin.main')

@section('content')

<style>
.completed {
  color: green;
}</style>

<h5 style="margin-top: -30px;">Пользователи</h5>
  
<div id="jsGrid"></div>
    
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
          deleteConfirm: "Удалить пользователя?",    
          
          controller: {
              loadData: function(filter) {
                  return $.ajax({
                      type: "GET",
                      url: "/admin/user-getList",
                      dataType: "json",
                      data: filter
                  });
              },
              insertItem: function(item) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                $.ajax({
                    type: "POST",
                    url: "/admin/user",
                    data: item
                }).done(function(data) {
                    location.reload();
                });
              },
              updateItem: function(item) {
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                //console.log(item);
                  return $.ajax({
                      type: "post",
                      url: "/admin/user",
                      data: item
                  });
              },
              deleteItem: function(item) {
                  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                  return $.ajax({
                      type: "DELETE",
                      url: "/admin/user",
                      data: item
                  });
              }
          },
        // rowRenderer: function(item) {
        // var classStr = "";
        // if (item.is_completed == 1) {
        //     classStr = "completed";
        // }
        // var test = '<tr class="jsgrid-row ' + classStr + ' style="background:red !importent">' +
        // '<td class="jsgrid-cell" style="width: 150px;">' + item.name + '</td>' +
        // '<td class="jsgrid-cell" style="width: 100px;">' + item.email + '</td>' +
        // '<td class="jsgrid-cell" style="width: 100px;">' + item.link + '</td>' +
        // '<td class="jsgrid-cell" style="width: 100px;">' + item.last_activity + '</td>' +
        // '<td class="jsgrid-cell" style="width: 100px;">' + item.wrong_attempts + '</td>' +
        // '<td class="jsgrid-cell" style="width: 100px;">' + item.completed + '</td>' +
        // '</tr>';
        // return $(test)
        // },
          fields: [
              { name: "name", title: "Имя", type: "text", width: 5},
              { name: "email", title: "Email", type: "text", width: 20},
              { name: "link", title: "Ссылка", type: "text", width: 30, readOnly: true},
              { name: "last_activity", title: "Последняя активность", type: "text", width: 5, readOnly: true},
              { name: "wrong_attempts", title: "Неудачных попыток", type: "text", width: 5, readOnly: true},
              { name: "completed", title: "Пройдено статей", type: "text", width: 5, readOnly: true},
              { type: "control", width: 10,  editButton: false,}
        ]
      });
      
    
</script>
  
@endsection