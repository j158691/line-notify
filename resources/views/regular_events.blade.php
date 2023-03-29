<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>定期寶貝</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/icon.png') }}" type="image/x-icon">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
@include('side_bar')
@yield('sidebar')
<div class="container">
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Regular</h1>
    <div class="memos">
        <table>
            <thead>
            <tr>
                <th>事件</th>
                <th>時間</th>
                <th>星期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($regular_events as $regular_event)
                <tr id="delete-{{ $regular_event['id'] }}">
                    <td>{{ $regular_event['event'] }}</td>
                    <td>{{ $regular_event['time'] }}</td>
                    <td>
                        @if($regular_event['sunday'] == 1) 日 @endif
                        @if($regular_event['monday'] == 1) 一 @endif
                        @if($regular_event['tuesday'] == 1) 二 @endif
                        @if($regular_event['wednesday'] == 1) 三 @endif
                        @if($regular_event['thursday'] == 1) 四 @endif
                        @if($regular_event['friday'] == 1) 五 @endif
                        @if($regular_event['saturday'] == 1) 六 @endif
                    </td>
                    <td>
                        <div class="btn-wrap">
                            @if ($regular_event['enabled'] == 1)
                                <button type="button" id="patch-{{ $regular_event['id'] }}" class="patch-btn disable-btn" value="{{ $regular_event['id'] }}">
                                    停用
                                </button>
                            @else
                                <button type="button" id="patch-{{ $regular_event['id'] }}" class="patch-btn enable-btn" value="{{ $regular_event['id'] }}">
                                    啟用
                                </button>
                            @endif
                            <button type="button" class="delete-btn" value="{{ $regular_event['id'] }}">刪除</button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="logout">
        <button type="submit" id="logout" style="width: 68px;">登出</button>
    </div>
    <div class="dog"></div>
    <div class="meliodas"></div>
    <div class="kon"></div>
</div>
<div class="alien"></div>

<script>
    const patchButtons = document.querySelectorAll('.patch-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    patchButtons.forEach(btn => {
        btn.addEventListener("click", function (event) {
            patchButtons.disabled = true;

            let regularEventId = event.target.value;
            let patchBtnElement = document.getElementById("patch-" + regularEventId);

            let params = {
                regular_event_id: regularEventId,
                _token: token
            };

            // console.table(params);

            patchData("{{ env('APP_URL') }}/regular-event", params)
                .then(data => {

                    if (data.status === 200) {
                        swal("寶貝" + data.message, "my man", "success");
                    } else if (data.status === 403) {
                        swal("改到別人的了啦", "哭", "warning");
                    } else {
                        swal("寶貝做不到qq", "", "error");
                    }

                    patchButtons.disabled = false;

                    if (patchBtnElement.innerText === "啟用") {
                        patchBtnElement.innerText = "停用";
                        patchBtnElement.className = "patch-btn disable-btn";
                    } else {
                        patchBtnElement.innerText = "啟用";
                        patchBtnElement.className = "patch-btn enable-btn";
                    }
                })
                .catch(error => {deleteButtons.disabled = false;})
        });
    });

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function (event) {
            deleteButtons.disabled = true;

            let regularEventId = event.target.value;
            let trElement = document.getElementById("delete-" + regularEventId);

            let params = {
                regular_event_id: regularEventId,
                _token: token
            };

            // console.table(params);

            deleteData("{{ env('APP_URL') }}/regular-event", params)
                .then(data => {

                    if (data.status === 200) {
                        swal("寶貝忘記惹", "蛤??", "success");
                    } else if (data.status === 403) {
                        swal("刪到別人的了啦", "哭", "warning");
                    } else {
                        swal("寶貝做不到qq", "", "error");
                    }

                    deleteButtons.disabled = false;
                    trElement.remove();
                })
                .catch(error => {deleteButtons.disabled = false;})
        });
    });

    const deleteData = (url, data) => {
        // Default options are marked with *
        return fetch(url, {
            body: JSON.stringify(data), // must match 'Content-Type' header
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, same-origin, *omit
            headers: {
                'user-agent': 'Mozilla/4.0 MDN Example',
                'content-type': 'application/json'
            },
            method: 'DELETE', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, cors, *same-origin
            redirect: 'follow', // manual, *follow, error
            referrer: 'no-referrer', // *client, no-referrer
        })
            .then(response => response.json()) // 輸出成 json
    }

    const patchData = (url, data) => {
        // Default options are marked with *
        return fetch(url, {
            body: JSON.stringify(data), // must match 'Content-Type' header
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, same-origin, *omit
            headers: {
                'user-agent': 'Mozilla/4.0 MDN Example',
                'content-type': 'application/json'
            },
            method: 'PATCH', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, cors, *same-origin
            redirect: 'follow', // manual, *follow, error
            referrer: 'no-referrer', // *client, no-referrer
        })
            .then(response => response.json()) // 輸出成 json
    }
</script>
</body>
</html>



