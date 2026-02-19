@extends('backend.layouts.master')
@section('title','Fast')
@section('content')

    <!-- Workspace -->
    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Fast</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin_fasts.fasts')}}">Fast</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <!-- Add New -->
                    <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2" data-toggle="modal"
                            data-target="#addFasts">Add New</button>
                </div>
            </div>
        </section>

        <div class="card p-5">
            <div class="overflow-x-auto">
                <table class="table table-auto table_hoverable w-full " id="myTable">
                    <thead>
                    <tr>
                        <th class="ltr:text-left rtl:text-right uppercase">#</th>
                        <th class="text-center uppercase">Fasting Hour</th>
                        <th class="text-center uppercase">Eating Hour</th>
                        <th class="text-center uppercase">type</th>
                        <th class="uppercase ltr:text-left rtl:text-right">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fasts as $index => $fast)
                        <tr>
                            <td>{{++$index}}</td>
                            <td >{{$fast->fasting_hour}} </td>
                            <td >{{$fast->eating_hour}} </td>
                            <td >{{ $fast->type }} </td>

                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                            <span >
                                <button class="btn btn-icon btn_outlined btn_secondary" type="button" data-toggle="modal" data-target="#update{{$fast->id}}" data-toggle="tooltip" data-placement="left" title="Edit"><span class="la la-pen-fancy"></span></button>
                            </span>
                            <span class="ml-2">
                                <button class="btn btn-icon btn_outlined btn_danger" type="button" data-toggle="modal" data-target="#delete{{$fast->id}}" data-toggle="tooltip" data-placement="left" title="Delete"><span class="la la-trash-alt"></span></button>
                            </span>
                                </div>
                            </td>
                        </tr>
                        {{--Delete icon Modal--}}
                        <div id="delete{{$fast->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Fast</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete these records? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{route('admin_fasts.fasts.delete',$fast->id)}}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="update{{$fast->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Update Fast</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <form id="exerciseFormEdit{{$fast->id}}" action="{{route('admin_fasts.fasts.update',$fast->id)}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-transparent">
                                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                            </p>
                                            <h3>Workout</h3>
                                            <div class="flex gap-6">
                                                <div class="xl:w-1/2">
                                                    <div class="relative mt-5">
                                                        <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Fasting Hour</label>
                                                        <input id="fastingHour{{$fast->id}}" type="text" class="form-control mt-2 mb-1 pt-5" name="fasting_hour" placeholder="Enter fasting hour" value="{{$fast->fasting_hour}}">
                                                        <span id="fastingHourError{{$fast->id}}" class="text-danger" style="color: #9c1919;"></span>
                                                    </div>
                                                </div>
                                                <div class="xl:w-1/2">
                                                    <div class="relative mt-5">
                                                        <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Eating Hour</label>
                                                        <input id="eatingHour{{$fast->id}}" type="text" class="form-control mt-2 mb-1 pt-5" name="eating_hour" placeholder="Enter eating hour" value="{{$fast->eating_hour}}">
                                                        <span id="eatingHourError{{$fast->id}}" class="text-danger" style="color: #9c1919;"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative mt-5">
                                                <label class="label">Type</label>
                                                <select id="typeSelect{{$fast->id}}" class="form-control" name="type">
                                                    <option value="">Select Type</option>
                                                    <option value="easiest" {{$fast->type == 'easiest' ? 'selected' : ''}}>Easiest</option>
                                                    <option value="moderate" {{$fast->type == 'moderate' ? 'selected' : ''}}>Moderate</option>
                                                    <option value="challenging" {{$fast->type == 'challenging' ? 'selected' : ''}}>Challenging</option>
                                                </select>
                                                <span id="typeError{{$fast->id}}" class="text-danger" style="color: #9c1919;"></span>
                                            </div>

                                            <div class="mt-5">
                                                <label class="label block mb-2" for="description">Description</label>
                                                <textarea rows="2" id="description{{$fast->id}}" class="form-control" name="description">{{$fast->description}}</textarea>
                                                <span id="descriptionError{{$fast->id}}" class="text-danger" style="color: #9c1919;"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Update Fast</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Add Category Modal -->
        <div id="addFasts" class="modal" data-animations="fadeInDown, fadeOutUp">
            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Add Fast</h2>
                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                    </div>
                    <form id="addLevelForm" action="{{ route('admin_fasts.fasts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p class="text-transparent">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            </p>
                            <h3>Fast</h3>

                            <div class="flex gap-6">
                                <div class="xl:w-1/2">
                                    <div class="relative mt-5">
                                        <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Fasting Hour</label>
                                        <input id="fastingHour" type="text" class="form-control mt-2 mb-1 pt-5" placeholder="Enter hour here" name="fasting_hour">
                                        <span id="fastingHourError" class="text-danger" style="color: #9c1919;"></span>
                                    </div>
                                </div>
                                <div class="xl:w-1/2">
                                    <div class="relative mt-5">
                                        <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading">Eating Hour</label>
                                        <input id="eatingHour" type="text" class="form-control mt-2 mb-1 pt-5" placeholder="Enter hours here" name="eating_hour">
                                        <span id="eatingHourError" class="text-danger" style="color: #9c1919;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative mt-5">
                                <label class="label">Type</label>
                                <select class="form-control" name="type" id="typeSelect">
                                    <option value="">Select Type</option>
                                    <option value="easiest">Easiest</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="challenging">Challenging</option>
                                </select>
                                <div class="custom-select-icon la la-caret-down"></div>
                                <span id="typeError" class="text-danger" style="color: #9c1919;"></span>
                            </div>

                            <div class="mt-5">
                                <label class="label block mb-2" for="description">Description</label>
                                <textarea rows="2" id="description" class="form-control" name="description"></textarea>
                                <span id="descriptionError" class="text-danger" style="color: #9c1919;"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Add Fast</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('backend.layouts.footer')
    </main>
@endsection

@section('script')
    <script>
        document.getElementById('addLevelForm').addEventListener('submit', function (event) {
            let isValid = true;

            const fastingHour = document.getElementById('fastingHour');
            const eatingHour = document.getElementById('eatingHour');
            const typeSelect = document.getElementById('typeSelect');
            const description = document.getElementById('description');

            // Error elements
            const fastingHourError = document.getElementById('fastingHourError');
            const eatingHourError = document.getElementById('eatingHourError');
            const typeError = document.getElementById('typeError');
            const descriptionError = document.getElementById('descriptionError');

            // Clear previous errors
            fastingHourError.textContent = '';
            eatingHourError.textContent = '';
            typeError.textContent = '';
            descriptionError.textContent = '';

            if (!fastingHour.value.trim()) {
                fastingHourError.textContent = 'Fasting hour is required.';
                isValid = false;
            }

            if (!eatingHour.value.trim()) {
                eatingHourError.textContent = 'Eating hour is required.';
                isValid = false;
            }

            if (!typeSelect.value) {
                typeError.textContent = 'Please select a type.';
                isValid = false;
            }

            if (!description.value.trim()) {
                descriptionError.textContent = 'Description is required.';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>


    <script>
        document.querySelectorAll("form[id^='exerciseFormEdit']").forEach(form => {
            form.addEventListener("submit", function(event) {
                const id = this.id.replace('exerciseFormEdit', '');

                const fastingHour = document.getElementById(`fastingHour${id}`);
                const eatingHour = document.getElementById(`eatingHour${id}`);
                const typeSelect = document.getElementById(`typeSelect${id}`);
                const description = document.getElementById(`description${id}`);

                const fastingHourError = document.getElementById(`fastingHourError${id}`);
                const eatingHourError = document.getElementById(`eatingHourError${id}`);
                const typeError = document.getElementById(`typeError${id}`);
                const descriptionError = document.getElementById(`descriptionError${id}`);

                let isValid = true;

                fastingHourError.textContent = '';
                eatingHourError.textContent = '';
                typeError.textContent = '';
                descriptionError.textContent = '';

                if (!fastingHour.value.trim()) {
                    fastingHourError.textContent = 'Fasting hour is required.';
                    isValid = false;
                }

                if (!eatingHour.value.trim()) {
                    eatingHourError.textContent = 'Eating hour is required.';
                    isValid = false;
                }

                if (!typeSelect.value) {
                    typeError.textContent = 'Please select a type.';
                    isValid = false;
                }

                if (!description.value.trim()) {
                    descriptionError.textContent = 'Description is required.';
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>








@endsection


