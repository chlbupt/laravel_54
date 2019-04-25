@extends('layout.main')
@section('content')
    <div class="col-sm-8 blog-main">
        <form action="/posts/{{$post->id}}" method="POST">
            {{ method_field("PUT") }}
            {{ csrf_field() }}
            <div class="form-group">
                <label>标题</label>
                <input name="title" type="text" class="form-control" placeholder="这里是标题" value="{{$post->title}}">
            </div>
            <div class="form-group">
                <label>内容</label>
                {{--<textarea id="content" name="content" class="form-control" style="height:400px;max-height:500px;"  placeholder="这里是内容">{!! htmlspecialchars($post->content) !!}</textarea>--}}
                <script id="container" name="content" type="text/plain">
                    {!! $post->content !!}
                </script>
                @include('layout.error')
                <script type="text/javascript" src="/laravel-u-editor/ueditor.config.js"></script>
                <!-- 编辑器源码文件 -->
                <script type="text/javascript" src="/laravel-u-editor/ueditor.all.js"></script>
                <script type="text/javascript">
                    var ue = UE.getEditor('container',{
                        toolbars: [
                            ['fullscreen', 'source', 'undo', 'redo', 'time', 'date', 'map', 'emotion', 'insertvideo', 'attachment', 'wordimage', 'mergecells', 'simpleupload', 'insertimage'],
                            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
                        ]
                    });
                    ue.ready(function(){
                        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                    });
                </script>
            </div>
            <button type="submit" class="btn btn-default">提交</button>
        </form>
        <br>
    </div>
@endsection