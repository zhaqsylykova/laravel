@extends('store.layout')

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
                        <form action="{{ route('store.order.update', $order->id) }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Товар:</label>
                                    <input type="text" value="{{ old('name', $order->product) }}" name="name" class="form-control"
                                           id="name" placeholder="Введите название" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Количество товара</label>
                                    <input type="number" value="{{ old('quantity', $order->quantity) }}" name="quantity" id="quantity" class="form-control" required readonly>
                                </div>

                                <!--div class="form-group">
                                    <label for="order_total">Цена</label>
                                    <input type="text" value="{ old('price', $order->product ? $order->product->price : '') }}" name="price" class="form-control" required readonly>
                                </div-->

                                <div class="form-group">
                                    <label for="order_total">Сумма</label>
                                    <input type="number" value="{{ old('order_total', $order->order_total) }}" name="order_total"  class="form-control" required readonly>
                                </div>





                                <div class="form-group">
                                    <label for="user">Клиент</label>
                                    <select name="user" id="user" class="form-control" required readonly>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id == $order->user_id ? 'selected' : '' }} disabled>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="status">Статус заказа</label>
                                    <select name="status" id="status" class="form-control"  value="{{ old('status', $order->status) }}" required>
                                        <option value="В обработке" {{ $order->status === 'В обработке' ? 'selected' : '' }}>В обработке</option>
                                        <option value="Доставляется" {{ $order->status === 'Доставляется' ? 'selected' : '' }}>Доставляется</option>
                                        <option value="Доставлен" {{ $order->status === 'Доставлен' ? 'selected' : '' }}>Доставлен</option>
                                    </select>
                                </div>
                            </div>

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



