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
        #email, #password ,#password-confirm {
            padding: 9px;
            width: 100%;
        }
        h2 {
            text-align: center;
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

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->token }}">
        <div class="card-body">
            <div class="form-group row">
                <h2> Сброс пароля </h2>


                <div class="row mb-3">

                    <div class="col-md-6">
                        <input placeholder='Email' id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email">
                        <i class='bx bxs-envelope' type='solid'></i><br><br>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
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

            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary" >
                        {{ __('Reset Password') }}
                    </button><br><br>


                </div>
            </div>
        </div>
    </form>
    </body>
@endsection
</html>
