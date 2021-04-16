@extends('admin.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Добавить ответы на вопрос  "{{$question->name}}"</h4></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/answer-bulkadd') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="question_id" value="{{$question->id}}">

                        @for ($i = 0; $i < 5; $i++) 
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Ответ</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="answer_name{{$i}}" cols="150" rows="2"></textarea>
                            </div>
                        </div>
                        @endfor

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
