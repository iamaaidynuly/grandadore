@extends('admin.layouts.app')
@section('content')

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card p-2">
                <p>Сообщение</p>
                <p>{{$item->message}}</p>
            </div>


        </div>

    </div>
@endsection
@push('js')
@endpush
