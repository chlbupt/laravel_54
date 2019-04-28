@extends('layout.main')
@section('content')
<div class="col-sm-8">
    <blockquote>
        <p><img src="/storage/9f0b0809fd136c389c20f949baae3957/iBkvipBCiX6cHitZSdTaXydpen5PBiul7yYCc88O.jpeg" alt="" class="img-rounded" style="border-radius:500px; height: 40px"> {{$user->name}}
        </p>
        <footer>关注：{{$user->stars_count}}｜粉丝：{{$user->fans_count}}｜文章：{{$user->posts_count}}</footer>
        @include('user.gadgets.like', ['target_user' => $user])
    </blockquote>
</div>
<div class="col-sm-8 blog-main">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">文章</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">关注</a></li>
            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">粉丝</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                @foreach($posts as $post)
                    {{--{{ dd($post) }}--}}
                <div class="blog-post" style="margin-top: 30px">
                    <p class=""><a href="/user/{{$post->user->id}}">{{$post->user->name}}</a> {{$post->created_at->diffForHumans()}}</p>
                    <p class=""><a href="/posts/{{$post->id}}" >{{$post->title}}</a></p>
                    <p>{!! $post->content !!}</p>
                </div>
                @endforeach
            </div>
            <div class="tab-pane" id="tab_2">
                @foreach($susers as $user)
                <div class="blog-post" style="margin-top: 30px">
                    <p class="">{{ $user->name }}.</p>
                    <p class="">关注：{{$user->stars_count}} | 粉丝：{{$user->fans_count}}｜ 文章：{{$user->post_count}}</p>
                    @include('user.gadgets.like', ['target_user' => $user])
                </div>
                @endforeach
            </div>
            <div class="tab-pane" id="tab_3">
                @foreach($fusers as $fuser)
                    <div class="blog-post" style="margin-top: 30px">
                        <p class="">{{ $fuser->name }}.</p>
                        <p class="">关注：{{$fuser->stars_count}} | 粉丝：{{$fuser->fans_count}}｜ 文章：{{$fuser->post_count}}</p>
                        @include('user.gadgets.like', ['target_user' => $fuser])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
