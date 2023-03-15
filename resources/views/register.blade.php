<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>註冊寶貝</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/icon.png') }}" type="image/x-icon">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container">
    <button class="back" type="button" id="back" onclick="history.back()">Back</button>
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Register</h1>
    <div class="form">
        @csrf
        <label for="account">Username</label>
        <input type="text" id="account" name="account" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" id="register">Register</button>
    </div>
</div>

<script>
    const accountElement = document.getElementById('account');
    const passwordElement = document.getElementById('password');
    const registerElement = document.getElementById('register');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    registerElement.addEventListener("click", function () {
        registerElement.disabled = true;

        params = {
            account: accountElement.value,
            password: passwordElement.value,
            _token: token
        };

        console.table(params);

        postData('{{ env('APP_URL') }}/register', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                //     swal("", "", "success").then(value => {
                        window.location.href = '{{ env('APP_URL') }}/authorize';
                //     });
                } else {
                    swal("寶貝做不到qq", data.message.account[0], "error");
                    registerElement.disabled = false;
                }
            })
            .catch(error => {registerElement.disabled = false;})
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



