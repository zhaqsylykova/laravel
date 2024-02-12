@extends('layouts.admin_layout')

@section('title', 'Список городов')

@section('content')
    <div class="content-header">
        <div class="container" style="width: 60%">
            <a href="{{ route('city.create') }}" class="btn btn-success float-right">Добавить город</a>
        </div>
        <div class="container">
            <h2>
                Список городов</h2>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table">
                <thead>
                <tr>
                    <th style="width: 30%">Город</th>
                    <th style="width: 40%">Количество пользователей</th>
                    <th style="width: 30%">Управление</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cities as $city)
                    <tr>
                        <td>{{ $city->city }}</td>
                        <td>{{ $cityUsersCount[$city->id] }}</td>
                        <td>
                            <a href="{{ route('city.edit', ['city' => $city]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt">
                                </i> Edit</a>
                            <form method="POST" action="{{ route('city.destroy', ['city' => $city]) }}" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash">
                                    </i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

