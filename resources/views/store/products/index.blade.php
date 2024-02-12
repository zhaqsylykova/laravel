@extends('store.layout')

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
                    <a href="{{ route('store.product.create') }}" class="btn btn-success float-right">Добавить товар</a>
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
                            <th style="width: 15%">Общее количество</th>
                            <th style="width: 15%">Доступное количество</th>

                            <th style="width: 30%">Управление</th>

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
                                    {{$product->total_quantity}}
                                </td>

                                <td>
                                    {{$product->available_quantity}}
                                </td>


                                <td class="project-actions text-left">

                                    <a class="btn btn-info btn-sm" href=" {{ route('store.product.edit', $product->id) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <form action=" {{ route('store.product.destroy', $product->id  )}}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                            <i class="fas fa-trash">
                                            </i>
                                            Delete
                                        </button>
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


