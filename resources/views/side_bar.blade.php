{{--@extends('layouts.app')--}}

@section('sidebar')
    <div class="sidebar">
        <div class="sidebar-wrapper">
            <h2 class="sidebar-text">單次提醒</h2>
            <ul>
                <li><a href="{{ env('APP_URL') }}/memo">建立提醒</a></li>
                <li><a href="{{ env('APP_URL') }}/memos">提醒紀錄</a></li>
            </ul>
            <h2 class="sidebar-text">重複提醒</h2>
            <ul>
                <li><a href="{{ env('APP_URL') }}/regular-event">建立定期</a></li>
                <li><a href="{{ env('APP_URL') }}/regular-events">定期紀錄</a></li>
            </ul>
        </div>
    </div>
@endsection
