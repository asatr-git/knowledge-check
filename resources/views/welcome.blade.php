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
                    <p>Администратор - login: admin@admin.com password: 123456</p>
                    <p>Вход в админку  - пункт меню "Admin page"</p>
                    <p>Ссылки на тесты - в админке.</p>
                    <p>Пример теста: <a href="http://knowledge-check/psw/60731cef3cc2d">Пройти тест</a></p>

                    <p>1. Нажмите кнопку "Начать тест".</p>
                    <p>2. Прочитайте статью и нажмите "Далее".</p>
                    <p>3. После прочтения всех статей система перейдет на страницу "Вопросы" .</p>
                    <p>4. Ответьте на все вопросы и нажмите кнопку "Закончить тест".</p>
                </div>
            </div>
            @if ($user_name != '')
                <a href="/psw/{{$psw}}/article/0"><button>Начать тест</button></a> 
            @endif
        </div>
    </div>
</div>
@endsection
