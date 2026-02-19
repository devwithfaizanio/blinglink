@extends('backend.layouts.master')
@section('title','Change Password')
@section('content')
    <!-- Workspace -->
    <main class="workspace overflow-hidden">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb">
            <h1>Dashboard</h1>
            <ul>
                <li><a href="#">Login</a></li>
                <li class="divider la la-arrow-right"></li>
                <li>Update Password</li>
            </ul>
        </section>
        <div class="container flex items-center justify-center">
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
                @if (Session::has('success_message'))
                    <div class="alert alert_outlined alert_success mt-5">
                        <strong class="uppercase">
                            <bdi>Success!</bdi>
                        </strong>
                        {{ Session::get('success_message') }}
                        <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
                    </div>
                @endif
                <form class="card mt-5 p-5 md:p-10" action="{{route('change.password.post')}}" method="POST"
                      id="reset_password">
                    @csrf
                    <div class="mb-5">
                        <label class="label block mb-2" for="password">Current Password</label>
                        <label class="form-control-addon-within">
                            <input id="password" type="password" class="form-control border-none"
                                   name="current_password">
                            <span class="flex items-center ltr:pr-4 rtl:pl-4">
                                <button type="button"
                                        class="btn btn-link text-gray-600 dark:text-gray-600 la la-eye text-xl leading-none"
                                        data-toggle="password-visibility"></button>
                            </span>
                        </label>
                    </div>
                    <div class="mb-5">
                        <label class="label block mb-2" for="password">New Password</label>
                        <label class="form-control-addon-within">
                            <input id="password" type="password" class="form-control border-none" name="new_password">
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
                            <input id="password" type="password" class="form-control border-none"
                                   name="confirm_password">
                            <span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
                                    class="btn btn-link text-gray-600 dark:text-gray-600 la la-eye text-xl leading-none"
                                    data-toggle="password-visibility"></button>
                        </span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <button type="submit" class="btn btn_primary ltr:ml-auto rtl:mr-auto uppercase">Update
                            Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @include('backend.layouts.footer')
    </main>
@endsection
<!-- Scripts -->
@section('script')
    <!-- Javascript Requirements -->
    <script src="{{asset('js-validation/jquery.min.js')}}"></script>
    <script src="{{asset('js-validation/bootstrap.min.js')}}"></script>

    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\web\v1\ResetPasswordRequest', '#reset_password'); !!}
@endsection
