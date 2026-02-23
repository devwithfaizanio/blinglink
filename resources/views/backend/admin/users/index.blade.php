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
                        <th class="text-center uppercase">role</th>
                        <th class="text-center uppercase">is verify</th>
                        <th class="text-center uppercase">verify</th>
                        <th class="uppercase">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{++$index}}</td>
                            <td class="text-center"><img src="{{$user->profile_image}}" width="50px" height="50px" style="border-radius: 50%" onerror="{{asset('Avatars/2.png')}}"></td>
                            <td class="">{{$user->f_name}}<br>{{$user->email}}</td>
                            <td class=""> <span class="badge badge_primary">{{ ucfirst($user->role) }}</span></td>
                            <td class="uppercase">
                                <span class="badge {{ $user->is_approved == 0 ? 'badge_danger' : 'badge_primary' }}">
                                    {{ $user->is_approved == 0 ? 'Not Verified' : 'Verified' }}
                                </span>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox"
                                           class="verify-toggle"
                                           data-user-id="{{ $user->id }}"
                                        {{ $user->is_approved ? 'checked' : '' }}>
                                    <span></span>
                                </label>
                            </td>

                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <span class="ml-2">
                                        <button
                                            class="btn btn-icon btn_outlined btn_info"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#exampleModalScrollable{{$user->id}}"
                                            data-placement="left"
                                            title="View"
                                        >
                                            <span class="la la-eye"></span>
                                        </button>
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
                                        <h2 class="modal-title">Delete User</h2>
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
                                            <h2 class="modal-title">User Info
                                            </h2><br>
                                            <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-transparent">To select a coach which coach train to the trainee for the best practice to achieve our goal.</p>

                                            <div class="grid grid-cols-2 gap-4 text-sm">

                                                <div><strong>First Name:</strong> {{ $user->f_name ?? 'N/A' }}</div>
                                                <div><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</div>

                                                <div><strong>Role:</strong> {{ ucfirst($user->role) }}</div>
                                                <div><strong>Age:</strong> {{ $user->age ?? 'N/A' }}</div>

                                                <div><strong>Gender:</strong> {{ $user->gender ?? 'N/A' }}</div>
                                                <div><strong>Nationality:</strong> {{ $user->nationality ?? 'N/A' }}</div>

                                                <div><strong>Profession:</strong> {{ $user->profession ?? 'N/A' }}</div>
                                                <div><strong>Company:</strong> {{ $user->company ?? 'N/A' }}</div>

                                                <div><strong>Dubai Location:</strong> {{ $user->dubai_location ?? 'N/A' }}</div>
                                                <div><strong>Height:</strong> {{ $user->height ?? 'N/A' }}</div>

                                                <div><strong>Education Level:</strong> {{ $user->education_level ?? 'N/A' }}</div>
                                                <div><strong>Family:</strong> {{ $user->family ?? 'N/A' }}</div>

                                                <div><strong>Languages:</strong> {{ $user->languages ?? 'N/A' }}</div>

                                                <div>
                                                    <strong>LinkedIn:</strong>
                                                    @if($user->linkedin_profile)
                                                        <a href="{{ $user->linkedin_profile }}" target="_blank" class="text-blue-600 underline">
                                                            View Profile
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>



                                                <div>
                                                    <strong>Status:</strong>
                                                    <span class="badge {{ $user->is_approved ? 'badge_success' : 'badge_danger' }}">
                {{ $user->is_approved ? 'Verified' : 'Not Verified' }}
            </span>
                                                </div>

                                            </div>

                                            {{-- Lifestyle Preference --}}
                                            <div class="mt-4">
                                                <strong>Lifestyle Preferences:</strong>
                                                <p class="mt-1">
                                                    {{ $user->lifestyle_preference_text }}
                                                </p>
                                            </div>

                                            {{-- Interests --}}
                                            <div class="mt-3">
                                                <strong>Your Interests:</strong>
                                                <p class="mt-1">
                                                    {{ $user->interest_text }}
                                                </p>
                                            </div>

                                            {{-- Bio --}}
                                            <div class="mt-3">
                                                <strong>Bio:</strong>
                                                <p class="mt-1">{{ $user->bio ?? 'N/A' }}</p>
                                            </div>

                                            {{-- Images Row --}}
                                            @if($user->emirate_id || $user->profile_image)
                                                <div class="mt-4">
                                                    <strong>Documents:</strong>

                                                    <div class="mt-2 flex items-center gap-6">

                                                        {{-- Emirate ID --}}
                                                        @if($user->emirate_id)
                                                            <div class="flex flex-col items-center">
                                                                <span class="text-xs mb-1">Emirate ID</span>
                                                                <img src="{{$user->emirate_id }}"
                                                                     class="w-24 h-24 rounded-lg object-cover border">
                                                            </div>
                                                        @endif

                                                        {{-- Profile Image --}}
                                                        @if($user->profile_image)
                                                            <div class="flex flex-col items-center">
                                                                <span class="text-xs mb-1">Profile Image</span>
                                                                <img src="{{ $user->profile_image }}"
                                                                     class="w-24 h-24 rounded-lg object-cover border">
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endif

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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.verify-toggle').forEach(function (checkbox) {

                checkbox.addEventListener('change', function () {

                    let userId = this.dataset.userId;
                    let isChecked = this.checked;

                    console.log('Toggling user ID:', userId, 'New Status:', isChecked);

                    fetch("{{ route('admin.users.toggle-status') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: userId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert('Something went wrong!');
                                this.checked = !isChecked; // revert toggle
                            }
                        })
                        .catch(error => {
                            alert('Error updating status');
                            this.checked = !isChecked; // revert toggle
                        });

                });

            });

        });
    </script>
@endsection



