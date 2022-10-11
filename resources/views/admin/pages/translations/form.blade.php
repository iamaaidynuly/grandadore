@extends('admin.layouts.app')
@section('content')
    @card
    <div class="card little-p">
        <form action="{{ url()->current() }}" method="post">@csrf @method('patch')
            @foreach($fileContent as $key=>$content)
                @if(is_array($content))
                    <div class="form-group mb-2 pt-1"
                         style="border-top:1px solid #888888; border-bottom: 1px solid #888888">
                        <h4>{{ $key }}</h4>
                        <div class="p-3">
                            @foreach($content as $sub)
                                <div class="form-group mb-1">
                                    <div class="form-group mb-1">
                                        <label class="d-block">
                                            <span class="d-block">{{ $sub }}</span>
                                            <input type="text" name="t[{{ $key }}][{{ $sub }}]" class="form-control"
                                                   value="{{ t("$filename.$key.$sub", [], $locale, false) }}">
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="form-group mb-1">
                        <div class="form-group mb-1">
                            <label class="d-block">
                                <span class="d-block">{{ $key }}</span>
                                <input type="text" name="t[{{ $key }}]"
                                       value="{{ t("$filename.$key", [], $locale, false) }}" class="form-control">
                            </label>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="col-12 save-btn-fixed">
                <button type="submit"></button>
            </div>
        </form>
    </div>
    @endcard
@endsection
