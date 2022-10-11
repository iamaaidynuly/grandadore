@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Company')
@section('content')

    <div class="container">
        <div class="row">
            @foreach($companies as $company)
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6  col-12 mt-5">
                    <div class="company-bg-image">
                        <div class="company-card-image">
                            <a href="{{ route('boutiques.view', ['alias' => $company->url]) }}">
                                <img src="{{ empty($company->image) ? asset('u/users/small/default.jpg') : asset('u/users/small/'.$company->image) }}"
                                    alt="">
                            </a>
                        </div>
                        @if($company->logo)
                            <div class="company-top-image">
                                <a href="{{ route('boutiques.view', ['alias' => $company->url]) }}">
                                    <img src="{{ asset('u/users/thumbs/'.$company->logo) }}" style="" alt="">
                                </a>
                            </div>
                        @endif
                        <div class="company-name">
                            <a href="{{ route('boutiques.view', ['alias' => $company->url]) }}">
                                {{ $company->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                {!! $companies->links() !!}
            </div>
        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/company.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush
