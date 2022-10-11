@extends('site.notifications.layout')
@section('content')
    <p><strong>Раздел:</strong> Контакты</p>
    <p><strong>Имя:</strong> {{ $name }}</p>
    <p><strong>Эл.почта:</strong> {{ $email }}</p>
    <p><strong>Телефон:</strong> {{ $phone }}</p>
    <div>
        <strong>Сообщение:</strong>
        <p>{{ $ }}</p>
    </div>
@endsection
