@extends('backend.layouts.master')
@section('title','Dashboard')
@section('content')

<!-- Workspace -->
<main class="workspace overflow-hidden">

    <!-- Breadcrumb -->
    <section class="breadcrumb">
        <h1>Dashboard</h1>
        <ul>
            <li><a href="#">Login</a></li>
            <li class="divider la la-arrow-right"></li>
            <li>Dashboard</li>
        </ul>
    </section>

    <div class="lg:flex lg:-mx-4">
        <div class="lg:w-1/2 lg:px-4">
            <!-- Summaries -->
            <div class="lg:flex lg:-mx-4">
                <div class="lg:w-1/3 lg:px-4">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-users"></span>
                            <p class="mt-2">Users</p>
                            <div class="text-primary mt-5 text-3xl leading-none">12</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-list-alt"></span>
                            <p class="mt-2">Recipes</p>
                            <div class="text-primary mt-5 text-3xl leading-none">13</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-archway"></span>
                            <p class="mt-2">Workouts</p>
                            <div class="text-primary mt-5 text-3xl leading-none">12</div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="lg:w-1/2 lg:px-4">
            <!-- Summaries -->
            <div class="lg:flex lg:-mx-4">
                <div class="lg:w-1/3 lg:px-4">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-clock-o"></span>
                            <p class="mt-2">Fast Type</p>
                            <div class="text-primary mt-5 text-3xl leading-none">122</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-bell"></span>
                            <p class="mt-2">Announcements</p>
                            <div class="text-primary mt-5 text-3xl leading-none">12</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-ticket"></span>
                            <p class="mt-2">OpenTickets</p>
                            <div class="text-primary mt-5 text-3xl leading-none">12</div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div class=" mt-5">

        <!-- Bar -->
        <div class="">
            <div class="card p-5">
{{--                <h3>Last 12 months users are registered {{$last12MonthsUsersRegistered}} </h3>--}}
                <h3>
                    <span style="font-weight: 600; color: #2c3e50;">
                        📈 A total of
                        <span style="color: #27ae60; font-weight: bold;">
                            152
                        </span>
                        users registered in the last 12 months!
                    </span>
                </h3>
                <div class="mt-5 ">
{{--                    <canvas id="singleBarChart" style="height: 300px; max-height: 300px;"></canvas>--}}
                </div>
            </div>
        </div>

    </div>

    @include('backend.layouts.footer')
</main>



@endsection
<!-- Scripts -->

@section('script')


@endsection

