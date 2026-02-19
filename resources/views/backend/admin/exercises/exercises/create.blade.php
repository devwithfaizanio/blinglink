@extends('backend.layouts.master')
@section('title','Create Exercise')
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
                    <li>Exercise Create</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                {{--                <button class="btn btn_primary uppercase" data-toggle="modal" data-target="#addExercise">Add New</button>--}}
            </div>
        </section>

        {{-- Add Modal --}}
        <form action="{{ route('admin.exercise.store') }}" method="POST" enctype="multipart/form-data" id="addExercise">
            @csrf
            <div class="card p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Name <span class="text-red-500">*</span></label>
                        <input type="text" class="form-control" name="name">

                    </div>

                    <div>
                        <label class="label">Equipment <span class="text-red-500">*</span></label>
                        <select class="form-control select2" name="equipment_id" style="width: 100%">
                            <option value="">Select Equipment</option>
                            @foreach($equipments as $equipment)
                                <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label">Muscle <span class="text-red-500">*</span></label>
                        <select class="form-control select2" name="muscles_id"  style="width: 100%">
                            <option value="">Select Muscle</option>
                            @foreach($muscles as $muscle)
                                <option value="{{ $muscle->id }}">{{ $muscle->name }}</option>
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
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                    @if($type->example)
                                        - {{ $type->example }}
                                    @endif
                                    @if(!empty($typeUnits))
                                        - ({{ implode(', ', $typeUnits) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">Muscle Groups</label>
                        <select class="form-control select2Multiple" name="muscles_group_ids[]" multiple
                                id="muscleGroupIds" style="width: 100%">
                            @foreach($muscleGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">Image</label>
                        <input type="file" class="form-control" name="image" id="imageInput" accept="image/*,video/*"
                               required onchange="previewAddMedia()">
                        <div class="mt-2" id="addImagePreviewContainer">
                            <img src="" alt="Image Preview" class="w-32 h-32 object-cover rounded border hidden"
                                 id="addImagePreview">
                            <video class="w-32 h-32 rounded border hidden" id="addVideoPreview" controls></video>
                            <p class="text-sm text-gray-600 mt-1 hidden" id="addImagePreviewText">Preview</p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mt-5 p-5">
                <h3>Create Exercise</h3>
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
        function previewAddMedia() {
            const input = document.getElementById('imageInput');
            const previewImage = document.getElementById('addImagePreview');
            const previewVideo = document.getElementById('addVideoPreview');
            const previewText = document.getElementById('addImagePreviewText');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileType = file.type;

                const reader = new FileReader();

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
                    previewVideo.src = videoURL;
                    previewVideo.classList.remove('hidden');
                    previewImage.classList.add('hidden');
                    previewText.classList.remove('hidden');
                } else {
                    // Unsupported file type
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
    {!! JsValidator::formRequest('App\Http\Requests\web\v1\admin\exercise\CreateExerciseRequest', '#addExercise'); !!}
@endsection
