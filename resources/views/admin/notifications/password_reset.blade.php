<!doctype html>
<html lang="hy">
<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        p {
            margin-top: 5px;
        }
    </style>
    <title></title>
</head>
<body>
<h1 style="margin-top:10px">{{ env('APP_NAME') }}</h1>
<p>Мы получили запрос на сброс пароля.</p>
<p><a href="{{ $url }}">Нажмите здесь, чтобы изменить свой пароль.</a></p>
</body>
</html>
