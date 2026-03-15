@extends('admin.layouts.app')

@section('title', 'Товары')

@section('content')
    <main>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Товары</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Добавить товар</a>
        </div>
        <div class="card info-block">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Остаток</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category->category_name ?? '-' }}</td>
                                <td>{{ number_format($product->unit_price, 0, '.', ' ') }} ₽</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Редактировать</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить товар?')">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </main>
@endsection