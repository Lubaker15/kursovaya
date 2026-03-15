@extends('admin.layouts.app')

@section('title', 'Заказы')

@section('content')
    <main>
        <h1>Заказы</h1>
        <div class="card info-block">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID заказа</th>
                            <th>Пользователь</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                <td>{{ number_format($order->total, 0, '.', ' ') }} ₽</td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Оплачен</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Ожидает оплаты</span>
                                    @else
                                        <span class="badge bg-danger">Отменён</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning">Редактировать</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </main>
@endsection