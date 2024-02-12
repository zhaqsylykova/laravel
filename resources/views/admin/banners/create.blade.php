@extends('layouts.admin_layout')

@section('content')
    <div class="container">
        <h1>Создать баннер</h1>

        <form method="POST" action="{{ route('banner.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="caption">Текст баннера</label>
                <input type="caption" name="caption" id="caption" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="store">Магазин</label>
                <select name="store" id="store" class="form-control" required>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="photo">Фото</label>
                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection
