@extends('site.layouts.main', ['headerSidebar' => true])
@section('title', 'Login')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-column w-100 my-5">
            <div class="login-section ">
                <form action="{{ route('password.update', ['email'=>$email,'token'=>$token]) }}" method="post" class="w-100 d-contents">
                    @csrf
                    <p class="sub-text mb-1">Создайте новый пароль</p>
                    <div class="login-input">
                        <div class="input-group my-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <svg id="Group_5700" data-name="Group 5700" xmlns="http://www.w3.org/2000/svg" width="13.701" height="13.701" viewBox="0 0 13.701 13.701">
                                        <path id="Path_11354" data-name="Path 11354" d="M11.7,2.007a6.851,6.851,0,1,0-9.688,9.688,6.855,6.855,0,0,0,8.1,1.187.412.412,0,0,0-.392-.725,6.027,6.027,0,1,1,3.166-5.306,5.945,5.945,0,0,1-.362,2.07,1.351,1.351,0,0,1-1.065.743A1.132,1.132,0,0,1,10.32,8.533V4.188a.412.412,0,1,0-.824,0v.421a3.468,3.468,0,1,0,.061,4.41,1.958,1.958,0,0,0,1.894,1.469,2.166,2.166,0,0,0,1.8-1.2A6.605,6.605,0,0,0,13.7,6.851,6.806,6.806,0,0,0,11.7,2.007ZM6.852,9.495A2.644,2.644,0,1,1,9.5,6.851,2.647,2.647,0,0,1,6.852,9.495Z" transform="translate(-0.001 0)" fill="#858585"/>
                                    </svg>
                                </div>
                            </div>
                            <input type="email" class="form-control" id="inlineFormInputGroup" placeholder="Эл. почта *" name="email" value="{{ $email }}" readonly>
                            @if($errors->has('email'))
                                <span class="input-alert">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="input-group  mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <svg id="_001-lock" data-name="001-lock" xmlns="http://www.w3.org/2000/svg"
                                         width="13.052" height="16.541" viewBox="0 0 13.052 16.541">
                                        <g id="Group_5670" data-name="Group 5670" transform="translate(6.203 1.292)">
                                            <g id="Group_5669" data-name="Group 5669" transform="translate(0)">
                                                <path id="Path_11350" data-name="Path 11350"
                                                      d="M246.359,40h-.036a.323.323,0,1,0,0,.646h.033a.323.323,0,0,0,0-.646Z"
                                                      transform="translate(-246 -40)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5672" data-name="Group 5672" transform="translate(0)">
                                            <g id="Group_5671" data-name="Group 5671">
                                                <path id="Path_11351" data-name="Path 11351"
                                                      d="M66.083,6.268H64.694v-2.1a4.168,4.168,0,0,0-8.335,0v2.1H54.969A.97.97,0,0,0,54,7.237v8.335a.97.97,0,0,0,.969.969H66.083a.97.97,0,0,0,.969-.969V7.237A.97.97,0,0,0,66.083,6.268Zm.323,9.3a.323.323,0,0,1-.323.323H54.969a.323.323,0,0,1-.323-.323V7.237a.323.323,0,0,1,.323-.323H59.46a.323.323,0,1,0,0-.646H58.3v-2.1A2.228,2.228,0,0,1,59.66,2.114a.323.323,0,1,0-.252-.595,2.873,2.873,0,0,0-1.757,2.649v2.1H57v-2.1a3.522,3.522,0,0,1,7.043,0v2.1H63.4v-2.1a2.882,2.882,0,0,0-1.7-2.625.323.323,0,0,0-.265.589,2.235,2.235,0,0,1,1.32,2.035v2.1h-1.05a.323.323,0,1,0,0,.646h4.378a.323.323,0,0,1,.323.323Z"
                                                      transform="translate(-54)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5674" data-name="Group 5674" transform="translate(6.243 6.268)">
                                            <g id="Group_5673" data-name="Group 5673" transform="translate(0)">
                                                <path id="Path_11352" data-name="Path 11352"
                                                      d="M247.581,194h-.008a.323.323,0,0,0,0,.646h.008a.323.323,0,0,0,0-.646Z"
                                                      transform="translate(-247.25 -194)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5676" data-name="Group 5676" transform="translate(5.169 9.014)">
                                            <g id="Group_5675" data-name="Group 5675">
                                                <path id="Path_11353" data-name="Path 11353"
                                                      d="M216.246,281.382A1.357,1.357,0,1,0,214,280.357a1.364,1.364,0,0,0,.489,1.043l-.214,2.025a.323.323,0,0,0,.321.357h1.544a.323.323,0,0,0,.321-.357Zm-.512-.423a.323.323,0,0,0-.15.308l.2,1.868h-.826l.2-1.855a.323.323,0,0,0-.156-.312.711.711,0,1,1,.738-.009Z"
                                                      transform="translate(-214 -279)" fill="#858585"/>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGroup" placeholder="Пароль" name="password">
                            @if($errors->has('password'))
                                <span class="input-alert">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="input-group  mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <svg id="_001-lock" data-name="001-lock" xmlns="http://www.w3.org/2000/svg"
                                         width="13.052" height="16.541" viewBox="0 0 13.052 16.541">
                                        <g id="Group_5670" data-name="Group 5670" transform="translate(6.203 1.292)">
                                            <g id="Group_5669" data-name="Group 5669" transform="translate(0)">
                                                <path id="Path_11350" data-name="Path 11350"
                                                      d="M246.359,40h-.036a.323.323,0,1,0,0,.646h.033a.323.323,0,0,0,0-.646Z"
                                                      transform="translate(-246 -40)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5672" data-name="Group 5672" transform="translate(0)">
                                            <g id="Group_5671" data-name="Group 5671">
                                                <path id="Path_11351" data-name="Path 11351"
                                                      d="M66.083,6.268H64.694v-2.1a4.168,4.168,0,0,0-8.335,0v2.1H54.969A.97.97,0,0,0,54,7.237v8.335a.97.97,0,0,0,.969.969H66.083a.97.97,0,0,0,.969-.969V7.237A.97.97,0,0,0,66.083,6.268Zm.323,9.3a.323.323,0,0,1-.323.323H54.969a.323.323,0,0,1-.323-.323V7.237a.323.323,0,0,1,.323-.323H59.46a.323.323,0,1,0,0-.646H58.3v-2.1A2.228,2.228,0,0,1,59.66,2.114a.323.323,0,1,0-.252-.595,2.873,2.873,0,0,0-1.757,2.649v2.1H57v-2.1a3.522,3.522,0,0,1,7.043,0v2.1H63.4v-2.1a2.882,2.882,0,0,0-1.7-2.625.323.323,0,0,0-.265.589,2.235,2.235,0,0,1,1.32,2.035v2.1h-1.05a.323.323,0,1,0,0,.646h4.378a.323.323,0,0,1,.323.323Z"
                                                      transform="translate(-54)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5674" data-name="Group 5674" transform="translate(6.243 6.268)">
                                            <g id="Group_5673" data-name="Group 5673" transform="translate(0)">
                                                <path id="Path_11352" data-name="Path 11352"
                                                      d="M247.581,194h-.008a.323.323,0,0,0,0,.646h.008a.323.323,0,0,0,0-.646Z"
                                                      transform="translate(-247.25 -194)" fill="#858585"/>
                                            </g>
                                        </g>
                                        <g id="Group_5676" data-name="Group 5676" transform="translate(5.169 9.014)">
                                            <g id="Group_5675" data-name="Group 5675">
                                                <path id="Path_11353" data-name="Path 11353"
                                                      d="M216.246,281.382A1.357,1.357,0,1,0,214,280.357a1.364,1.364,0,0,0,.489,1.043l-.214,2.025a.323.323,0,0,0,.321.357h1.544a.323.323,0,0,0,.321-.357Zm-.512-.423a.323.323,0,0,0-.15.308l.2,1.868h-.826l.2-1.855a.323.323,0,0,0-.156-.312.711.711,0,1,1,.738-.009Z"
                                                      transform="translate(-214 -279)" fill="#858585"/>
                                            </g>
                                        </g>
                                    </svg>

                                </div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGroup" placeholder="Повторите пароль *" name="password_confirmation">
                            @if($errors->has('password_confirmation'))
                                <span class="input-alert">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class=" btn login-btn">Подтвердить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
