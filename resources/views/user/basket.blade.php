@extends('user.layout')

@section('title', 'Корзина')

@section('content')
    <div class="container mt-4">
        <h2>Корзина</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($basketItems->isEmpty())
            <p>Ваша корзина пуста.</p>
        @else
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($basketItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>
                            <form id="update-form-{{ $index }}" method="POST" action="{{ route('user.product.update', ['product' => $item->product->id]) }}">
                                @csrf
                                @method('PATCH')
                                <input type="number" value="{{ old('quantity', $item->quantity) }}" name="quantity" class="form-control" onchange="submitForm({{ $index }})" required>
                            </form>
                        </td>
                        <td>{{ $item->product->price }}</td>
                        <td>{{ $item->quantity * $item->product->price }}</td>
                        <td>
                            <form method="POST" action="{{ route('user.product.basket.remove', ['product' => $item->product->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    Убрать из корзины
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Общая сумма:</strong></td>
                    <td>{{ $totalAmount }}</td>
                    <td>
                        <form action="{{ route('user.product.basket.buy-all') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm">Купить все</button>
                        </form>
                    </td>
                </tr>
                </tfoot>
            </table>
        @endif
    </div>
    <script>
        function submitForm(index) {
            document.getElementById('update-form-' + index).submit();
        }
    </script>
@endsection
