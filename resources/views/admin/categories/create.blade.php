@extends('layouts.admin_layout')

@section('title', 'Создать категорию')

@section('content')
    <div class="container">
        <h1>Добавить категорию</h1>

        <form method="POST" action="{{ route('category.store') }}"  enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Название магазина:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <!-- Другие поля для создания магазина -->

            <div class="form-group">
                <label for="icon">Фото:</label>
                <input type="file" name="icon" id="icon" class="form-control-file">
            </div>



            <!-- Другие поля магазина -->
            <button type="submit" class="btn btn-primary">Создать категорию</button>
        </form>
    </div>
@endsection
