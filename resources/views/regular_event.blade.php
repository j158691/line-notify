<!DOCTYPE html>
<html lang="en">
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
<div class="container" style="margin-left: 110px">
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Regular</h1>
    <strong class="neon">沒有寶貝怎麼行 只有寶貝不會停</strong>
    <div class="form">
        @csrf
        <label for="event">事件</label>
        <input type="text" id="event" name="event">
        <label for="dateTime">時間</label>
        <input type="time" id="dateTime" name="dateTime">

        <div class="checkbox-group">
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="0">
                <span class="checkbox-custom"></span>
                週日
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="1">
                <span class="checkbox-custom"></span>
                週一
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="2">
                <span class="checkbox-custom"></span>
                週二
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="3">
                <span class="checkbox-custom"></span>
                週三
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="4">
                <span class="checkbox-custom"></span>
                週四
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="5">
                <span class="checkbox-custom"></span>
                週五
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="weekday" value="6">
                <span class="checkbox-custom"></span>
                週六
            </label>
        </div>

        <button type="submit" id="store">儲存</button>
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
    const eventElement = document.getElementById('event');
    const dateTimeElement = document.getElementById('dateTime');
    const checkboxes = document.querySelectorAll('input[name="weekday"]');
    const storeElement = document.getElementById('store');
    const logoutElement = document.getElementById('logout');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const weekdays = [];

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                weekdays.push(checkbox.value);
            } else {
                const index = weekdays.indexOf(checkbox.value);
                if (index > -1) {
                    weekdays.splice(index, 1);
                }
            }
            console.log(weekdays);
        });
    });

    storeElement.addEventListener("click", async function () {
        storeElement.disabled = true;

        if (!dateTimeElement.value || !eventElement.value) {
            swal("為什麼你就是不愛填欄位", '壞ˋˊ', "warning");
            storeElement.disabled = false;
            return;
        }

        params = {
            time: dateTimeElement.value,
            event: eventElement.value,
            weekdays: weekdays,
            _token: token
        };

        console.table(params);

        postData("{{ env('APP_URL') }}/regular-event", params)
            .then(data => {

                if (data.status === 200) {
                    swal("寶貝記得嚕", "", "success");
                } else {
                    swal("寶貝做不到qq", "", "error");
                }

                storeElement.disabled = false;
            })
            .catch(error => {storeElement.disabled = false;});

        eventElement.value = '';
        dateTimeElement.value = '';

        // 將所有的 checkbox 重新設定為未勾選
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
            checkbox.parentNode.classList.remove('is-checked');
        });

        // 清空存放已勾選 checkbox 的陣列
        weekdays.length = 0;
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
    };
</script>
</body>
</html>



