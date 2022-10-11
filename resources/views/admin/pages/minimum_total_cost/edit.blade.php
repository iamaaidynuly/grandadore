@extends('admin.layouts.app')
@section('content')
    <form
        action="{!! $edit?route('admin.minimum_total_cost.edit', ['id'=>1]):route('admin.minimum_total_cost.add') !!}"
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
                        <input type="text" name="price" class="form-control" placeholder="Название" maxlength="255"
                               value="{{ old('title', $item->price??null) }}">
                    </div>
                </div>
                <div class="card">
                    @bylang(['id'=>'form_text', 'tp_classes'=>'little-p', 'title'=>'Название'])
                    <input type="text" name="text[{!! $iso !!}]" class="form-control" placeholder="Название"
                           value="{{ old('text.'.$iso, tr($item, 'text', $iso)) }}">
                    @endbylang
                </div>
            </div>
        </div>
        <div class="col-12 save-btn-fixed">
            <button type="submit"></button>
        </div>
    </form>
@endsection
