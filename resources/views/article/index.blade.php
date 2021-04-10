@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$article[0]->name}}</div>

                <div class="panel-body">
                  <p>{{$article[0]->body}}</p>
                </div>
            </div>
            @if ($article[0]->sort_order < 1)
                <a href="/checkup-next"><button>Далее</button></a> 
            @else
                <a href="/question"><button>Перейти к вопросам</button></a> 
            @endif


        </div>
    </div>

</div>
@endsection
