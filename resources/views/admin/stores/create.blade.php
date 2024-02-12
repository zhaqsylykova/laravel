@extends('layouts.admin_layout')

@section('title', 'Создать магазин')

@section('content')
    <div class="container">
        <h1>Создать магазин</h1>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('store.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Название магазина:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <!-- Другие поля для создания магазина -->

            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="photo">Фото:</label>
                <input type="file" name="photo" id="photo" class="form-control-file" required>
            </div>

            <div class="form-group">
                <label for="phone">Номер телефона:</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="city">Выберите город:</label>
                <select name="city" id="city" class="form-control" style="width: 100%;">
                    <option value="">Выберите город</option> <!-- Добавьте начальную опцию -->
                    @foreach($cities as $city)
                        <option value="{{ $city->city }}" {{ $city->city === $city->city ? 'selected' : '' }}>
                            {{ $city->city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Другие поля магазина -->
            <button type="submit" class="btn btn-primary">Создать магазин</button>
        </form>
    </div>
@endsection
