@extends('layouts.admin_layout')

@section('title', 'Создать город')

@section('content')

    <div class="container">
        <h1>Создать город</h1>

        <form action="{{ route('city.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="city">Название города:</label>
                <input type="text" name="city" id="city" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Создать город</button>
        </form>
    </div>
@endsection
