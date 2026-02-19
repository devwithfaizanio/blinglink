@extends('backend.layouts.master')
@section('title','Announcement')
@section('content')
    {{--        {{dd($brands)}}--}}
    <main class="workspace">
        @include('backend.layouts.toaster')
        <form action="{{route('admin.announcement.store')}}" method="POST" enctype="multipart/form-data" id="addAnnouncementForm">
            @csrf
            <!-- Breadcrumb -->
            <section class="breadcrumb">
                <h1>Announcement</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin.announcement')}}">Announcement</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>Set Announcement</li>
                </ul>
            </section>

            <div class=" lg:-mx-4">

                <div class="lg:w-1/1 xl:w-1/1 lg:px-4">
                    <div class="card p-5">
                        <div class="flex gap-6">
                            <div class="mb-5 xl:w-1/3">
                                <label class="label block mb-2 " for="title">Title</label>
                                <input id="addTitle"  type="text" class="form-control " name="title" placeholder="Title">
                                <span id="addTitleError" class="text-danger" style="color: #ee2020"></span>
                            </div>
                            <div class="xl:w-full" >
                                <label class="label block mb-2 " for="description">Body</label>
                                <input id="addBody" type="text" class="form-control " name="body" placeholder="Body">
                                <span id="addBodyError" class="text-danger" style="color: #ee2020"></span>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2" for="title">Select Type</label>
                                <select class="form-control select2 user_select" name="type" id="addSelectType"  data-placeholder="Search Type" style="width: 100%">
                                    <option></option>
                                    <option value="all">All User</option>
                                    <option value="selected">Selected User</option>
                                </select>
                                <span id="addSelectTypeError" class="text-danger" style="color: #ee2020"></span>
                            </div>
{{--                            <div class="mb-5 xl:w-1/2">--}}
{{--                                <label class="label block mb-2" for="title">Send Type</label>--}}
{{--                                <select class="form-control select2 send_type" name="send_type" id="addSendType" data-placeholder="Search Type" style="width: 100%">--}}
{{--                                    <option></option>--}}
{{--                                    <option value="immediate">Immediate</option>--}}
{{--                                    <option value="scheduled">Scheduled Time</option>--}}
{{--                                </select>--}}
{{--                                <span id="addSendTypeError" class="text-danger" style="color: #ee2020"></span>--}}
{{--                            </div>--}}
                        </div>

                        <div class="flex gap-6">
                            <div class="mb-5 xl:w-1/2" id="individual_user_select_box">
                                <label class="label block mb-2 " for="title">Select Users</label>
                                <select class="form-control  selectUsers" name="selected_user_list[]" id="selected_user_list" data-placeholder="Search Users" multiple style="width: 100%">
                                    <option></option>
                                    @foreach($allUser as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>

                                <span id="SelectListError" class="text-danger" style="color: #ee2020"></span>

                            </div>
{{--                            <div class="mb-5 xl:w-1/2" id="scheduled_date_time_select_box">--}}
{{--                                <label class="label block mb-2" for="title">Select Date And Time</label>--}}
{{--                                <input type="datetime-local" name="scheduled_date_time" id="scheduled_date_time">--}}
{{--                                <span id="scheduledDateTimeError" class="text-danger" style="color: #ee2020"></span>--}}
{{--                            </div>--}}

                        </div>

                        <div class="flex gap-6">
                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2" for="image">Announcement Image</label>
                                <input type="file" id="addFile" name="file" onchange="loadFileBannerImage(event)" class="block w-full text-sm text-gray-500 file:py-2 file:px-6 file:rounded file:border-1 file:border-primary-400">
                                <span id="addFileError" class="text-danger" style="color: #ee2020"></span>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="mb-5 xl:w-1/3">
                                <center >
                                    <span class=""><img src="" alt="No image" id="outputAddBannerImage" style="display: none;  width: 90%; border-radius: 5%"></span>
                                </center>
                            </div>
                        </div>

                    </div>
                    <div class="card mt-5 p-5">
                        <h3>Set Announcement</h3>
                        <button class="mt-5 btn btn_outlined btn_secondary uppercase" type="submit">Submit</button>
                    </div>
                </div>

            </div>
        </form>
        @include('backend.layouts.footer')
    </main>
@endsection
@section('script')

    <script>
        document.getElementById('addAnnouncementForm').addEventListener('submit', function(event) {
            validateInput('addTitle', 'addTitleError', 'Title is required.');
            validateInput('addBody', 'addBodyError', 'Body is required.');
            validateInput('addSelectType', 'addSelectTypeError', 'Select Type is required.');
            validateInput('addSendType', 'addSendTypeError', 'Send Type is required.');
            // validateInput('addFile', 'addFileError', 'File is required.');

            if ($('.send_type').val() === 'scheduled') {
                validateInput('scheduled_date_time', 'scheduledDateTimeError', 'Date and time are required.');
            }
            if ($('.user_select').val() === 'selected') {
                validateInput('selected_user_list', 'SelectListError', 'Users are required.');
            }

        });

        function validateInput(inputId, errorId, errorMessage) {
            var inputValue = document.getElementById(inputId).value;
            var errorElement = document.getElementById(errorId);

            errorElement.textContent = '';

            if (!inputValue.trim()) {
                event.preventDefault();
                errorElement.textContent = errorMessage;
            }
        }
        // Add event listeners for input fields to clear error messages
        ['addTitle','addBody','addSelectType','addSendType','addFileType'].forEach(function(inputId) {
            document.getElementById(inputId).addEventListener('input', function() {
                clearErrorMessage(inputId);
            });
        });

        function clearErrorMessage(inputId) {
            var errorId = inputId + 'Error';
            var errorElement = document.getElementById(errorId);
            errorElement.textContent = '';
        }

    </script>





    <script>
        var loadFileBannerImage = function(event) {
            var output = document.getElementById('outputAddBannerImage');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
                output.style.display = '';
            }
        };
    </script>

    <script>
        // Re-initialize Select2 on the newly added select element
        $('.select2').select2({
            minimumInputLength: 0,
            allowClear: true,
            multiple: false,
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
        $('.selectUsers').select2({
            minimumInputLength: 0,
            allowClear: true,
            multiple: true,
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initially hide the individual user selection div
            $('#individual_user_select_box').hide();

            // When the user selection dropdown changes
            $('.user_select').on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue == 'selected') {
                    $('#individual_user_select_box').show();
                }
                else {
                    $('#individual_user_select_box').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initially hide the individual user selection div
            $('#scheduled_date_time_select_box').hide();

            // When the user selection dropdown changes
            $('.send_type').on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue == 'scheduled') {
                    $('#scheduled_date_time_select_box').show();
                }
                else {
                    $('#scheduled_date_time_select_box').hide();
                }
            });
        });
    </script>


{{--    <script src="{{asset('js-validation/jquery.min.js')}}"></script>--}}
{{--    <script src="{{asset('js-validation/bootstrap.min.js')}}"></script>--}}

{{--    <!-- Laravel Javascript Validation -->--}}
{{--    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>--}}
{{--    {!! JsValidator::formRequest('App\Http\Requests\admin\AnnouncementRequest', '#addAnnouncement'); !!}--}}


@endsection


