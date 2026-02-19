<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    @include('backend.layouts.style')

</head>
<body class="selection:bg-[#17725b] selection:text-white">

<!-- Header -->
@include('backend.layouts.header')
{{--side bar--}}
@include('backend.layouts.sidebar')
@include('backend.layouts.theme')
<!--/ content -->
@yield('content')
{{--footer--}}
{{--@include('newlayouts.footer')--}}
{{--script--}}
@include('backend.layouts.script')
</body>

</html>
