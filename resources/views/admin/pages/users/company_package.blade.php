@extends('admin.layouts.app')
@section('title', 'Редактирование пакета для магазина')
@section('content')
    <div class="col-12">
        <div class="card p-3">
            <form method="post" action="{{route('admin.users.packages.submit',['id'=>$company->id])}}"
                  class="d-flex flex-column">
                @csrf
                <label for="discount">Связать скидку с пользователем</label>

                <select class="js-example-basic-multiple" name="package_id" id="package_id">
                    @foreach($packages as $package)
                        <option
                            value="{{$package->id}}" {{(in_array($package->id,$company_package)?'selected':null)}}> {{$package->title}} </option>
                    @endforeach
                </select>
                <div class="card-actionbar mt-3">
                    <button type="submit" class="btn ink-reaction btn-raised btn-primary">Сохранить</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push('js')
    @js(aApp('select2/select2.js'))
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2({
                tags: true
            });
        });
    </script>
@endpush

@css(aApp('select2/select2.css'))
