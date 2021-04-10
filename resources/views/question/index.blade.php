@extends('layouts.app')
@section('content')
<div class="container">
    @foreach ($questions as $question)
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$question->name}}</div>
                <div class="panel-body">
                    @foreach ($question->answers as $answer)
                        <p>
                            <input type="radio" name="answer{{$question->id}}">                        
                            {{$answer->name}}
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<a style="margin-left: 50%;margin-right: 50%;" href="/checkup-result"><button>Закончить тест</button></a> 
@endsection

