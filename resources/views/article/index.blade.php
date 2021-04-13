@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h4>{{$user_name}}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{!! $article[0]->name !!}</div>

                <div class="panel-body">
                  <p>{!! $article[0]->body !!}</p>
                </div>
            </div>
        </div>
    </div>

<div class="col-md-10 col-md-offset-1"><h4>Ответьте на вопросы к статье и нажмите "Далее"</h4></div>

    <form id="questions_from" method="POST" action="/checkanswers">
        <input type="hidden" name="psw" value={{$psw}}>
        <input type="hidden" name="article_id" value={{$article[0]->id}}>
        @foreach ($questions as $question)
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$question->name}}</div>
                    <div class="panel-body">
                        @foreach ($question->answers as $answer)
                            <p>
                                <input type="radio" checked name="{{$question->id}}" value="{{$answer->id}}">                        
                                {{$answer->name}}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </form>
</div>
<button style="margin-left: 50%;margin-right: 50%;" onclick="checkanswers();">Далее</button>
@endsection

@section('jsscripts')
<script>
function checkanswers(){
    var form = $("#questions_from");
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}) 
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        dataType: "json", 
    }).done(function(data) {
        if (data.length == 0) {
            if ("{{$next_article_id}}" == 0) {
                next_url = "/checkup-result";
            } else {
                next_url = "/psw/{{$psw}}/article/{{$next_article_id}}";
            }
            alert("Вы ответили правильно")
            location.href = next_url;
        }else{
            msg = 'Вы неверно ответили на следующие вопросы:\n'
            data.forEach(function(item, i, arr) {
                msg = msg + (i+1) + ". " + item+ "\n";
            });        
            alert(msg);
        }
    });
}
</script>
@endsection
