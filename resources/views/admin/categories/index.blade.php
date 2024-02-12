@extends('layouts.admin_layout')

@section('title', 'Категорий')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Категории</h1>
                </div><!-- /.col -->
                <div class="col-sm-6" style="width: 30%">
                    <a href="{{ route('category.create') }}" class="btn btn-success float-right">Добавить категорию</a>
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
                            <th style="width: 5%">
                                #
                            </th>
                            <th style="width: 40%">
                                Фото
                            </th>
                            <th style="width: 40%">
                                Категории
                            </th>

                            <th style="width: 20%">
                                Управление
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)

                            <tr>
                                <td>
                                    {{$category->id}}
                                </td>
                                <td>
                                    @if($category->icon)
                                        <img height="100" width="100" src="{{asset('storage/' . $category->icon)}}">
                                    @endif
                                </td>
                                <td>
                                    {{$category->name}}
                                </td>


                                <td class="project-actions text-left">

                                    <a class="btn btn-info btn-sm" href=" {{ route('category.edit', $category->id  )}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <form action="{{ route('category.destroy', $category->id ) }}" method="POST" style="display: inline-block">
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
