@extends('user.layout')

@section('title', 'Список магазинов')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Список магазинов</h1>
                </div><!-- /.col -->
                <!--div class="col-sm-6" style="width: 30%">
                    <a href="{ route('store.create') }}" class="btn btn-success float-right">Добавить магазин</a>
                </div><-- /.col -->
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
                            <th style="width: 40%">Описание</th>
                            <th style="width: 15%">Город</th>
                            <th style="width: 15%">Телефон</th>
                            <th style="width: 10%">Количество товаров</th>
                            <!--th style="width: 30%">Управление</th-->

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $store)

                            <tr>
                                <td>
                                    {{$store->id}}
                                </td>
                                <td>
                                    @if($store->photo)
                                    <img height="100" width="100" src="{{asset('storage/' . $store->photo)}}">
                                    @endif
                                </td>
                                <td>
                                    {{$store->name}}
                                </td>
                                <td>
                                    {{$store->description}}
                                </td>
                                <td>
                                    {{$store->city}}
                                </td>
                                <td>
                                    {{$store->phone}}
                                </td>
                                <td>
                                    {{ $store->products_count }}
                                </td>

                                <td class="project-actions text-left">

                                    <!--a class="btn btn-info btn-sm" href=" route('store.edit', $store['id']) }}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <form action="route('store.destroy', $store->id ) }}" method="POST" style="display: inline-block">
                                        csrf
                                        method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                            <i class="fas fa-trash">
                                            </i>
                                            Delete
                                        </button>
                                    </form-->
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


