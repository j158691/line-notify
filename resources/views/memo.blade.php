<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>忘記了嗎</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container">
    <h1>備忘錄</h1>
    <div class="form">
        @csrf
        <label for="event">什麼事</label>
        <input type="text" id="event" name="event" required>
        <label for="dateTime">時間</label>
        <input type="datetime-local" id="dateTime" name="dateTime" required>
        <button type="submit" id="store">儲存</button>
    </div>
</div>

<script>
    const eventElement = document.getElementById('event');
    const dateTimeElement = document.getElementById('dateTime');
    const storeElement = document.getElementById('store');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    storeElement.addEventListener("click", function () {
        storeElement.disabled = true;

        params = {
            notify_time: dateTimeElement.value,
            event: eventElement.value,
            _token: token
        };

        console.table(params);

        postData('http://localhost:8000/memo', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                    swal("新增成功", "", "success");
                } else {
                    swal("失敗", "", "error");
                }

                storeElement.disabled = false;
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



