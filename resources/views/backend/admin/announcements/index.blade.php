@extends('backend.layouts.master')
@section('title','Announcement')
@section('content')

    <!-- Workspace -->
    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Announcement</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin.announcement')}}">Announcement</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <!-- Add New -->
                    <a href="{{route('admin.announcement.create')}}">
                        <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2">Set New</button>
                    </a>
                </div>
            </div>
        </section>

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full " id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="text-center uppercase">Image</th>
                        <th class="text-center uppercase">Title</th>
                        <th class="text-center uppercase">body</th>
                        <th class="text-center uppercase">notification type</th>
                        <th class="uppercase ltr:text-left rtl:text-right">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($announcements as $index => $an)
                        <tr>
                            <td>{{++$index}}</td>
                            <td ><img src="{{$an->file}}" width="50px"> </td>
                            <td >{{$an->title}} </td>
                            <td >{{$an->body}} </td>
                            <td >
                                <span class="badge badge_outlined   @if($an->send_type == 'immediate') badge_success @else badge_primary @endif x uppercase ltr:mr-5 rtl:ml-5">
                                    {{$an->send_type}}
                                </span>
                            </td>
                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                <span class="mr-2">
                                    <button class="btn btn-icon btn_outlined btn_info" type="button" data-toggle="modal" data-target="#detailModel{{$an->id}}" data-toggle="tooltip" data-placement="left" title="Info"><span class="la la-eye"></span></button>
                                </span>
                                <span >
                                    <a href="{{route('admin.announcement.edit',$an->id)}}" >
                                        <button class="btn btn-icon btn_outlined btn_secondary" type="button" data-placement="left" title="Edit"><span class="la la-pen-fancy"></span></button>
                                    </a>
                                </span>
                                <span class="ml-2">
                                    <button class="btn btn-icon btn_outlined btn_danger" type="button" data-toggle="modal" data-target="#delete{{$an->id}}" data-toggle="tooltip" data-placement="left" title="Delete"><span class="la la-trash-alt"></span></button>
                                </span>
                                </div>
                            </td>
                        </tr>
                        {{--Delete Announcement Modal--}}
                        <div id="delete{{$an->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Announcement</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete these records? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{route('admin.announcement.delete',$an->id)}}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="detailModel{{$an->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Announcement</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-transparent">this is an Announcement modal in which we are showing the detail of the Announcement</p>
                                        <center>
                                            <img src="{{$an->file}}" style="border-radius: 5%; width: 50%" class="mb-5">
                                        </center>

                                        <h1
                                            class="mx-auto max-w-4xl font-display text-3xl font-bold tracking-normal text-black sm:text-7xl mb-3">
                                            Details of {{$an->title}} Announcement

                                        </h1>
                                        <div class="px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                            <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                <span>Title</span>
                                            </span>
                                            <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                {{$an->title}}
                                            </span>
                                        </div>

                                        <div class="mt-2 px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                            <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                <span>Body</span>
                                            </span>
                                            <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                {{$an->body}}
                                            </span>
                                        </div>

                                        <div class="mt-2 px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                            <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                <span>Notification Type</span>
                                            </span>
                                            <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                <span class="badge badge_outlined   @if($an->send_type == 'immediate') badge_success @else badge_primary @endif x uppercase">
                                                    {{$an->send_type}}
                                                </span>
                                            </span>
                                        </div>
                                        @if($an->send_type != 'immediate')
                                            <div class="mt-2 px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                            <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                <span>Schedule Time</span>
                                            </span>
                                                <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                    <span class="badge badge_outlined  badge_primary x uppercase">
                                                    {{ date('d-M-Y H:i:s', strtotime($an->scheduled_date_time)) }}
                                                </span>
                                                </span>
                                            </div>
                                        @endif
                                        <div class="mt-2 px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                            <span class="py-1 text-xs font-bold text-black mr-1 flex flex-row items-center">
                                                <span>Send to</span>
                                            </span>
                                            <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                 @if($an->type == 'all' )
                                                    <span class="badge badge_outlined badge_primary x uppercase">
                                                        All Users
                                                    </span>
                                                @else
                                                    <span class="badge badge_outlined badge_primary x uppercase">
                                                        Selected Users
                                                    </span>
                                                @endif

                                            </span>
                                        </div>


                                        @if($an->type == 'selected')
                                            <div class="mt-2 px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                                                <span  class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                                                    @foreach(json_decode($an->selected_user_list) as $a)
                                                        <span class="badge badge_outlined badge_success">
                                                             {{ App\Models\User::find($a)->name }} {{ App\Models\User::find($a)->surname }}
                                                        </span>
                                                    @endforeach
                                            </span>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                        </div>
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


