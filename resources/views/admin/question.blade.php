@extends('admin.main')

@section('content')

<h5 style="margin-top: -30px;">Вопросы</h5>
  
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
    $.ajax({
        type: "GET",
        url: "/admin/article-getAjaxList"
    }).done(function(articles) {
          articles = jQuery.parseJSON(articles); 

          $("#jsGrid").jsGrid({
            width: "100%",
          //   height: "400px",
            // filtering: true,
            autoload: true,
            inserting: true,
            editing: true,
            sorting: true,
            paging: true,
            pageSize: 50,
            deleteConfirm: "Удалить вопрос?",    
            
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "/admin/question-getList",
                        dataType: "json",
                        data: filter
                    });
                },
                insertItem: function(item) {
                  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                      $.ajax({
                          type: "POST",
                          url: "/admin/question",
                          data: item
                      }).done(function(updatedItemReturnedFromServer) {
                          location.reload();
                      });
                },
                updateItem: function(item) {
                  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                  //console.log(item);
                    return $.ajax({
                        type: "post",
                        url: "/admin/question",
                        data: item
                    });
                },
                deleteItem: function(item) {
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
                    return $.ajax({
                        type: "DELETE",
                        url: "/admin/question",
                        data: item
                    });
                }
            },
            fields: [
                { name: "id", title: "Id", type: "number", width: 20, readOnly: true},
                { name: "article_id", title: "Статья", type: "select", width: 100, items: articles, valueField: "id", textField: "name" },
                { name: "name", title: "Название", type: "text", width: 100},
                { name: "sort_order", title: "Сорт.", type: "number", width: 20},
                { type: "control", width: 10, editButton: false, 
                  itemTemplate: function(value, item) {
                      var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);

                      var $customEditButton = $("<button>").attr({class: "customGridEditbutton jsgrid-button jsgrid-edit-button"})
                        .click(function(e) {
                          // alert("ID: " + item.id);
                          document.location.href = "question/" + item.id;
                          //  document.location.href = item.id + "/edit";
                          e.stopPropagation();
                        });

                      // return $("<div>").append($customEditButton).append($customDeleteButton);
                      return $result.add($customEditButton);
                  },
              }
          ]
        });
    });
      
    
</script>
  
@endsection