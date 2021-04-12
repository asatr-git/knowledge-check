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
            @if ($next_article_id > 0)
                <a href="/psw/{{$psw}}/article/{{$next_article_id}}"><button>Далее</button></a> 
            @else
                <a href="/question/psw/{{$psw}}"><button>Перейти к вопросам</button></a> 
            @endif
        </div>
    </div>

</div>
@endsection
