@extends('backend.layouts.master')
@section('title','Support Tickets')
@section('content')

    <!-- Workspace -->

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Support Tickets</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="#">Support Tickets</a></li>
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
                        <th class="text-center uppercase">Ticket Id#</th>
                        <th class="text-center uppercase">User</th>
                        <th class="text-center uppercase">Subject</th>
                        <th class="text-center uppercase">priority</th>
                        <th class="text-center uppercase">status</th>
                        <th class="text-center uppercase">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $index => $ticket)
                        <tr>
                            <td>{{++$index}}</td>
                            <td > {{$ticket->ticket_id}}</td>
                            <td >
                                {{$ticket->user->name}}
                                <br>
                                {{$ticket->user->email}}
                            </td>
                            <td >{{$ticket->subject}}</td>
                            <td >
                                @if($ticket->priority == 'high')
                                    <span class="badge badge_danger">High</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="badge badge_warning">Medium</span>
                                @elseif($ticket->priority == 'low')
                                    <span class="badge badge_success">Low</span>
                                @endif
                            </td>
                            <td >
                                @if($ticket->status == 'open')
                                    <span class="badge badge_success">Open</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="badge badge_danger">Closed</span>
                                @elseif($ticket->status == 'pending')
                                    <span class="badge badge_warning">Pending</span>
                                @endif
                            </td>

                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                     <span class="ml-2">
                                        <button class="btn btn-icon btn_outlined btn_primary" type="button" data-toggle="modal" data-target="#replyModalScrollable{{$ticket->id}}" data-toggle="tooltip" data-placement="left" title="Detail"><span class="la la-share"></span></button>
                                    </span>

                                    <span class="ml-2">
                                        <button class="btn btn-icon btn_outlined btn_info" type="button" data-toggle="modal" data-target="#exampleModalScrollable{{$ticket->id}}" data-toggle="tooltip" data-placement="left" title="Detail"><span class="la la-eye"></span></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <!-- Scrollable -->
                        <div id="exampleModalScrollable{{$ticket->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_scrollable max-w-2xl">
                                <form class="mt-5" action="#" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Ticket Detail</h2><br>
                                            <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-transparent">To select a coach which coach train to the trainee for the best practice to achieve our goal.</p>


                                            <!-- Subject & Priority in one row -->
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <!-- Ticket Subject -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Ticket #:</label>
                                                    <p class="text-gray-800">{{ $ticket->ticket_id }}</p>
                                                </div>
                                                <!-- Ticket Subject -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Subject:</label>
                                                    <p class="text-gray-800">{{ $ticket->subject }}</p>
                                                </div>

                                                <!-- Priority -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Priority:</label>
                                                    <p>
                                                        @if($ticket->priority == 'high')
                                                            <span class="badge badge_danger">High</span>
                                                        @elseif($ticket->priority == 'medium')
                                                            <span class="badge badge_warning">Medium</span>
                                                        @elseif($ticket->priority == 'low')
                                                            <span class="badge badge_success">Low</span>
                                                        @endif
                                                    </p>
                                                </div>

                                                <!-- Priority -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Status:</label>
                                                    <p>
                                                        @if($ticket->status == 'open')
                                                            <span class="badge badge_success">Open</span>
                                                        @elseif($ticket->status == 'closed')
                                                            <span class="badge badge_danger">Closed</span>
                                                        @elseif($ticket->status == 'pending')
                                                            <span class="badge badge_warning">Pending</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Separator line -->
                                            <hr class="my-4">

                                            <div class="mb-5">
                                                <label for="commission" class="font-semibold text-gray-800 mt-3">User Query:</label>
                                                <textarea type="text" rows="5" class="form-control" placeholder="Enter Reason" disabled>{{ $ticket->description ?? '' }}</textarea>
                                            </div>
                                            <!-- Separator line -->
                                            <hr class="my-4">
                                            <!-- Admin Reply -->
                                            <div>
                                                <label class="font-semibold text-gray-700">Admin Reply:</label>
                                                @if($ticket->reply)
                                                    <div class="p-3 bg-green-50 border rounded text-gray-800">
                                                        {{ $ticket->reply }}
                                                    </div>
                                                @else
                                                    <p class="italic text-sm text-gray-500">No reply from admin yet.</p>
                                                @endif
                                            </div>


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

                        <!-- Scrollable -->
                        <div id="replyModalScrollable{{$ticket->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_scrollable max-w-2xl">
                                <form class="mt-5" action="{{route('admin.tickets.reply', $ticket->id)}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="modal-title">Ticket Detail</h2><br>
                                            <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-transparent">To select a coach which coach train to the trainee for the best practice to achieve our goal.</p>


                                            <!-- Subject & Priority in one row -->
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <!-- Ticket Subject -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Ticket #:</label>
                                                    <p class="text-gray-800">{{ $ticket->ticket_id }}</p>
                                                </div>
                                                <!-- Ticket Subject -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Subject:</label>
                                                    <p class="text-gray-800">{{ $ticket->subject }}</p>
                                                </div>

                                                <!-- Priority -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Priority:</label>
                                                    <p>
                                                        @if($ticket->priority == 'high')
                                                            <span class="badge badge_danger">High</span>
                                                        @elseif($ticket->priority == 'medium')
                                                            <span class="badge badge_warning">Medium</span>
                                                        @elseif($ticket->priority == 'low')
                                                            <span class="badge badge_success">Low</span>
                                                        @endif
                                                    </p>
                                                </div>

                                                <!-- Priority -->
                                                <div>
                                                    <label class="font-semibold text-gray-700">Status:</label>
                                                    <p>
                                                        @if($ticket->status == 'open')
                                                            <span class="badge badge_success">Open</span>
                                                        @elseif($ticket->status == 'closed')
                                                            <span class="badge badge_danger">Closed</span>
                                                        @elseif($ticket->status == 'pending')
                                                            <span class="badge badge_warning">Pending</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Separator line -->
                                            <hr class="my-4">

                                            <div class="mb-5">
                                                <label for="commission" class="font-semibold text-gray-800 mt-3">User Query:</label>
                                                <textarea type="text" rows="5" class="form-control" placeholder="Enter Reason" disabled>{{ $ticket->description ?? '' }}</textarea>
                                            </div>
                                            <!-- Separator line -->
                                            <hr class="my-4">


                                            <!-- Custom Select -->

                                            <div class="custom-select">
                                                <label for="status" class="font-semibold text-gray-800 mt-3">Status:</label>
                                                <select name="status" class="form-control">
                                                    <option value="open" @if($ticket->status == 'open') selected @endif>Open</option>
                                                    <option value="closed" @if($ticket->status == 'closed') selected @endif>Closed</option>
                                                    <option value="pending" @if($ticket->status == 'pending') selected @endif>Pending</option>
                                                </select>
                                                <div class="custom-select-icon la la-caret-down"></div>
                                            </div>

                                            <!-- Admin Reply -->
                                            <div class="mt-3">
                                                <label class="font-semibold text-gray-700 ">Admin Reply:</label>
                                                <textarea type="text" rows="5" name="reply" class="form-control" placeholder="Enter Reply">{{ $ticket->reply ?? '' }}</textarea>
                                            </div>




                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto gap-2">
                                                <!-- Close Button -->
                                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn_primary uppercase">
                                                    Submit
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



