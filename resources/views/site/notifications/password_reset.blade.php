@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            <span>Восстановление пароля</span>
        </td>
    </tr>
    <tr>
        <td height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <span>Чтобы восстановить свой пароль, нажмите на ссылку ниже.</span>
        </td>
    </tr>
    <tr>
        <td class="paragraph">
            <span>Если вы не запрашивали восстановление пароля, проигнорируйте данное сообщение!</span>
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', ['text' => 'Восстановить', 'link' => $url ?? '#'])
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop
