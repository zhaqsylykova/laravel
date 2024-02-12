@extends('layouts.app')

@section('content')
    <html>
    <head>
        <link rel="stylesheet" href="css.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            .custom-link-button{
                color: blue;
                cursor: pointer;
                text-decoration: none;
            }
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            form {
                width: 300px;
                padding: 32px;
                box-shadow: 0 4px 16px #ccc;
            }
            label {
                text-align: center;
            }
            #email, #password {
                padding: 9px;
                width: 100%;
            }
            button {
                padding: 7px 15px;
                float: right;
                color: white;
                background-color: blue;
                border: none;
                border-radius: 3px;
                cursor: pointer;
            }
            i {
                position: absolute;
                font-size: 20px;
                color: grey;
                left:880px;
                margin-top: 8px;
            }

        </style>
    </head>
    <body>
    <h1>Сброс пароля</h1>
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email">Ваш E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
            <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit">Отправить ссылку для сброса пароля</button>
        </div>
    </form>
    </body>
    </html>
@endsection
