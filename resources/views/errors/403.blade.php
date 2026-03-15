<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступ ограничен</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #26145E;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        .error-code {
            font-size: 80px;
            font-weight: bold;
            margin: 0px;
        }

        .message {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
        }

        .back-link {
            margin-top: 20px;
            display: block;
            text-decoration: none;
            color: #94C91F;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div>
            <h1 class="error-code">403</h1>
            <p>Старина! Кажется, у тебя нет прав. Тут только доступ для админов.</p>
            <a class="back-link" href="/">Уйти</a>
        </div>
    </div>

</body>
</html>
