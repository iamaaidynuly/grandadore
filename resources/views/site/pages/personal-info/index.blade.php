@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'personal-info')

@section('content')
    <div class="container">
        <div class="personal-info-bar-btn">
            <div class="btn-section">
                <div class="personal-info-button">
                    <div class="toggle-wrap-personal-info">
                        <span class="toggle-bar"></span>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-flex mt-5">
            @include('site.components.personal-infobar')
            <div class="personal-inforamtion w-100 ml-lg-5">
                <h1 class="mb-5">Личная информация</h1>
                <form>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">ФИО <sup>*</sup></label>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control" id="name" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="city" class="col-sm-3 col-form-label">Город <sup>*</sup></label>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control"  id="city">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Мобильный телефон <sup>*</sup></label>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control" id="phone" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">E-mail <sup>*</sup></label>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control" class="form-control-plaintext" id="email"  >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 text-left text-lg-right">
                            <input type="checkbox"    >
                        <label  class="col-form-label">Согласен на обработку персональных данных</label>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-grey " type="submit">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/personal-info.css') }}">
@endpush
@section('js')
    <script src="{{ asset('js/personal-info.js') }}"></script>
@endsection
