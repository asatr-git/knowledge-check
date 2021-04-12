@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h4>{{$user_name}}</h4>
        </div>
    </div>
    <form id="questions_from" method="POST" action="/checkanswers">
        <input type="hidden" name="psw" value={{$psw}}>
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
<button style="margin-left: 50%;margin-right: 50%;" onclick="checkanswers();">Закончить тест</button>
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
            location.href = "/checkup-result"
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
