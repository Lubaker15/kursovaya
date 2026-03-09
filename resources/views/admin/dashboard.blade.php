@extends('admin.layouts.app')

@section('title', 'Дашборд')

@section('content')
    <h1>Добро пожаловать, {{ $admin->first_name }}</h1>
    <div class="card">
        <div class="card-header">Последние заказы</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td>{{ number_format($order->total, 0, '.', ' ') }} ₽</td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Оплачен</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Ожидает</span>
                                @else
                                    <span class="badge bg-danger">Отменён</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection