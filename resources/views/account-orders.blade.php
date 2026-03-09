<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
</head>
<body>
    <x-navbar></x-navbar>

    <div class="first-section-full-bg">
        <div class="container info-block">
            <h1 class="mb-4">Мои заказы</h1>
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('account') }}">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('account.orders') }}">Мои заказы</a>
                </li>
            </ul>
            @if($orders->isEmpty())
                <div class="alert alert-info">У вас пока нет заказов.</div>
            @else
                @foreach($orders as $order)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Заказ №{{ $order->id }} от {{ $order->created_at->format('d.m.Y H:i') }}</span>
                            <span>
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status == 'paid' ? 'Оплачен' : 'Ожидает оплаты' }}
                                </span>
                                @if($order->payment_status != 'paid')
                                    <form action="{{ route('order.pay', $order->id) }}" method="POST" style="display: inline-block; margin-left: 10px;">
                                        @csrf
                                        <button type="submit" class="btn-success">Оплатить</button>
                                    </form>
                                @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                            <p><strong>Сумма заказа:</strong> {{ number_format($order->total, 0, '.', ' ') }} ₽</p>
                            <h6>Состав заказа:</h6>
                            <ul class="list-group">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item->product->product_name }}
                                        <span>{{ $item->quantity }} × {{ number_format($item->unit_price, 0, '.', ' ') }} ₽ = {{ number_format($item->total_price, 0, '.', ' ') }} ₽</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <x-footer></x-footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>