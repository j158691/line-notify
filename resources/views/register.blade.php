<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Page</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container">
    <button type="button" id="back" onclick="history.back()">Back</button>
    <h1>Register</h1>
    <div class="form">
        @csrf
        <label for="account">Account</label>
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

        postData('http://localhost:8000/register', params)
            .then(data => {
                console.log(data);
                if (data.status === 200) {
                //     swal("登入", "", "success").then(value => {
                        window.location.href = 'http://localhost:8000/authorize';
                //     });
                } else {
                    swal("錯誤", data.message.account[0], "error");
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



