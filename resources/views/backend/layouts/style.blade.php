

<title>@yield('title')</title>

<!-- Generics -->
<link rel="icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="32x32">
<link rel="icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="128x128">
<link rel="icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="192x192">

<!-- Android -->
{{--<link rel="shortcut icon" href="{{asset('backend/assets/images/favicon/favicon-196.png')}}" sizes="196x196">--}}
<link rel="shortcut icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="196x196">
{{--@vite('resources/css/app.css')--}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- iOS -->
<link rel="apple-touch-icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="152x152">
<link rel="apple-touch-icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="167x167">
<link rel="apple-touch-icon" href="{{ asset(settingKey()['app_logo'] ? 'images/setting/' . settingKey()['app_logo'] : asset('logo/secondaryLogo.png')) }}" sizes="180x180">

<link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}" />
{{--data table link--}}

<link href="{{asset('datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('datatable/css/buttons.dataTables.min.css')}}" />


<link href="{{asset('select2/select2.min.css')}}" rel="stylesheet" />

<link href="{{asset('toaster/toastr.css')}}" rel="stylesheet">
@yield('style')

