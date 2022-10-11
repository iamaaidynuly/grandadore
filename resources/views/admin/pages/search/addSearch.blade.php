@extends('admin.layouts.app')
@section('content')
    <form method="post" action="{{ route('admin.createSearch') }}">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="little-p">
                        @if(session('error'))
                            <div class="text-danger">{!! session()->get('error') !!} </div>
                        @endif
                        @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Название'])
                        <input type="text" name="title[{!! $iso !!}]" class="form-control" placeholder="Название"
                               value="{{ old('title.'.$iso, tr($item, 'title', $iso)) }}" maxlength="255">
                        @endbylang

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>

    <script src="https://api-maps.yandex.ru/2.1/?apikey=ce752946-5050-4a17-9d27-624b2dc71d8b&lang=ru_RU"></script>


@endsection
{{--@push('js')--}}
{{--@js(aApp('select2/select2.js'))--}}
{{--<script>--}}
{{--$('.select2').select2();--}}
{{--</script>--}}
{{--@endpush--}}
{{--@push('css')--}}
{{--@css(aApp('select2/select2.css'))--}}
{{--@endpush--}}
