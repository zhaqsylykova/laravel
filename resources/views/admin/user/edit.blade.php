@extends('layouts.admin_layout')

@section('title', 'Редактирование')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактирование пользователя: {{ ($user['name']) }}</h1>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <!-- form start -->
                        <form action="{{ route('user.update', $user['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Имя</label>
                                    <input type="text" value="{{ $user['name'] }}" name="name" class="form-control"
                                           id="exampleInputEmail1" placeholder="Введите имя" required>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Почта</label>
                                    <input type="number" value="{{ $user['phone'] }}" name="phone" class="form-control"
                                           id="exampleInputEmail1" placeholder="Введите номер" required>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="city">Выберите город:</label>
                                    <select name="city" id="city" class="form-control" style="width: 100%;">
                                        <option value="">Выберите город</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->city }}" {{ $user->city === $city->city ? 'selected' : '' }}>
                                                {{ $city->city }}
                                            </option>
                                        @endforeach
                                    </select>
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
