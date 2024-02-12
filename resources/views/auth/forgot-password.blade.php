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
        #email, #password {
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

    <form method="POST" action="{{ route('password.request') }}">
        <div class="card-body">
            @if (session('status'))
                <div class="flex gap-3 rounded-md border border-green-500 bg-green-50 p-4 mb-6">

                    <h3 class="text-sm font-medium text-green-800">{{ session('status') }}</h3>
                </div>
            @endif
            @csrf
            <div class="form-group row">
                <h2> Сброс пароля </h2>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="Email" ><i class='bx bxs-envelope' ></i><br><br>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                    @enderror
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary" >
                        {{ __('Отправить ссылку для сброса пароля') }}
                    </button><br><br>


                </div>
            </div>
        </div>
    </form>
    </body>
@endsection
</html>

