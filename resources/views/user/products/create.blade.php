@extends('layouts.admin_layout')


@section('title', 'Создать товар')

@section('content')
    <div class="container">

        <h1>Создать товар</h1>
        <div class="card">
            @if(count($errors))
                <div>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>

                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="name">Название товара:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <!-- Другие поля для создания магазина -->

            <div class="form-group">
                <label for="description"> Описание: </label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
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

            <div class="form-group">
                <label for="subcategory_id">Выберите подкатегорию:</label>
                <select name="subcategory_id" id="subcategory" class="form-control">
                    <option value="">Выберите</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Цена:</label>
                <input type="text" name="price" id="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="store_id">Выберите магазин:</label>
                <select name="store_id" id="store" class="form-control" style="width: 100%;">
                    <option value="">Выберите</option> <!-- Добавьте начальную опцию -->
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ $store->name === $store->name ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="photo">Фото:</label>
                <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" required>
            </div>


            <!-- Другие поля магазина -->
            <button type="submit" class="btn btn-primary">Создать товар</button>
        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#category').on('change', function(e) {
                console.log(e);

                var cat_id = e.target.value;

                $.get('/ajax-subcat?cat_id=' + cat_id, function(data){
                    $('#subcategory').empty();
                    $.each(data, function (index, subcatObj){
                        $(' #subcategory').append('<option value="'+subcatObj.id+'"> '+subcatObj.name+' </option>')
                    }
                    )})})});

    </script>
@endsection
