<script src="{{asset('backend/assets/js/vendor.js')}}"></script>
<script src="{{asset('backend/assets/js/chart.min.js')}}"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/chart.js@3.2.1"></script>--}}
<script src="{{asset('backend/assets/js/script.js')}}"></script>
{{--data table --}}
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('datatable/js/vfs_fonts.js')}}"></script>
<script src="{{asset('datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('datatable/js/buttons.print.min.js')}}"></script>


<script src="{{asset('backend/assets/js/Sortable.min.js')}}"></script>
{{--<script>--}}
{{--    $(document).ready( function () {--}}
{{--        $('#myTable').DataTable();--}}
{{--    } );--}}
{{--</script>--}}

<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {

            dom: 'Bfrtip',
            // // dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength','copy', 'csv', 'excel', 'pdf', 'print',
            ],
        } );
    } );



</script>
{{--</script>--}}
<script src="{{asset('plugin/ckeditor2/ckeditor.js')}}"></script>

{{--<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>--}}
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            ckfinder: {
                uploadUrl: '{{route('upload').'?_token='.csrf_token()}}',
            }
        })
        .catch( error => {
            console.error( error );
        } );
</script>




<script src="{{asset('select2/select2.min.js')}}"></script>




<script src="{{asset('toaster/toastr.min.js')}}"></script>

<script>
    @if (Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':

            toastr.options.timeOut = 10000;
            toastr.info("{{ Session::get('message') }}");
            var audio = new Audio('audio.mp3');
            audio.play();
            break;
        case 'success':

            toastr.options.timeOut = 10000;
            toastr.success("{{ Session::get('message') }}");
            var audio = new Audio('audio.mp3');
            audio.play();

            break;
        case 'warning':

            toastr.options.timeOut = 10000;
            toastr.warning("{{ Session::get('message') }}");
            var audio = new Audio('audio.mp3');
            audio.play();

            break;
        case 'error':

            toastr.options.timeOut = 10000;
            toastr.error("{{ Session::get('message') }}");
            var audio = new Audio('audio.mp3');
            audio.play();

            break;
    }
    @endif
</script>


@yield('script')
