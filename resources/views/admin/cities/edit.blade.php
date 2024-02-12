@extends('layouts.admin_layout')

@section('title', 'Редактировать город')

@section('content')
    <div class="container">
        <h1>Редактировать город</h1>

        <form method="POST" action="{{ route('city.update', $city['id']) }}">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Название города:</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ $city->city }}" placeholder="Введите название города" required>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endsection
