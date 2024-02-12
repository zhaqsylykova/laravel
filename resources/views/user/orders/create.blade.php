@extends('user.layout')

@section('title', 'Создать заказ')

@section('content')
    <div class="container">

        <h1>Создать заказ</h1>
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

        <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="product">Товар</label>
                <select name="product" id="product" class="form-control" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Количество товара</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="user">Клиент</label>
                <select name="user" id="user" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group">
                <label for="status">Статус заказа</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="В обработке">В обработке</option>
                    <option value="Доставляется">Доставляется</option>
                    <option value="Доставлен">Доставлен</option>
                </select>
            </div>


            <!-- Другие поля магазина -->
            <button type="submit" class="btn btn-primary">Создать товар</button>
        </form>
    </div>
@endsection
