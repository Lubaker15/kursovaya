@extends('admin.layouts.app')

@section('title', 'Добавить категорию')

@section('content')
    <h1>Добавить категорию</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="category_name" class="form-label">Название категории</label>
            <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" value="{{ old('category_name') }}" required>
            @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection