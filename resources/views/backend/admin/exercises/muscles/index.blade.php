@extends('backend.layouts.master')
@section('title','Muscles')
@section('content')

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')

        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Muscles</h1>
                <ul>
                    <li><a href="{{ route('admin_dashboard') }}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{ route('admin.exercise.muscles') }}">Muscles</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2" data-toggle="modal"
                            data-target="#addMuscle">Add New</button>
                </div>
            </div>
        </section>

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full" id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="text-center uppercase">Image</th>
                        <th class="text-center uppercase">Muscle Name</th>
                        <th class="uppercase ltr:text-left rtl:text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($muscles as $index => $muscle)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td><img src="{{ $muscle->image }}" alt="" width="50px" height="50px" class="rounded-full"></td>
                            <td>{{ $muscle->name }}</td>
                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                <span>
                                    <button class="btn btn-icon btn_outlined btn_secondary" type="button"
                                            data-toggle="modal" data-target="#update{{ $muscle->id }}">
                                        <span class="la la-pen-fancy"></span>
                                    </button>
                                </span>
                                    <span class="ml-2">
                                    <button class="btn btn-icon btn_outlined btn_danger" type="button"
                                            data-toggle="modal" data-target="#delete{{ $muscle->id }}">
                                        <span class="la la-trash-alt"></span>
                                    </button>
                                </span>
                                </div>
                            </td>
                        </tr>

                        {{-- Delete Modal --}}
                        <div id="delete{{ $muscle->id }}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Muscle</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete this muscle? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{ route('admin.exercise.muscles.delete', $muscle->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Update Modal --}}
                        <div id="update{{ $muscle->id }}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Update Muscle</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <center>
                                        <span class="w-10 h-10 rounded-full">
                                            <img src="{{ $muscle->image }}" id="outputEdit{{ $muscle->id }}"
                                                 style="height: 200px; width: 200px; border-radius: 100%">
                                        </span>
                                    </center>
                                    <form id="muscleFormEdit{{ $muscle->id }}"
                                          action="{{ route('admin.exercise.muscles.update', $muscle->id) }}"
                                          method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-transparent">
                                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                            </p>
                                            <h3>Muscle</h3>
                                            <div class="relative mt-5">
                                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Name</label>
                                                <input type="text" class="form-control mt-2 pt-5" name="name" value="{{ $muscle->name }}" id="muscleNameEdit{{ $muscle->id }}">
                                                <span id="nameErrorEdit{{ $muscle->id }}" class="text-danger" style="color: #9c1919"></span>
                                            </div>
                                            <div class="relative mt-5">
                                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Media</label>
                                                <input type="file" onchange="loadFileEdit(event, 'outputEdit{{ $muscle->id }}')" class="form-control mt-2 pt-5" name="image">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Modal --}}
        <div id="addMuscle" class="modal" data-animations="fadeInDown, fadeOutUp">
            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Add Muscle</h2>
                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                    </div>
                    <center>
                    <span class="w-10 h-10 rounded-full">
                        <img src="" id="outputAdd" style="display: none; height: 200px; width: 200px; border-radius: 100%">
                    </span>
                    </center>
                    <form id="muscleForm" action="{{ route('admin.exercise.muscles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p class="text-transparent">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            </p>
                            <h3>Muscle</h3>
                            <div class="relative mt-5">
                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Name</label>
                                <input type="text" class="form-control mt-2 mb-1 pt-5" name="name" id="muscleName">
                                <span id="nameError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                            <div class="relative mt-5">
                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Media</label>
                                <input type="file" onchange="loadFile(event)" class="form-control mt-2 mb-1 pt-5" name="image" id="muscleImage">
                                <span id="imageError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Add Muscle</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('backend.layouts.footer')
    </main>
@endsection

@section('script')
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('outputAdd');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
                output.style.display = '';
            }
        };

        var loadFileEdit = function(event, outputId) {
            var output = document.getElementById(outputId);
            if (output) {
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src);
                }
            }
        };

        document.getElementById('muscleForm').addEventListener('submit', function(event) {
            var name = document.getElementById('muscleName').value;
            var image = document.getElementById('muscleImage').files.length;

            var nameError = document.getElementById('nameError');
            var imageError = document.getElementById('imageError');

            nameError.textContent = '';
            imageError.textContent = '';

            if (!name) {
                event.preventDefault();
                nameError.textContent = 'Name is required.';
            }

            if (!image) {
                event.preventDefault();
                imageError.textContent = 'Image is required.';
            }
        });
    </script>
@endsection
