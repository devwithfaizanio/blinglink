<header class="top-bar">

    <!-- Menu Toggler -->
    <button type="button" class="menu-toggler la la-bars" data-toggle="menu"></button>

    <!-- Brand -->
    <span class="brand">{{settingKey()['app_name']}}</span>

    <!-- Search -->
{{--    <form class="hidden md:block ltr:ml-10 rtl:mr-10" action="#">--}}
{{--        <label class="form-control-addon-within rounded-full">--}}
{{--            <input type="text" class="form-control border-none" placeholder="Search">--}}
{{--            <button type="button"--}}
{{--                    class="btn btn-link text-gray-300 dark:text-gray-700 hover:text-primary dark:hover:text-primary text-xl leading-none la la-search ltr:mr-4 rtl:ml-4"></button>--}}
{{--        </label>--}}
{{--    </form>--}}
    @if (Session::has('error'))
        <div class="alert alert_outlined alert_primary mt-5">
            <strong class="uppercase"><bdi>Warning!</bdi></strong>
            {{ Session::get('error') }}
            <button type="button" class="dismiss la la-times" data-dismiss="alert"></button>
        </div>
    @endif
    <!-- Right -->
    <div class="flex items-center ltr:ml-auto rtl:mr-auto">
{{--Home screen--}}
{{--        <a href="{{url('/')}}">--}}
{{--        <button class="btn btn_outlined btn_primary uppercase ltr:ml-auto rtl:mr-auto mr-2">Home</button>--}}
{{--        </a>--}}
        <!-- Dark Mode -->
        <label class="switch switch_outlined" data-toggle="tooltip" data-tippy-content="Toggle Dark Mode">
            <input id="darkModeToggler" type="checkbox">
            <span></span>
        </label>

        <!-- Fullscreen -->
        <button id="fullScreenToggler" type="button"
                class="hidden lg:inline-block btn-link ltr:ml-3 rtl:mr-3 px-2 text-2xl leading-none la la-expand-arrows-alt"
                data-toggle="tooltip" data-tippy-content="Fullscreen"></button>

        <!-- Apps -->
{{--        <div class="dropdown self-stretch">--}}
{{--            <button type="button"--}}
{{--                    class="flex items-center h-full btn-link ltr:ml-4 rtl:mr-4 lg:ltr:ml-1 lg:rtl:mr-1 px-2 text-2xl leading-none la la-box"--}}
{{--                    data-toggle="custom-dropdown-menu" data-tippy-arrow="true" data-tippy-placement="bottom">--}}
{{--            </button>--}}
{{--            <div class="custom-dropdown-menu p-5 text-center">--}}
{{--                <div class="flex justify-around">--}}
{{--                    <a href="#" class="p-5 text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">--}}
{{--                        <span class="block la la-cog text-5xl leading-none"></span>--}}
{{--                        <span>Settings</span>--}}
{{--                    </a>--}}
{{--                    <a href="#" class="p-5 text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">--}}
{{--                        <span class="block la la-users text-5xl leading-none"></span>--}}
{{--                        <span>Users</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="flex justify-around">--}}
{{--                    <a href="#" class="p-5 text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">--}}
{{--                        <span class="block la la-book text-5xl leading-none"></span>--}}
{{--                        <span>Docs</span>--}}
{{--                    </a>--}}
{{--                    <a href="#" class="p-5 text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">--}}
{{--                        <span class="block la la-dollar text-5xl leading-none"></span>--}}
{{--                        <span>Shop</span>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Notifications -->
{{--        @php--}}
{{--        //get data only today--}}
{{--        $today = date('Y-m-d');--}}
{{--        $contacts = \App\Models\Contact::where('created_at', 'like', $today.'%')->get();--}}
{{--        $count = count($contacts);--}}
{{--        @endphp--}}
{{--        @if(auth()->user()->role == 'admin')--}}
{{--        <div class="dropdown self-stretch">--}}
{{--            <button type="button"--}}
{{--                    class="relative flex items-center h-full btn-link ltr:ml-1 rtl:mr-1 px-2 text-2xl leading-none la la-bell"--}}
{{--                    data-toggle="custom-dropdown-menu" data-tippy-arrow="true" data-tippy-placement="bottom-end">--}}
{{--                    <span--}}
{{--                        class="absolute top-0 right-0 rounded-full border border-primary -mt-1 -mr-1 px-2 leading-tight text-xs font-body text-primary">{{$count}}</span>--}}
{{--            </button>--}}
{{--            <div class="custom-dropdown-menu">--}}
{{--                <div class="flex items-center px-5 py-2">--}}
{{--                    <h5 class="mb-0 uppercase">Notifications</h5>--}}
{{--                    <button class="btn btn_outlined btn_warning uppercase ltr:ml-auto rtl:mr-auto">Clear All</button>--}}
{{--                </div>--}}
{{--                <hr>--}}
{{--                @foreach($contacts as $contact)--}}
{{--                <div class="p-5 hover:bg-primary-100 dark:hover:bg-primary-900">--}}
{{--                    <a href="mailto:{{$contact->email}}">--}}
{{--                        <h6 class="uppercase">{{$contact->email}}</h6>--}}
{{--                    </a>--}}
{{--                    <p>{{$contact->message}}</p>--}}
{{--                    data and time formate in month name with time--}}
{{--                    <span class="text-gray-500 dark:text-gray-400 text-sm">{{date('F d, Y h:i A', strtotime($contact->created_at))}}</span>--}}
{{--                    <small>{{$contact->created_at}}</small>--}}
{{--                </div>--}}
{{--                <hr>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @endif--}}
        <!-- User Menu -->
        <div class="dropdown">
            <button class="flex items-center ltr:ml-4 rtl:mr-4 text-gray-700" data-toggle="custom-dropdown-menu"
                    data-tippy-arrow="true" data-tippy-placement="bottom-end">
                <span class="avatar"><img src="{{auth()->user()->profile_image}}" alt="image"></span>
            </button>
            <div class="custom-dropdown-menu w-64">
                <div class="p-5">
                    <h5 class="uppercase">{{auth()->user()->f_name}}</h5>
                    <p>{{auth()->user()->role}}</p>
                </div>
                <hr>
                <div class="p-5">
                    @if(auth()->user()->role == 'admin')
{{--                    <a href="#"--}}
                    <a href="{{route('admin.profile')}}"
                       class="flex items-center text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">
                        <span class="la la-user-circle text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        View Profile
                    </a>
{{--                    @else--}}
{{--                        <a href="{{route('coach.profile')}}"--}}
{{--                           class="flex items-center text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">--}}
{{--                            <span class="la la-user-circle text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>--}}
{{--                            View Profile--}}
{{--                        </a>--}}
                    @endif
                    <a href="{{route('reset.password')}}"
{{--                    <a href="#"--}}
                       class="flex items-center text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary mt-5">
                        <span class="la la-key text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        Change Password
                    </a>
                </div>
                <hr>
                <div class="p-5">
                    <a href="{{route('logout')}}"
                       class="flex items-center text-gray-700 dark:text-gray-500 hover:text-primary dark:hover:text-primary">
                        <span class="la la-power-off text-2xl leading-none ltr:mr-2 rtl:ml-2"></span>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
