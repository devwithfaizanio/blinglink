@if (Session::has('success'))
    <div id="toasts-container"
         class="toasts-container top-auto lg:top-0 bottom-0 lg:bottom-auto right-0 left-0 lg:ltr:left-auto lg:rtl:right-auto">
        <div class="toast mb-4 ">
            <div class="toast-header bg-primary text-white">
                <h5>Successfully</h5>
                <small>just now</small>
                <button type="button" class="close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body uppercase">
                {{ Session::get('success') }}
            </div>
        </div>
    </div>
@endif
<script>
{{--    stay toaster only three second--}}
    setTimeout(function () {
        $('.toast').toast('hide');
    }, 3000);
</script>
