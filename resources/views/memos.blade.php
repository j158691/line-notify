<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>提醒寶貝</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/icon.png') }}" type="image/x-icon">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
@include('side_bar')
@yield('sidebar')
<div class="container">
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Memos</h1>
    <div class="memos">
        <table>
            <thead>
            <tr>
                <th>事件</th>
                <th>時間</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($memos as $memo)
                <tr id="delete-{{ $memo['id'] }}">
                    <td>{{ $memo['event'] }}</td>
                    <td>{{ $memo['notify_time'] }}</td>
                    <td>
                        <button type="button" class="delete-btn" value="{{ $memo['id'] }}">刪除</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="dog"></div>
    <div class="meliodas"></div>
    <div class="kon"></div>
</div>
<div class="alien"></div>

<script>
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function (event) {
            deleteButtons.disabled = true;

            let memoId = event.target.value;
            const trElement = document.getElementById("delete-" + memoId);

            let params = {
                memo_id: memoId,
                _token: token
            };

            // console.table(params);

            deleteData("{{ env('APP_URL') }}/memo", params)
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
</script>
</body>
</html>



