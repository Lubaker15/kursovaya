@extends('admin.layouts.app')

@section('title', 'Редактировать заказ')

@section('content')
    <h1>Заказ №{{ $order->id }}</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Информация о заказе</div>
                <div class="card-body">
                    <p><strong>Пользователь:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }} ({{ $order->user->email }})</p>
                    <p><strong>Дата заказа:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                    <p><strong>Текущий статус:</strong> 
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">Оплачен</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning">Ожидает оплаты</span>
                        @else
                            <span class="badge bg-danger">Отменён</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Изменить статус и адрес</div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Статус оплаты</label>
                            <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Ожидает оплаты</option>
                                <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Оплачен</option>
                                <option value="cancelled" {{ old('payment_status', $order->payment_status) == 'cancelled' ? 'selected' : '' }}>Отменён</option>
                            </select>
                            @error('payment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Адрес доставки</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="2" required>{{ old('delivery_address', $order->delivery_address) }}</textarea>
                            @error('delivery_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Обновить заказ</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Назад</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Состав заказа</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ number_format($item->unit_price, 0, '.', ' ') }} ₽</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->total_price, 0, '.', ' ') }} ₽</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Итого:</th>
                        <th>{{ number_format($order->total, 0, '.', ' ') }} ₽</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection