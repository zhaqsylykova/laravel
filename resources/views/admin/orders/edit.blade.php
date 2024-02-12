@extends('layouts.admin_layout')

@section('title', 'Редактирование')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактирование заказа: {{ $order->name }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
                </div>
            @endif
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

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

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <!-- form start -->
                        <form action="{{ route('order.update', $order->id) }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Товар:</label>
                                    <input type="text" value="{{ $order->name }}" name="name" class="form-control"
                                           id="name" placeholder="Введите название" required>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <textarea name="description"  rows="4" class="form-control"
                                              id="description" placeholder="Описание" required>{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>



                            <div class="card-body">
                                <div class="form-group">
                                    <label for="phone">Цена:</label>
                                    <input type="text" name="price" id="price" class="form-control"
                                           value="{{ $product->price }}" required>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="city">Выберите магазин:</label>
                                    <select name="store" id="store" class="form-control" style="width: 100%;">
                                        <option value="">Выберите</option> <!-- Добавьте начальную опцию -->
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ $store->name === $store->name ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="photo">Фото</label>
                                    @if ($product->photo)
                                        <img src="{{ asset('storage/' . $product->photo) }}" alt="Предыдущее фото">
                                    @endif
                                    <input type="file" name="photo" class="form-control-file" >
                                    @if ($errors->has('photo'))
                                        <div class="text-danger">{{ $errors->first('photo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Обновить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->

@endsection



