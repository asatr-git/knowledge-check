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
                <div class="panel-heading">Инструкция по прохождению теста</div>

                <div class="panel-body">
                    <p>1. Нажмите кнопку "Начать тест".</p>
                    <p>2. Прочтите статью, ответьте на вопросы и нажмите "Далее".</p>
                </div>
            </div>
            @if ($user_name != '')
                <a href="/psw/{{$psw}}/article"><button>Начать тест</button></a> 
            @endif
        </div>
    </div>
</div>
@endsection
