<?php
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
    <h1>Обновление пароля</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email">Ваш E-mail</label>
            <input type="email" name="email" id="email" value="{{ $email }}" required>
            @error('email')
            <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">Новый пароль</label>
            <input type="password" name="password" id="password" required>
            @error('password')
            <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Подтвердите пароль</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            @error('password_confirmation')
            <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit">Обновить пароль</button>
        </div>
    </form>
    </body>
    </html>
@endsection
