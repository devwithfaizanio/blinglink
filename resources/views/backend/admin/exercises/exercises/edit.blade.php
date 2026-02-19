@extends('backend.layouts.master')
@section('title','Update Exercise')
@section('content')

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')

        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Exercise</h1>
                <ul>
                    <li><a href="{{ route('admin_dashboard') }}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>Exercise</li>
                    <li class="divider la la-arrow-right"></li>
                    <li>Exercise Update</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                {{--                <button class="btn btn_primary uppercase" data-toggle="modal" data-target="#addExercise">Add New</button>--}}
            </div>
        </section>

        {{-- Add Modal --}}
        <form action="{{ route('admin.exercise.update', $exercise->id) }}" method="POST" enctype="multipart/form-data" id="updateExercise">
            @csrf
            <div class="card p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $exercise->name }}">

                    </div>

                    <div>
                        <label class="label">Equipment <span class="text-red-500">*</span></label>
                        <select class="form-control select2" name="equipment_id" style="width: 100%">
                            <option value="">Select Equipment</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}" {{ $exercise->equipment_id == $equipment->id ? 'selected' : '' }}>
                                    {{ $equipment->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Muscle <span class="text-red-500">*</span></label>
                        <select class="form-control select2" name="muscles_id"  style="width: 100%">
                            <option value="">Select Muscle</option>
                            @foreach($muscles as $muscle)
                                <option value="{{ $muscle->id }}" {{ $exercise->muscles_id == $muscle->id ? 'selected' : '' }}>
                                    {{ $muscle->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">Exercise Type <span class="text-red-500">*</span></label>
                        <select class="form-control select2" name="exercise_types_id"
                                style="width: 100%">
                            <option value="">Select Exercise Type</option>
                            @foreach($exerciseTypes as $type)
                                @php
                                    $unitIds = json_decode($type->unit_ids ?? '[]');
                                    $typeUnits = \App\Models\ExerciseUnit::whereIn('id', $unitIds)->pluck('unit')->toArray();
                                @endphp
                                <option value="{{ $type->id }}" {{ $exercise->exercise_types_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                    @if($type->example) - {{ $type->example }} @endif
                                    @if(!empty($typeUnits)) - ({{ implode(', ', $typeUnits) }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">Muscle Groups</label>
                        <select class="form-control select2Multiple" name="muscles_group_ids[]" multiple
                                id="muscleGroupIds" style="width: 100%">
                            @php
                                $selectedMuscleGroupIds = json_decode($exercise->muscles_group_ids ?? '[]');
                            @endphp
                            @foreach($muscleGroups as $group)
                                <option value="{{ $group->id }}" {{ in_array((string)$group->id, $selectedMuscleGroupIds) ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">Image</label>
{{--                        <input type="file" class="form-control" name="image" id="imageInput" accept="image/*,video/*"--}}
{{--                               required onchange="previewAddMedia()">--}}
                        <input type="file" name="image" id="updateImageInput" onchange="previewUpdateMedia()" accept="image/*,video/*" class="form-control">

                    @if($exercise->image)
                            <div class="mt-2" id="updateMediaPreviewContainer">
                                {{-- Default Preview from $exercise --}}
                                @php
                                    $extension = pathinfo($exercise->image, PATHINFO_EXTENSION);

//                                    dd($extension);
                                @endphp

                                @if($exercise->image)
                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp','jfif']))
                                        <img src="{{ $exercise->image }}" alt="Current Image"
                                             class="w-50 h-50 object-cover rounded"
                                             id="defaultImagePreview">
                                    @elseif(in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                                        <video class="w-50 h-50 object-cover rounded"
                                               id="defaultVideoPreview"
                                               controls>
                                            <source src="{{ $exercise->image }}" type="video/{{ $extension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1" id="defaultMediaText">Current Media</p>
                                @endif

                                {{-- New Media Preview (Initially Hidden) --}}
                                <img src="" alt="Preview Image"
                                     class="w-50 h-50 object-cover rounded hidden"
                                     id="updateImagePreview">

                                <video class="w-50 h-50 object-cover rounded hidden"
                                       id="updateVideoPreview" controls>
                                    <source id="updateVideoSource" src="" type="">
                                    Your browser does not support the video tag.
                                </video>

                                <p class="text-sm text-gray-600 mt-1 hidden" id="updateMediaPreviewText">New Media Preview</p>
                            </div>

                        @endif

                    </div>
                </div>
            </div>
            <div class="card mt-5 p-5">
                <h3>Update Exercise</h3>
                <button class="mt-5 btn btn_outlined btn_secondary uppercase" type="submit">Submit</button>
            </div>
        </form>


        @include('backend.layouts.footer')
    </main>
@endsection

@section('script')

    <script>
        // Initialize Select2
        $('.select2Multiple').select2({
            minimumInputLength: 0,
            allowClear: true,
            multiple: true,
            placeholder: function () {
                $(this).data('placeholder');
            }
        });
    </script>
    <script>
        function previewUpdateMedia() {
            const input = document.getElementById('updateImageInput');
            const previewImage = document.getElementById('updateImagePreview');
            const previewVideo = document.getElementById('updateVideoPreview');
            const previewVideoSource = document.getElementById('updateVideoSource');
            const previewText = document.getElementById('updateMediaPreviewText');

            const defaultImage = document.getElementById('defaultImagePreview');
            const defaultVideo = document.getElementById('defaultVideoPreview');
            const defaultText = document.getElementById('defaultMediaText');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileType = file.type;
                const reader = new FileReader();

                // Hide old preview
                if (defaultImage) defaultImage.classList.add('hidden');
                if (defaultVideo) defaultVideo.classList.add('hidden');
                if (defaultText) defaultText.classList.add('hidden');

                if (fileType.startsWith('image/')) {
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('hidden');

                        previewVideo.classList.add('hidden');
                        previewText.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else if (fileType.startsWith('video/')) {
                    const videoURL = URL.createObjectURL(file);
                    previewVideoSource.src = videoURL;
                    previewVideoSource.type = fileType;
                    previewVideo.load();

                    previewVideo.classList.remove('hidden');
                    previewImage.classList.add('hidden');
                    previewText.classList.remove('hidden');
                } else {
                    // Unsupported
                    previewImage.classList.add('hidden');
                    previewVideo.classList.add('hidden');
                    previewText.classList.add('hidden');
                }
            }
        }
    </script>




    <script src="{{asset('js-validation/jquery.min.js')}}"></script>
    <script src="{{asset('js-validation/bootstrap.min.js')}}"></script>

    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\web\v1\admin\exercise\UpdateExerciseRequest', '#updateExercise'); !!}
@endsection
