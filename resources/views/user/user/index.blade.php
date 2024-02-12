@extends('user.layout')

@section('title', 'Список пользователей')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Список пользователей</h1>
                </div><!-- /.col -->
                <div class="col-sm-6" style="width: 30%">
                    <a href="{{ route('user.create') }}" class="btn btn-success float-right">Создать пользователя</a>
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
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 35%">
                                Имя
                            </th>
                            <th style="width: 20%">
                                Почта
                            </th>
                            <th style="width: 20%">
                                Город
                            </th>
                            <th>
                                Управление
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)

                            <tr>
                                <td>
                                    {{$user->id}}
                                </td>
                                <td>
                                    {{$user->name}}
                                </td>
                                <td>
                                    {{$user->phone}}
                                </td>
                                <td>
                                    {{$user->city}}
                                </td>

                                <td class="project-actions text-left">

                                    <a class="btn btn-info btn-sm" href=" {{ route('user.edit', $user->id  )}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Edit
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id ) }}" method="POST" style="display: inline-block">
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
