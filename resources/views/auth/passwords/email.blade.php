@extends('layouts.app')
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
        #email, #password, #name, #password-confirm{
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
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="row mb-3">
                    <h2>Сбросить пароль</h2>

                    <div class="col-md-6">
                        <input placeholder='Email' id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <i class='bx bxs-envelope' type='solid'></i><br><br>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Отправить ссылку для сброса пароля') }}
                        </button><br><br>
                    </div>
                </div>
            </form>
        </div>
    </body>



@endsection
