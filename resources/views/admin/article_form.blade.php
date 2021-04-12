@extends('admin.main')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>{{$article[0]->name}}</h1></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('admin/article-body') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$article[0]->id}}">

                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="summernote" name="body" id="" cols="130" rows="10">{{$article[0]->body}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Save
                                </button>
                                <a href="/admin/article">
                                    <button class="btn btn-primary"><i class="fa fa-btn fa-sign-in"></i>Cancel</button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
    height: 350,
  });  
});
</script>
@endsection
