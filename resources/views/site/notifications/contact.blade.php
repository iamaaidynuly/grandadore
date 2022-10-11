@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            <span>Новое сообщение обратной связи на сайте.</span>
        </td>
    </tr>
    <tr>
        <td height="20"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <ul>
                <li>
                    <b>Имя</b>
                    <span>{{ $name }}</span>
                </li>
                <li>
                    <b>Номер телефона</b>
                    <span>{{ $phone }}</span>
                </li>
                <li>
                    <b>Эл. почта</b>
                    <span>{{ $email }}</span>
                </li>
                <li>
                    <b>Сообщение</b>
                    <p>
                        {{ $messageText }}
                    </p>
                </li>
            </ul>
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')
@stop
