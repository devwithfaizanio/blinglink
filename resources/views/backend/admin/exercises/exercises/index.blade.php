@extends('backend.layouts.master')
@section('title','Exercise')
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
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <a href="{{route('admin.exercise.create')}}">
                    <button class="btn btn_primary uppercase">Add New</button>
                </a>
            </div>
        </section>

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full" id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Name</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Equipment</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Muscles</th>
{{--                        <th class="ltr:text-left rtl:text-right uppercase">Image</th>--}}
                        <th class="ltr:text-left rtl:text-right uppercase">Muscle Groups</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Exercise Type</th>
                        <th class="ltr:text-left rtl:text-right uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($exercises as $index => $exercise)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $exercise->name }}</td>
                            <td>
                                @if($exercise->equipment_id)
                                    <span class="badge badge_info">{{ $exercise->equipment->name }}</span>
                                @else
                                    <span class="text-muted">No Equipment</span>
                                @endif
                            </td>
                            <td>
                                @if($exercise->muscle)
                                    <span class="badge badge_success">{{ $exercise->muscle->name }}</span>
                                @else
                                    <span class="text-muted">No Muscle</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $selectedMuscleGroupIds = json_decode($exercise->muscles_group_ids ?? '[]');
                                @endphp
                                @if($muscleGroups && count($selectedMuscleGroupIds) > 0)
                                    @foreach($muscleGroups as $group)
                                        @if(in_array((string)$group->id, $selectedMuscleGroupIds))
                                            <span class="badge badge_warning">{{ $group->name }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="text-muted">No Groups</span>
                                @endif
                            </td>
                            <td>
                                @if($exercise->exerciseType)
                                    @php
                                        $unitIds = json_decode($exercise->exerciseType->unit_ids ?? '[]');
                                        $typeUnits = \App\Models\ExerciseUnit::whereIn('id', $unitIds)->pluck('unit')->toArray();
                                    @endphp

                                    <span class="badge badge_primary">
                                        {{ $exercise->exerciseType->name }}
                                    @if($exercise->exerciseType->example) - {{ $exercise->exerciseType->example }} @endif
                                        @if(!empty($typeUnits)) - ({{ implode(', ', $typeUnits) }}) @endif
                                    </span>
                                @else
                                    <span class="text-muted">No Type</span>
                                @endif
                            </td>

                            <td>
                                <div class="inline-flex">
                                    <a href="{{route('admin.exercise.edit',$exercise->id)}}">
                                        <button class="btn btn-icon btn_outlined btn_secondary" >
                                            <span class="la la-pen-fancy"></span>
                                        </button>
                                    </a>
                                    <button class="btn btn-icon btn_outlined btn_danger ml-2" data-toggle="modal" data-target="#delete{{ $exercise->id }}">
                                        <span class="la la-trash-alt"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Delete Modal --}}
                        <div id="delete{{ $exercise->id }}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Exercise</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete this exercise? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.exercise.delete', $exercise->id) }}" method="POST">
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

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


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
            placeholder: function(){
                $(this).data('placeholder');
            }
        });

    </script>
@endsection
