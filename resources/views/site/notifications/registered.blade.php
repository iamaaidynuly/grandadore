@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            <span>Вы успешно зарегистрировались на сайте гипермаркета {{ env('APP_NAME') }}</span>
        </td>
    </tr>
    <tr>
        <td height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <span>Чтобы подтвердить адрес эл. почты, нажмите на ссылку ниже</span>
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', ['text' => 'Подтвердить', 'link' => $url ?? '#'])
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop
