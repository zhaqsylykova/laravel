@extends('layouts.app')
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
        h2 {
            text-align: center;
        }
        p{
            text-align: center;
        }
        #phone, #password, #name, #password-confirm{
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
@section('content')
    <body>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group row">
                    <h2>&nbsp;Регистрация</h2>
                    <div class="col-md-6">
                        <input placeholder="Имя" id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <i class='bx bxs-user' ></i><br><br>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <input placeholder='Phone' id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                            <i class='bx bxs-phone'></i><br><br>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-3">

                    <div class="col-md-6">
                        <input placeholder='Пароль' id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <i class='bx bxs-lock-alt' ></i><br><br>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input placeholder='Подтвердите пароль' id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <i class='bx bxs-lock-alt' ></i><br><br>
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button><br><br>
                        @if (Route::has('login'))
                            <p>Уже зарегистрированы?
                                <a class="btn btn-link custom-link-button" href="{{ route('login') }}">
                                    {{ __('Войдите') }}
                                </a></p>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </body>



@endsection
