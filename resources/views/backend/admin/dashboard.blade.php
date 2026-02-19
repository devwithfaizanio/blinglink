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
                    <a href="{{route('admin.users')}}">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-users"></span>
                            <p class="mt-2">Users</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$totalUsers}}</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="{{route('admin_recipes.diets')}}">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-list-alt"></span>
                            <p class="mt-2">Recipes</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$totalRecipes}}</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="#">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-archway"></span>
                            <p class="mt-2">Workouts</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$totalWorkouts}}</div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="lg:w-1/2 lg:px-4">
            <!-- Summaries -->
            <div class="lg:flex lg:-mx-4">
                <div class="lg:w-1/3 lg:px-4">
                    <a href="{{route('admin_fasts.fasts')}}">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-clock-o"></span>
                            <p class="mt-2">Fast Type</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$totalFastType}}</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="{{route('admin.announcement')}}">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-bell"></span>
                            <p class="mt-2">Announcements</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$totalAnnouncements}}</div>
                        </div>
                    </a>
                </div>
                <div class="lg:w-1/3 lg:px-4 pt-5 lg:pt-0">
                    <a href="{{route('admin.tickets')}}">
                        <div
                            class="card px-4 py-8 text-center lg:transform hover:scale-110 hover:shadow-lg transition-transform duration-200">
                            <span class="text-primary text-5xl leading-none la la-ticket"></span>
                            <p class="mt-2">OpenTickets</p>
                            <div class="text-primary mt-5 text-3xl leading-none">{{$openTickets}}</div>
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
                            {{ $last12MonthsUsersRegistered }}
                        </span>
                        users registered in the last 12 months!
                    </span>
                </h3>
                <div class="mt-5 ">
                    <canvas id="singleBarChart" style="height: 300px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

    @include('backend.layouts.footer')
</main>



@endsection
<!-- Scripts -->

@section('script')
    <script>
        const graphLabels = {!! json_encode($graphData['months']) !!};
        const graphValues = {!! json_encode($graphData['users']) !!};
    </script>


    <!-- HTML Structure with updated ID -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('singleBarChart').getContext('2d');

            const data = {
                labels: graphLabels,
                datasets: [{
                    label: 'Measurements',
                    data: graphValues,
                    backgroundColor: 'rgb(22,119,95)',
                    borderColor: 'rgb(29,113,92)',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>

@endsection

