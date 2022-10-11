@extends('site.pages.cabinet.cabinet_layout')

@section('cabinetContent')
    <form action="{{ route('cabinet.userMessages') }}" method="post">
        @csrf
        <div class="w-100">
            <h1 class="mb-xl-3">Подержка</h1>
            <div class="form-group row">
                <div class="col-md-4 col-12 my-md-0 my-2">
                    <input type="text" class="form-control" id="name" name="name" value="{{ authUser()->name }}">
                </div>
                <div class="col-md-4 col-12 my-md-0 my-2">
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ authUser()->phone }}">
                </div>
                <div class="col-md-4 col-12 my-md-0 my-2">
                    <input type="text" class="form-control" id="email" name="email" value="{{ authUser()->email }}">
                </div>
            </div>
            <div class="form-group">
                <textarea name="message" id="message" class="form-control" placeholder="Опишите проблему или вопрос."
                          rows="7"></textarea>
            </div>
            <div class="d-flex justify-content-end align-items-end flex-column p-0 mb-5">
                <button class="btn btn-grey mt-2">Отправить</button>
            </div>
        </div>
    </form>
    <div class="w-100">
        <div class="form-group">
            <div class="accordion section-support" id="accordionExample">
                @foreach($questions as $i => $question)
                    <div class="card">
                        <div class="card-header m-0 p-0" id="headingOne-{{ $i }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link w-100 text-left py-3" type="button"
                                        data-toggle="collapse" data-target="#collapseOne-{{ $i }}"
                                        aria-expanded="true" aria-controls="collapseOne-{{ $i }}">
                                    {{ $question->title }}
                                    <i class="float-right fa fa-angle-up"></i>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne-{{ $i }}" class="collapse{{ $i == 0 ? ' show' : '' }}" aria-labelledby="headingOne{{ $i }}"
                             data-parent="#accordionExample">
                            <div class="card-body">
                                {!! $question->answer !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
