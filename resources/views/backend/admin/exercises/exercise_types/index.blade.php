@extends('backend.layouts.master')
@section('title','Exercise Types')
@section('content')

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')

        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Exercise Types</h1>
                <ul>
                    <li><a href="{{ route('admin_dashboard') }}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>Exercise Types</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <button class="btn btn_primary uppercase" data-toggle="modal" data-target="#addMuscle">Add New</button>
            </div>
        </section>

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full" id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Name</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Example</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Units</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($exerciseTypes as $index => $muscle)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $muscle->name }}</td>
                            <td>{{ $muscle->example }}</td>
                            @php
                                $selectedUnitIds = json_decode($muscle->unit_ids ?? '[]');
                            @endphp

                            <td>
                                @foreach($units as $unit)
                                    @if(in_array((string)$unit->id, $selectedUnitIds))
                                        <span class="badge badge_success">{{ $unit->unit }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="inline-flex">
                                    <button class="btn btn-icon btn_outlined btn_secondary" data-toggle="modal" data-target="#update{{ $muscle->id }}">
                                        <span class="la la-pen-fancy"></span>
                                    </button>
                                    <button class="btn btn-icon btn_outlined btn_danger ml-2" data-toggle="modal" data-target="#delete{{ $muscle->id }}">
                                        <span class="la la-trash-alt"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Delete Modal --}}
                        <div id="delete{{ $muscle->id }}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Exercise Type</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete this exercise type? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.exercise.exercise_type.delete', $muscle->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                                <button type="button" class="btn btn_secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn_danger ml-2">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Update Modal --}}
                        <div id="update{{ $muscle->id }}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Edit Exercise Type</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.exercise.exercise_type.update', $muscle->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-transparent">
                                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                            </p>
                                            <div class="mt-4">
                                                <label class="label">Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $muscle->name }}">
                                            </div>
                                            <div class="mt-4">
                                                <label class="label">Example</label>
                                                <textarea class="form-control" name="example">{{ $muscle->example }}</textarea>
                                            </div>
                                            <div class="mt-4">
                                                <label class="label">Units</label>
                                                <select class="form-control select2" name="unit_ids[]" multiple style="width: 100%">
                                                    @php
                                                        $selectedUnitIds = json_decode($muscle->unit_ids ?? '[]');
                                                    @endphp

                                                    @foreach($units as $unit)
                                                        @if(in_array((string)$unit->id, $selectedUnitIds))
                                                            <option value="{{ $unit->id }}" selected>{{ $unit->unit }}</option>
                                                        @else
                                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                        @endif
                                                    @endforeach


                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                                <button type="button" class="btn btn_secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn_primary ml-2">Update</button>
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
            <div class="modal-dialog max-w-2xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Add Exercise Type</h2>
                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                    </div>
                    <form id="muscleForm" action="{{ route('admin.exercise.exercise_type.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-transparent">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            </p>
                            <div class="mt-4">
                                <label class="label">Name</label>
                                <input type="text" class="form-control" name="name" id="muscleName">
                                <span id="nameError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                            <div class="mt-4">
                                <label class="label">Example</label>
                                <textarea class="form-control" name="example" id="exampleText"></textarea>
                                <span id="exampleError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                            <div class="mt-4">
                                <label class="label">Units</label>
                                <select class="form-control select2" name="unit_ids[]" multiple id="unitIds" style="width: 100%">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                                <span id="unitError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                <button type="button" class="btn btn_secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn_primary ml-2">Add</button>
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
        $('.select2').select2({
            minimumInputLength: 0,
            allowClear: true,
            multiple: true,
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
    </script>


    <script>

        document.getElementById('muscleForm').addEventListener('submit', function(event) {
            let name = document.getElementById('muscleName').value.trim();
            let example = document.getElementById('exampleText').value.trim();
            let unitIds = document.getElementById('unitIds').selectedOptions.length;

            document.getElementById('nameError').textContent = '';
            document.getElementById('exampleError').textContent = '';
            document.getElementById('unitError').textContent = '';

            let hasError = false;

            if (!name) {
                document.getElementById('nameError').textContent = 'Name is required.';
                hasError = true;
            }

            if (!example) {
                document.getElementById('exampleError').textContent = 'Example is required.';
                hasError = true;
            }

            if (unitIds === 0) {
                document.getElementById('unitError').textContent = 'Please select at least one unit.';
                hasError = true;
            }

            if (hasError) {
                event.preventDefault();
            }
        });



    </script>
@endsection
