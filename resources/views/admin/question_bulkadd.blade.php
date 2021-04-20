@extends('admin.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Добавить вопросы</h4></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/question-bulkadd') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                        <label class="col-md-4 control-label">Статья</label>
                            <div class="col-md-12">
                                <select name = "article_id">
                                @foreach ($articles as $article)
                                    <option value="{{{$article->id}}}">{{{$article->name}}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Вопросы</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="questions" cols="150" rows="10"></textarea>
                            </div>
                        </div>

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
