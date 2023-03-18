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
    <strong class="neon">寶貝會提前一小時跟你講</strong>
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

    storeElement.addEventListener("click", async function () {
        storeElement.disabled = true;

        if (!dateTimeElement.value || !eventElement.value) {
            swal("為什麼你就是不愛填欄位", '壞ˋˊ', "warning");
            storeElement.disabled = false;
            return;
        }

        // 取得server time時間
        const serverTimeData = await getData("{{ env('APP_URL') }}/server-time");
        let serverTime = new Date(serverTimeData.message).toTimeString();
        let temp = new Date(serverTimeData.message);
        let checkTime = new Date(temp.setHours(temp.getHours() + 1)).toTimeString();
        let notifyTime = new Date(dateTimeElement.value).toTimeString();
        if (!(notifyTime >= checkTime)) {
            console.log(notifyTime);
            console.log(checkTime);
            swal("時間設定錯誤", "當前伺服器時間為" + serverTime + "\n你設定的時間為" + notifyTime + "\n需選擇伺服器時間加一小時以後的時間，辛苦我惹ˊˋ", "warning");
            storeElement.disabled = false;
            return;
        }

        params = {
            notify_time: dateTimeElement.value,
            event: eventElement.value,
            _token: token
        };

        console.table(params);

        postData("{{ env('APP_URL') }}/memo", params)
            .then(data => {

                if (data.status === 200) {
                    swal("寶貝記得嚕", "", "success");
                } else {
                    swal("寶貝做不到qq", "", "error");
                }

                storeElement.disabled = false;
            })
            .catch(error => {storeElement.disabled = false;})

        eventElement.value = '';
        dateTimeElement.value = '';
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

    const getData = async (url) => {
        try {
            const response = await fetch(url, {
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, same-origin, *omit
                headers: {
                    'user-agent': 'Mozilla/4.0 MDN Example',
                    'content-type': 'application/json'
                },
                method: 'GET', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, cors, *same-origin
                redirect: 'follow', // manual, *follow, error
                referrer: 'no-referrer', // *client, no-referrer
            });
            return await response.json(); // 輸出成 json
        } catch (error) {
            console.error(error);
        }
    };
</script>
</body>
</html>



