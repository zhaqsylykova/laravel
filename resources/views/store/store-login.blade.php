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
        label {
            text-align: center;
        }
        #phone, #password {
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
        <form method="POST" action="{{ url('/store/login')  }}">
            @csrf
            <div class="card-body">
                @if (session('status'))
                    <div class="flex gap-3 rounded-md border border-green-500 bg-green-50 p-4 mb-6">
                        <h3 class="text-sm font-medium text-green-800">{{ session('status') }}</h3>
                    </div>
                @endif
                <div class="form-group row">
                    <label>&nbsp;   Войдите в систему, чтобы начать сессию</label><br><br>

                    <div class="col-md-6">
                        <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus
                               placeholder="Phone" ><i class='bx bxs-phone'></i><br><br>

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-6">
                        <input id="password" type="password" placeholder="Пароль" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"
                        ><i class='bx bxs-lock-alt' ></i><br><br>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember"><strong>
                                {{ __('Запомнить меня') }}</strong><br><br>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary" >
                            {{ __('Войти') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Забыли пароль?') }}
                            </a>
                        @endif

                        @if (Route::has('store.register'))
                            <p>Если Вы не зарегистрированы,
                            <a class="btn btn-link custom-link-button" href="{{ route('store.register') }}">
                                {{ __('нажмите сюда') }}
                            </a></p>
                        @endif

                        <a href="{{ route('login') }}" class="btn btn-link custom-link-button">
                            {{ __('Войти как пользователь') }}
                        </a>


                    </div>
                </div>
            </div>
        </form>
    </body>
@endsection
</html>
