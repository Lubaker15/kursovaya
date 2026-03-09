@extends('admin.layouts.app')

@section('title', 'Редактировать товар')

@section('content')
    <h1>Редактировать товар: {{ $product->product_name }}</h1>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Основная информация</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Название товара</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                   id="product_name" name="product_name" 
                                   value="{{ old('product_name', $product->product_name) }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Категория</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit_price" class="form-label">Цена (₽)</label>
                                <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" 
                                       id="unit_price" name="unit_price" 
                                       value="{{ old('unit_price', $product->unit_price) }}" required>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_quantity" class="form-label">Количество на складе</label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" name="stock_quantity" 
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">Изображение товара</div>
                    <div class="card-body">
                        @if($product->image_url)
                            <div class="mb-3 text-center">
                                <img src="{{ asset($product->image_url) }}" alt="{{ $product->product_name }}" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                                <p class="text-muted small mt-2">Текущее изображение</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">Загрузить новое изображение</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Оставьте пустым, чтобы сохранить текущее изображение.</div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Действия</div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">Сохранить изменения</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection