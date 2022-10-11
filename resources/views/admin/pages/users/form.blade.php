@extends('admin.layouts.app')
@section('content')
    @if(isset($role))
        <form action="{{route('admin.users.add_put.admin',['role'=>$role])}}" method="post"
              enctype="multipart/form-data">
            @else
                <form action="{{route('admin.users.add_put',['type'=>$type])}}" method="post"
                      enctype="multipart/form-data">
                    @endif
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="card p-2">
                                {{--                @bylang(['id'=>'form_title', 'tp_classes'=>'little-p', 'title'=>'Имя'])--}}
                                <label for="name">Имя</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Имя"
                                       value="{{ isset($item)??old('name')}}">
                                {{--                @endbylang--}}
                            </div>


                            @labelauty(['id'=>'active', 'label'=>'Неактивно|Активно'])@endlabelauty
                        </div>

                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card p-2">
                            <label for="email">Эл.почта</label>
                            <input type="text" max="255" id="email" name="email" class="form-control"
                                   placeholder="email" value="{{old('email')}}">
                        </div>
                        <div class="card p-2">
                            <label for="password">Пароль</label>
                            <input class="form-control" type="password" max="255" id="password" name="password"
                                   placeholder="Пароль" value="{{old('password')}}">
                        </div>
                        <div class="card p-2">
                            <label for="confirm_password">Повтарите Пароль</label>
                            <input class="form-control" type="password" max="255" id="confirm_password"
                                   name="confirm_password" placeholder="Пароль" value="{{old('confirm_password')}}">
                        </div>
                    </div>


                    </div>


                    <div class="col-12 save-btn-fixed">
                        <button type="submit"></button>
                    </div>
                </form>
                @endsection
                @push('js')
                @endpush
                @css(aApp('select2/select2.css'))
