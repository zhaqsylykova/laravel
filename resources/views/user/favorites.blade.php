@extends('user.layout')

@section('title', 'Избранные товары')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Избранные товары</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    @if ($favorites->isEmpty())
                        <p>No favorite items yet.</p>
                    @else
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
                            @foreach($favorites as $favorite)
                                <tr>
                                    <td>{{ $favorite->id }}</td>
                                    <td>
                                        @if($favorite->photo)
                                            <img height="100" width="100" src="{{ asset('storage/' . $favorite->photo) }}">
                                        @endif
                                    </td>
                                    <td>{{ $favorite->name }}</td>
                                    <td>{{ $favorite->description }}</td>
                                    <td>{{ $favorite->category->name }}</td>
                                    <td>
                                        @if ($favorite->subcategory)
                                            {{ $favorite->subcategory->name }}
                                        @else
                                            Нет подкатегории
                                        @endif
                                    </td>
                                    <td>{{ $favorite->price }}</td>
                                    <td>{{ $favorite->store->name }}</td>
                                    <td class="project-actions text-left">
                                        <a class="btn btn-info btn-sm" href="{{ route('user.product.buy', $favorite->id) }}">
                                            Купить
                                        </a>
                                    </td>
                                    <td>

                                        <form method="POST" action="{{ route('user.product.add.to.favorites', $favorite->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm ml-1">
                                                @if (auth()->user()->favorites->contains($favorite->id))
                                                    <i class="fas fa-heart"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
