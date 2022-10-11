@extends('admin.layouts.app')
@section('content')
    <form
        action="{!! $edit?route('admin.delivery_regions.edit', ['id'=>$item->id]):route('admin.delivery_regions.add') !!}"
        method="post">
        @csrf @method($edit?'patch':'put')
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="c-title">Название</div>
                    <div class="little-p">
                        <input type="text" name="title" class="form-control" placeholder="Название" maxlength="255"
                               value="{{ old('title', $item->title??null) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
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
