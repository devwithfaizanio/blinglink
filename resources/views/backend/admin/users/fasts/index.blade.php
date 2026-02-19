@extends('backend.layouts.master')
@section('title','Fasting History')
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
                    <li><a href="#">Fast History</a></li>
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
                        <th class="text-center uppercase">Fast Type</th>
                        <th class="text-center uppercase">Fast Duration</th>
                        <th class="text-center uppercase">Start At</th>
                        <th class="text-center uppercase">End At</th>
                        <th class="text-center uppercase">Remaining</th>
                        <th class="text-center uppercase">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fastingHistory as $index => $fast)
                        <tr>
                            <td>{{++$index}}</td>
                            <td class="">
                                @if($fast->fast->type == 'easiest')
                                    <span class="badge badge_outlined badge_success ltr:mr-2 rtl:ml-2 mt-2">Easiest</span>
                                @elseif($fast->fast->type == 'moderate')
                                    <span class="badge badge_outlined badge_warning ltr:mr-2 rtl:ml-2 mt-2">Moderate</span>
                                @else
                                    <span class="badge badge_outlined badge_danger ltr:mr-2 rtl:ml-2 mt-2">Challenging</span>
                                @endif

                            </td>
                            <td class="">{{$fast->fast->fasting_hour}} - {{$fast->fast->eating_hour}} <br>
                                {{$fast->fast->description}}
                            </td>
                            <td class="">{{$fast->fast_start_at}}</td>
                            <td class="">{{$fast->fast_end_at}}</td>
                            <td class="">{{$fast->remaining_duration}}</td>
                            <td class="text-center">
                                @if($fast->status == 'start')
                                    <span class="badge badge_outlined badge_warning ltr:mr-2 rtl:ml-2 mt-2">In Progress</span>
                                @else
                                    <span class="badge badge_outlined badge_success ltr:mr-2 rtl:ml-2 mt-2">Completed</span>
                                @endif
                            </td>


                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('backend.layouts.footer')
    </main>
@endsection



