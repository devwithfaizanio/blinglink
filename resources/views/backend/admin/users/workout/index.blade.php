@extends('backend.layouts.master')
@section('title','Workout History')
@section('content')

    <!-- Workspace -->

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Users</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin.users')}}">Users</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="#">Workout History</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>

            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <!-- Add New -->
{{--                    <a href="{{route('admin_create_team')}}">--}}
{{--                        <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2">Add New Member</button>--}}
{{--                    </a>--}}
                </div>
            </div>
        </section>

        <!-- List -->

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full " id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="text-center uppercase">Workout Name</th>
                        <th class="text-center uppercase">Status</th>
                        <th class="uppercase">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workoutsHistory as $index => $work)
                        <tr>
                            <td>{{++$index}}</td>
                            <td class="">{{$work->workout->name}}</td>
                            <td class="text-center">
                                @if($work->status == 'in_progress')
                                    <span class="badge badge_outlined badge_warning ltr:mr-2 rtl:ml-2 mt-2">In Progress</span>
                                @else
                                    <span class="badge badge_outlined badge_success ltr:mr-2 rtl:ml-2 mt-2">Completed</span>
                                @endif
                            </td>


                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <span class="ml-2">
                                        <button class="badge badge_outlined badge_info ltr:mr-2 rtl:ml-2 mt-2" data-toggle="modal" data-target="#exampleModalScrollable{{$work->id}}">Detail</button>
                                    </span>
                                </div>
                            </td>
                        </tr>






                        <!-- Scrollable -->
                        <div id="exampleModalScrollable{{$work->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_scrollable max-w-2xl">
                                <form class="mt-5" action="#" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Workout Detail
                                                <span>
                                                    @if($work->status == 'in_progress')
                                                        <span class="badge badge_outlined badge_warning ltr:mr-2 rtl:ml-2 mt-2">In Progress</span>
                                                    @else
                                                        <span class="badge badge_outlined badge_success ltr:mr-2 rtl:ml-2 mt-2">Completed</span>
                                                    @endif
                                                </span>
                                            </h2><br>
                                            <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-transparent">To select a coach which coach train to the trainee for the best practice to achieve our goal.</p>
                                            <center>
                                                <h5 class="text-lg font-semibold mb-2">Workout Name: <span class="text-gray-600">{{ $work->workout->name }}</span></h5>
                                            </center>

                                            @php
                                                $data = json_decode($work->data, true);

                                                $totalDuration = 0;
                                                $totalSets = 0;
                                                $completedSets = 0;

                                                foreach ($data['exercises'] as $exerciseItem) {
                                                    $exerciseModel = \App\Models\Exercise::find($exerciseItem['id']);
                                                    if ($exerciseModel) {
                                                        $totalDuration += (float) $exerciseModel->time_duration;
                                                    }

                                                    foreach ($exerciseItem['sets_detail'] as $set) {
                                                        $totalSets++;
                                                        if (!empty($set['is_completed'])) {
                                                            $completedSets++;
                                                        }
                                                    }
                                                }

                                                $remainingPercentage = $totalSets > 0 ? round(100 - ($completedSets / $totalSets * 100), 2) : 100;
                                            @endphp

                                                <!-- Workout Stats -->
                                            <div class="grid grid-cols-3 gap-4 my-4 text-sm text-gray-700">
                                                <div>
                                                    <span class="font-semibold">Total Duration:</span>
                                                    <span>{{ gmdate('H:i:s', $totalDuration) }}</span> <!-- convert seconds to HH:MM:SS -->
                                                </div>
                                                <div>
                                                    <span class="font-semibold">Remaining %:</span>
                                                    <span>{{ $remainingPercentage }}%</span>
                                                </div>
                                                <div>
                                                    <span class="font-semibold">Spent Duration :</span>
                                                    <span>{{ $work->time ?? '00:00:00' }}</span>
                                                </div>
                                            </div>
                                                @foreach ($data['exercises'] as $exercise)
                                                    <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                                                        <h3 class="font-semibold text-lg mb-3">{{ $exercise['title'] }}</h3>

                                                        <div class="grid grid-cols-12 gap-2 font-semibold text-gray-600 mb-2">
                                                            <div class="col-span-2">Set</div>
                                                            <div class="col-span-4">Kg</div>
                                                            <div class="col-span-4">Reps</div>
                                                            <div class="col-span-2">Done</div>
                                                        </div>

                                                        @foreach ($exercise['sets_detail'] as $index => $set)
                                                            <div class="grid grid-cols-12 gap-2 items-center mb-1">
                                                                <div class="col-span-2">{{ $index + 1 }}</div>
                                                                <div class="col-span-4">
                                                                    <input type="text" class="form-control" value="{{ $set['kg'] ?? '' }}" readonly />
                                                                </div>
                                                                <div class="col-span-4">
                                                                    <input type="text" class="form-control" value="{{ $set['reps'] ?? '' }}" readonly />
                                                                </div>
                                                                <div class="col-span-2 flex justify-center">
                                                                    <input type="checkbox" disabled {{ $set['is_completed'] ? 'checked' : '' }} />
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                <!-- Separator line -->
                                                <hr class="my-4">
                                                @endforeach

                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto gap-2">
                                                <!-- Close Button -->
                                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>


                                    </div>
                                </form>
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



