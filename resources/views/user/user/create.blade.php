@extends('user.layout')

@section('title', 'Создать пользователя')

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Создать пользователя</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.store') }}">
                                @csrf

                                <!-- Ваши поля для создания пользователя, например: -->
                                <div class="form-group">
                                    <label for="name">Имя:</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="number" name="phone" id="phone" class="form-control" required>
                                </div>

                                <!-- Другие поля пользователя, такие как пароль, роль и т. д. -->

                                <button type="submit" class="btn btn-primary">Создать пользователя</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
