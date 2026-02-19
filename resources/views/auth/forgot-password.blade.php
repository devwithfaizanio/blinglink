<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    @section('title','Forgot Password')
    @include('backend.layouts.style')
</head>
<body class="selection:bg-[#17725b] selection:text-white">
<!-- Top Bar -->

<section class="top-bar">
    @include('backend.layouts.toaster')
    <!-- Brand -->
    <span class="brand">{{settingKey()['app_name']}}</span>

    <nav class="flex items-center ltr:ml-auto rtl:mr-auto">

        <!-- Dark Mode -->
        <label class="switch switch_outlined" data-toggle="tooltip" data-tippy-content="Toggle Dark Mode">
            <input id="darkModeToggler" type="checkbox">
            <span></span>
        </label>

        <!-- Fullscreen -->
        <button id="fullScreenToggler" type="button"
                class="hidden lg:inline-block btn-link ltr:ml-5 rtl:mr-5 text-2xl leading-none la la-expand-arrows-alt"
                data-toggle="tooltip" data-tippy-content="Fullscreen"></button>

        <!-- Login -->
        <a href="{{route('login')}}" class="btn btn_primary uppercase ltr:ml-5 rtl:mr-5">Login</a>
    </nav>
</section>

<div class="container flex items-center justify-center mt-20 py-10">
    <div class="w-full md:w-1/2 xl:w-1/3">
        @if (Session::has('link_error'))
            <div class="alert alert_outlined alert_primary mt-5">
                <strong class="uppercase">
                    <bdi>Warning!</bdi>
                </strong>
                {{ Session::get('link_error') }}
                <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
            </div>
        @endif
        @if (Session::has('link_success'))
            <div class="alert alert_outlined alert_success mt-5">
                <strong class="uppercase">
                    <bdi>Success!</bdi>
                </strong>
                {{ Session::get('link_success') }}
                <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
            </div>
        @endif

        <div class="mx-5 md:mx-10">
            <h2 class="uppercase">Forgot Password?</h2>
            <h4 class="uppercase">We'll Email You Soon</h4>
        </div>
        <form class="card mt-5 p-5 md:p-10" action="{{route('forgot.password.post')}}" method="POST" id="forgot">
            @csrf
            <div class="mb-5">
                <label class="label block mb-2" for="email">Email</label>
                <input id="email" type="text" class="form-control" placeholder="example@example.com" name="email">
            </div>
            {{--            if email validation error--}}
            @error('email')
            <div class="alert alert_outlined alert_primary mt-5 mb-5">
                {{ $message }}
                <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
            </div>
            @enderror

            <div class="flex">
                <button type="submit" class="btn btn_primary ltr:ml-auto rtl:mr-auto uppercase">Send Reset
                    Link
                </button>
            </div>
        </form>
    </div>
</div>
@include('backend.layouts.script')

<!-- Javascript Requirements -->
<script src="{{asset('js-validation/jquery.min.js')}}"></script>
<script src="{{asset('js-validation/bootstrap.min.js')}}"></script>

<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\web\v1\ForgotPassword', '#forgot'); !!}

</body>
</html>
