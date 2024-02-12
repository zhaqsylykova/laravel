@extends('layouts.admin_layout')

@section('title', 'Список заказов')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Список заказов</h1>
                </div><!-- /.col -->
                <div class="col-sm-6" style="width: 30%">
                    <a href="{{ route('order.create') }}" class="btn btn-success float-right">Добавить заказ</a>
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
                            <th style="width: 10%">ID</th>
                            <th style="width: 20%">Товар</th>
                            <th style="width: 5%">Количество</th>
                            <th style="width: 15%">Сумма</th>
                            <th style="width: 15%">Клиент</th>
                            <th style="width: 15%">Магазин</th>
                            <th style="width: 10%">Время заказа</th>
                            <th style="width: 10%">Статус</th>
                            <th style="width: 10%">Управление</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)

                            <tr>
                                <td>
                                    {{$order->order_number}}
                                </td>

                                <td>
                                    {{ $order->product }}
                                </td>
                                <td>
                                    {{$order->quantity}}
                                </td>
                                <td>
                                    {{$order->order_total}}
                                </td>
                                <td>
                                    {{ $order->user->name }}
                                </td>

                                <td>
                                    {{ $order->store->name }}
                                </td>
                                <td>
                                    {{$order->order_time}}
                                </td>
                                <td>
                                    {{$order->status}}
                                </td>

                                <td class="project-actions text-left">

                                    <!--a class="btn btn-info btn-sm" href="\ )}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a-->
                                    <form action=" {{ route('order.destroy', $order->id  )}}" method="POST" style="display: inline-block">
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


