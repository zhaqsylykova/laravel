@extends('store.layout')

@section('title', 'Редактирование')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактирование баннера: {{ $banner->name }}</h1>
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
                        <form action="{{ route('banner.update', $banner['id']) }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="caption">Описание</label>
                                    <textarea name="caption" class="form-control"
                                              id="caption" placeholder="Описание" required>{{ old('caption', $banner->caption) }}</textarea>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="store">Выберите магазин:</label>
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
                                    @if ($banner->photo)
                                        <img src="{{ asset('storage/' . $banner->photo) }}" alt="Предыдущее фото">
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
