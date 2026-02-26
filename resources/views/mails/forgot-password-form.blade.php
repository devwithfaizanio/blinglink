<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    @section('title','Forgot password')
    @include('backend.layouts.style')
</head>
<body>
<!-- Top Bar -->

<section class="top-bar">

    <span class="brand">{{settingKey()['app_name']}}</span>
</section>

<div class="container flex items-center justify-center mt-20 py-10">
    <div class="w-full md:w-1/2 xl:w-1/3">
        @if (Session::has('error_message'))
            <div class="alert alert_outlined alert_primary mt-5">
                <strong class="uppercase">
                    <bdi>Warning!</bdi>
                </strong>
                {{ Session::get('error_message') }}
                <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
            </div>
        @endif
        @if (Session::has('alert'))
            <div class="alert alert_outlined alert_primary mt-5">
                <strong class="uppercase">
                    <bdi>Warning!</bdi>
                </strong>
                {{ session()->get('alert.message') }}
                <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
            </div>
        @endif
        <div class="mx-5 md:mx-10">
            <h2 class="uppercase">It’s Great To See You!</h2>
            <h4 class="uppercase">Forgot password here</h4>
        </div>
        @php
            $data = \DB::table('password_reset_tokens')->where('token', $token)->first();
//                dd($data);
            $email = $data->email;
        @endphp


        <form class="card mt-5 p-5 md:p-10" action="{{route('reset.password.post')}}" method="POST" id="forgotpassword">
            @csrf
            <div class="mb-5">
                <label class="label block mb-2" for="email">Email</label>
                <input id="email" type="text" class="form-control bg-gray-100" value="{{$email}}" name="email" disabled>
            </div>
            <input type="hidden" name="email" value="{{$email}}">
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-5">
                <label class="label block mb-2" for="password">Password</label>
                <label class="form-control-addon-within">
                    <input id="new_password" type="password" class="form-control border-none" name="new_password">
                    <span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
                                    class="btn btn-link text-gray-600 dark:text-gray-600 la la-eye text-xl leading-none"
                                    data-toggle="password-visibility"></button>
                        </span>
                </label>
            </div>
            <div class="mb-5">
                <label class="label block mb-2" for="password">Confirm Password</label>
                <label class="form-control-addon-within">
                    <input id="confirm_password" type="password" class="form-control border-none"
                           name="confirm_password">
                    <span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
                                    class="btn btn-link text-gray-600 dark:text-gray-600 la la-eye text-xl leading-none"
                                    data-toggle="password-visibility"></button>
                        </span>
                </label>
            </div>
            <div class="flex items-center">
                <button type="submit" class="btn btn_primary ltr:ml-auto rtl:mr-auto uppercase">Change</button>
            </div>
        </form>
    </div>
</div>
<!-- Scripts -->
@include('backend.layouts.script')
</body>
<!-- Javascript Requirements -->
<script src="{{asset('js-validation/jquery.min.js')}}"></script>
<script src="{{asset('js-validation/bootstrap.min.js')}}"></script>

<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\web\v1\ForgotPasswordForm', '#forgotpassword'); !!}
</html>
