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
<div class="container">
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Memo</h1>
    <span>寶貝會提前一小時跟你講</span>
    <div class="form">
        @csrf
        <label for="event">事件</label>
        <input type="text" id="event" name="event" required>
        <label for="dateTime">時間</label>
        <input type="datetime-local" id="dateTime" name="dateTime" required>
        <button type="submit" id="store">儲存</button>
        <div class="logout">
            <button type="submit" id="logout" style="width: 68px;">登出</button>
        </div>
    </div>
    <div class="dog"></div>
    <div class="meliodas"></div>
    <div class="kon"></div>
</div>

<script>
    const eventElement = document.getElementById('event');
    const dateTimeElement = document.getElementById('dateTime');
    const storeElement = document.getElementById('store');
    const logoutElement = document.getElementById('logout');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    storeElement.addEventListener("click", function () {
        storeElement.disabled = true;

        if (!dateTimeElement.value || !eventElement.value) {
            swal("為什麼你就是不愛填欄位", '壞ˋˊ', "warning");
            registerElement.disabled = false;
            return;
        }

        params = {
            notify_time: dateTimeElement.value,
            event: eventElement.value,
            _token: token
        };

        console.table(params);

        postData('{{ env('APP_URL') }}/memo', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                    swal("寶貝記得嚕", "", "success");
                } else {
                    swal("寶貝做不到qq", "", "error");
                }

                storeElement.disabled = false;
            })
            .catch(error => {storeElement.disabled = false;})
    });

    logoutElement.addEventListener("click", function () {
        logoutElement.disabled = true;

        params = {
            _token: token
        };

        console.table(params);

        postData('{{ env('APP_URL') }}/logout', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                    // swal("", "", "success");
                    window.location.href = '{{ env('APP_URL') }}/login';
                } else {
                    swal("寶貝做不到qq", "", "error");
                }

                logoutElement.disabled = false;
            })
            .catch(error => {storeElement.disabled = false;})
    });

    const postData = (url, data) => {
        // Default options are marked with *
        return fetch(url, {
            body: JSON.stringify(data), // must match 'Content-Type' header
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, same-origin, *omit
            headers: {
                'user-agent': 'Mozilla/4.0 MDN Example',
                'content-type': 'application/json'
            },
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, cors, *same-origin
            redirect: 'follow', // manual, *follow, error
            referrer: 'no-referrer', // *client, no-referrer
        })
            .then(response => response.json()) // 輸出成 json
    }
</script>
</body>
</html>



