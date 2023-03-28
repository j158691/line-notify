{{--@extends('layouts.app')--}}

@section('sidebar')
    <div class="sidebar">
        <h3 class="neon" style="text-align: center">單次提醒</h3>
        <ul>
            <li><a href="{{ env('APP_URL') }}/memo">建立提醒</a></li>
            <li><a href="{{ env('APP_URL') }}/memos">提醒紀錄</a></li>
        </ul>
        <h3 class="neon" style="text-align: center">重複提醒</h3>
        <ul>
            <li><a href="{{ env('APP_URL') }}/regular-event">建立定期</a></li>
            <li><a href="{{ env('APP_URL') }}/regular-events">定期紀錄</a></li>
        </ul>
    </div>
@endsection
