@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            <span>Подтверждение адреса эл. почты</span>
        </td>
    </tr>
    <tr>
        <td height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <span>Ваш код подтверждения на сайте dev.loc</span>
        </td>
    </tr>
    <tr>
        <td height="15"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <u style="font-size: 24px;">{{ $code }}</u>
        </td>
    </tr>
    <tr>
        <td height="25"></td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')
@stop
