<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>登入寶貝</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('image/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container">
    <img class="bondee" src="{{ asset('image/bondee.png') }}">
    <h1>Login</h1>
    <div class="form">
        @csrf
        <label for="account">Username</label>
        <input type="text" id="account" name="account" required>
        <label for="password">Password</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required>
            <i id="eye" class="fas fa-eye" onclick="showPassword()"></i>
        </div>
        <button type="submit" id="login">Login</button>
        <br>
        <button type="button" id="register" onclick="window.location.href='{{ env('APP_URL') }}/register'">Register</button>
    </div>
    <div class="dog"></div>
</div>

<script>
    const accountElement = document.getElementById('account');
    const passwordElement = document.getElementById('password');
    const loginElement = document.getElementById('login');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    loginElement.addEventListener("click", function () {
        params = {
            account: accountElement.value,
            password: passwordElement.value,
            _token: token
        };

        console.table(params);

        postData('{{ env('APP_URL') }}/login', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                    swal("寶貝早", "", "success").then(value => {
                        window.location.href = '{{ env('APP_URL') }}/memo';
                    });
                } else {
                    swal("寶貝做不到qq", "", "error");
                }
            })
            .catch(error => console.error(error))
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

    function showPassword() {
        let eye = document.getElementById("eye");
        let passwordInput = document.getElementById("password");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eye.className = "fas fa-eye-slash";
        } else {
            passwordInput.type = "password";
            eye.className = "fas fa-eye";
        }
    }
</script>
</body>
</html>



