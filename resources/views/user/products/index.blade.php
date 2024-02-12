@extends('user.layout')

@section('title', 'Список товаров')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Список товаров</h1>
                </div><!-- /.col -->
                <div class="col-sm-6" style="width: 30%">
                    <!--a href="{ route('product.create') }}" class="btn btn-success float-right">Добавить товар</a-->
                </div><!-- /.col -->
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ session('success') }}</h4>
                </div>
            @endif
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th style="width: 10%">Фото</th>
                            <th style="width: 10%">Название</th>
                            <th style="width: 20%">Описание</th>
                            <th style="width: 15%">Категория</th>
                            <th style="width: 15%">Подкатегория</th>
                            <th style="width: 15%">Цена</th>
                            <th style="width: 10%">Магазин</th>
                            <th style="width: 30%"></th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)

                            <tr>
                                <td>
                                    {{$product->id}}
                                </td>
                                <td>
                                    @if($product->photo)
                                        <img height="100" width="100" src="{{asset('storage/' . $product->photo)}}">
                                    @endif
                                </td>
                                <td>
                                    {{$product->name}}
                                </td>
                                <td>
                                    {{$product->description}}
                                </td>
                                <td>
                                    {{$product->category->name}}
                                </td>
                                <td>
                                    @if ($product->subcategory)
                                        {{$product->subcategory->name}}
                                    @else
                                        Нет подкатегории
                                    @endif
                                </td>
                                <td>
                                    {{$product->price}}
                                </td>
                                <td>
                                    {{$product->store->name}}
                                </td>

                                <td class="project-actions text-left">

                                    <a class="btn btn-info btn-sm" href=" {{ route('user.product.buy', $product->id) }}">

                                        Купить
                                    </a>
                                </td>
                                <td>

                                    <form method="POST" action="{{ route('user.product.add.to.favorites', $product->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm ml-1">
                                            @if (auth()->user()->favorites->contains($product->id))
                                                <i class="fas fa-heart"></i>
                                            @else
                                                <i class="far fa-heart"></i>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('user.product.basket.add', $product->id) }}">
                                        @csrf
                                        <div class="input-group">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-cart-plus"></i> В корзину
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Main content -->

            <!-- Small boxes (Stat box) -->



            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->

@endsection


