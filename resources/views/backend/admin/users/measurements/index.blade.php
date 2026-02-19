@extends('backend.layouts.master')
@section('title','Measurements Detail')
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
                    <li><a href="#">Measurements Detail</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>
        </section>


            <div class=" mt-5">

                <!-- Bar -->
                <div class="">
                    <div class="card p-5">
                        <h3>Last 30 days Measurements of {{$measurement->name}} ({{$measurement->unit}})</h3>
                        <div class="mt-5 ">
                            <canvas id="singleBarChart" style="height: 300px; max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        <!-- History Section -->
        <div class="mt-6">
            <h4 class="text-lg font-medium mb-3">History</h4>
            <div class="border-t">
                @if(is_iterable($measurementLog))
                    @foreach($measurementLog as $history)
                        <div class="flex justify-between items-center border-b">
                            <div class="text-gray-600">
                                {{ \Carbon\Carbon::parse($history->measurement_at)->format('j F Y') }} at {{ \Carbon\Carbon::parse($history->measurement_at)->format('g:i A') }}
                            </div>
                            <div class="font-medium">
                                {{ $history->value }}{{ $measurement->unit }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No measurement history available.</p>
                @endif
            </div>

        </div>




        @include('backend.layouts.footer')
    </main>
@endsection

@section('script')
    <script>
        const graphLabels = {!! json_encode(collect($graphData)->pluck('day')) !!};
        const graphValues = {!! json_encode(collect($graphData)->pluck('total_value')) !!};
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



