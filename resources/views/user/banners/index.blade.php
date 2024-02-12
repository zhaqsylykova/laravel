@extends('user.layout')


@section('content')
    <div class="container">
        <h1>Список баннеров</h1>
        <!--a href="{ route('banner.create') }}" class="btn btn-primary">Создать баннер</a-->

        @if (count($banners) > 0)
            <div class="row">
                @foreach ($banners as $banner)
                    <div class="col-md-4 mt-4">
                        <div class="card">
                            @if ($banner->store) <!-- Проверяем, есть ли связанный магазин -->

                            <a href="{{ route( 'store.edit', $banner-> store) }}">

                                @if ($banner->photo)
                                    <img height="350" width="100" src="{{ asset('storage/' . $banner->photo) }}" class="card-img-top">
                                @endif

                            </a>


                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $banner->caption }}</h5>
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
