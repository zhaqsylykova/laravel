@extends('store.layout')


@section('content')
    <div class="container">
        <h1>Список баннеров</h1>
        <a href="{{ route('banner.create') }}" class="btn btn-primary">Создать баннер</a>

        @if ($banners && count($banners) > 0)
            <div class="row">
                @foreach ($banners as $banner)
                    <div class="col-md-4 mt-4">
                        <div class="card">
                            @if ($banner->store)

                            <a href="{{ route( 'store.edit', $banner-> store) }}">

                                @if ($banner->photo)
                                    <img height="350" width="100" src="{{ asset('storage/' . $banner->photo) }}" class="card-img-top">
                                @endif

                            </a>


                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $banner->caption }}</h5>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('banner.edit', $banner->id) }}" class="btn btn-primary">Редактировать</a>
                                <form action="{{ route('banner.destroy', $banner->id) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот баннер?')">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Баннеры отсутствуют.</p>
        @endif
    </div>
@endsection
