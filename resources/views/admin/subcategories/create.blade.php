@extends('layouts.admin_layout')

@section('title', 'Создать пользователя')

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Создать</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('subcategory.store') }}">
                                @csrf

                                <!-- Ваши поля для создания пользователя, например: -->
                                <div class="form-group">
                                    <label for="name">Название:</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Выберите категорию:</label>
                                    <select name="category_id" id="category" class="form-control" style="width: 100%;">
                                        <option value="">Выберите</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Другие поля пользователя, такие как пароль, роль и т. д. -->

                                <button type="submit" class="btn btn-primary">Создать</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
