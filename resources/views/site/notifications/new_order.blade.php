@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            <span>На сайте поступил новый заказ</span>
        </td>
    </tr>
    <tr>
        <td class="paragraph">
            <span>Для просмотра заказа перейдите по ссылке ниже</span>
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', [
                'text' => 'Посмотреть заказ',
                'link' => $url ?? '#'
            ])
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')
@stop
