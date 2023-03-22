{{--@extends('layouts.app')--}}

@section('sidebar')
    <div class="sidebar">
        <ul>
            <li><a href="{{ env('APP_URL') }}/memo">建立提醒</a></li>
            <li><a href="{{ env('APP_URL') }}/memos">瀏覽紀錄</a></li>
{{--            <li><a href="#">還沒</a></li>--}}
{{--            <li><a href="#">還沒</a></li>--}}
        </ul>
    </div>
@endsection
