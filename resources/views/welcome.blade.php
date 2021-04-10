@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Инструкция по прохождению теста</div>

                <div class="panel-body">
                  <p>1. Нажмите кнопку "Начать тест".</p>
                  <p>2. Прочитайте статью и нажмите "Далее".</p>
                  <p>3. После прочтения всех статей система перейдет на страницу "Вопросы" .</p>
                  <p>4. Ответьте на все вопросы и нажмите кнопку "Закончить тест".</p>
                </div>
                <a href="/checkup-start"><button>Начать тест</button></a> 
            </div>
        </div>
    </div>
</div>
@endsection
