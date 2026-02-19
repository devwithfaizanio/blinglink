@extends('backend.layouts.master')
@section('title','Users')
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
                    <li><a href="#">Users</a></li>
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
                        <th class="text-center uppercase">image</th>
                        <th class="text-center uppercase">name</th>
                        <th class="text-center uppercase">Reminder Notification</th>
                        <th class="text-center uppercase">Update Notification</th>
                        <th class="uppercase">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{++$index}}</td>
                            <td class="text-center"><img src="{{$user->image}}" width="50px" height="50px" style="border-radius: 50%" onerror="{{asset('Avatars/2.png')}}"> </td>
                            <td class="">{{$user->name}} <span class="font-bold">({{$user->username}})</span><br>{{$user->email}}</td>
                            <td class="">
                                <label class="switch">
                                    <input type="checkbox"
                                           @if($user->reminder_notification == 1) checked @endif disabled>
                                    <span></span>
                                </label>
                            </td>
                            <td class=""><label class="switch">
                                    <input type="checkbox"
                                           @if($user->update_notification == 1) checked @endif disabled>
                                    <span></span>
                                </label>
                            </td>


                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <span class="ml-2">
                                        <button class="badge badge_outlined badge_info ltr:mr-2 rtl:ml-2 mt-2" data-toggle="modal" data-target="#exampleModalScrollable{{$user->id}}">Measurement</button>
                                    </span>
                                    <span class="ml-2">
                                        <a href="{{route('admin_recipes.user.diets',$user->id)}}">
                                            <button class="badge badge_outlined badge_info ltr:mr-2 rtl:ml-2 mt-2">Recipe</button>
                                        </a>
                                    </span>
                                    <span class="ml-2">
                                        <a href="{{route('admin.users.fasting',$user->id)}}">
                                            <button class="badge badge_outlined badge_info ltr:mr-2 rtl:ml-2 mt-2">Fasts</button>
                                        </a>
                                    </span>
                                    <span class="ml-2">
                                        <a href="{{route('admin.users.workHistory',$user->id)}}">
                                            <button class="badge badge_outlined badge_info ltr:mr-2 rtl:ml-2 mt-2">WorkOuts</button>
                                        </a>
                                    </span>
                                    <span class="ml-2">
                                        <button class="btn btn-icon btn_outlined btn_danger" type="button" data-toggle="modal" data-target="#delete{{$user->id}}" data-toggle="tooltip" data-placement="left" title="Delete"><span class="la la-trash-alt"></span></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        {{--Delete User Modal--}}
                        <div id="delete{{$user->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Agency</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete these records? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{route('admin.users.delete',$user->id)}}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Scrollable -->
                        <div id="exampleModalScrollable{{$user->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_scrollable max-w-2xl">
                                <form class="mt-5" action="#" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Today Measurement
                                            </h2><br>
                                            <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-transparent">To select a coach which coach train to the trainee for the best practice to achieve our goal.</p>

                                            @foreach($measurements as $measurement)

                                                @php
                                                    $todayMeasurement = null;
                                                    $logModel = $measurement->measurementLog;

                                                    if ($logModel && $logModel->user_id == $user->id && $logModel->measurement_log) {
                                                        $logs = json_decode($logModel->measurement_log, true);
                                                        $today = \Carbon\Carbon::now()->toDateString();

                                                        foreach ($logs as $log) {
                                                            $logDate = \Carbon\Carbon::parse($log['measurement_at'])->toDateString();
                                                            if ($logDate === $today) {
                                                                $todayMeasurement = $log;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                <a href="{{route('admin.users.measurements', [$measurement->id, $user->id])}}" >
                                                    <div class="px-6 py-3 flex flex-row items-center justify-between bg-gray-100 mb-2">
                                                        <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                            <span>{{$measurement->name}} ({{$measurement->unit}}) </span>
                                                        </span>
                                                            <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                            {{ $todayMeasurement ? $todayMeasurement['value'] . ' ' . $measurement->unit : 'No data for today' }}
                                                        </span>
                                                    </div>
                                                </a>
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



