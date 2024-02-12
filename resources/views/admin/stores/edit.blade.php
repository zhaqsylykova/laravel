@extends('layouts.admin_layout')

@section('title', 'Редактирование')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактирование магазина: {{ $store->name }}</h1>
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
                        <form action="{{ route('store.update', $store['id']) }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Название магазина:</label>
                                    <input type="text" value="{{ $store->name }}" name="name" class="form-control"
                                           id="name" placeholder="Введите название" required>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <textarea name="description"  rows="4" class="form-control"
                                              id="description" placeholder="Описание" required>{{ old('description', $store->description) }}</textarea>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="photo">Фото</label>
                                    @if ($store->photo)
                                        <img src="{{ asset('storage/' . $store->photo) }}" alt="Предыдущее фото">
                                    @endif
                                    <input type="file" name="photo" class="form-control-file" >
                                    @if ($errors->has('photo'))
                                        <div class="text-danger">{{ $errors->first('photo') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="phone">Номер телефона:</label>
                                    <input type="text" value="{{ $store->phone }}" name="phone" class="form-control"
                                           id="phone" placeholder="Введите номер телефона" required>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="city">Выберите город:</label>
                                    <select name="city" id="city" class="form-control" style="width: 100%;">
                                        <option value="">Выберите город</option> <!-- Добавьте начальную опцию -->
                                        @foreach($cities as $cityOption)
                                            <option value="{{ $cityOption->city }}" {{ $cityOption->city === $store->city ? 'selected' : '' }}>
                                                {{ $cityOption->city }}
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



