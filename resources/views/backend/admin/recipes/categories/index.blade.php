@extends('backend.layouts.master')
@section('title','Recipy Category')
@section('content')

    <!-- Workspace -->
    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Category</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin_recipes.category')}}">Category</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>
            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <!-- Add New -->
                    <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2" data-toggle="modal"
                            data-target="#addcategory">Add New</button>
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
                        <th class="text-center uppercase">Category Name</th>
                        <th class="uppercase ltr:text-left rtl:text-right">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recipes as $index => $rec)
                        <tr>
                            <td>{{++$index}}</td>
                            <td >
                                <img src="{{$rec->image}}" alt="" width="50px"
                                     height="50px" class="rounded-full">
                            </td>
                            <td >{{$rec->name}} </td>

                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                            <span >
                                <button class="btn btn-icon btn_outlined btn_secondary" type="button" data-toggle="modal" data-target="#update{{$rec->id}}" data-toggle="tooltip" data-placement="left" title="Edit"><span class="la la-pen-fancy"></span></button>
                            </span>
                                    <span class="ml-2">
                                <button class="btn btn-icon btn_outlined btn_danger" type="button" data-toggle="modal" data-target="#delete{{$rec->id}}" data-toggle="tooltip" data-placement="left" title="Delete"><span class="la la-trash-alt"></span></button>
                                </span>
                                </div>
                            </td>
                        </tr>
                        {{--Delete icon Modal--}}
                        <div id="delete{{$rec->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Category</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete these records? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{route('admin_recipes.category.delete',$rec->id)}}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--Edite icon Modal--}}
                        <div id="update{{$rec->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Update Category</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <center >
                                        <span class=" w-10 h-10 rounded-full"><img src="{{$rec->image}}" alt="No image" id="output" style=" height: 200px; width: 200px; border-radius: 100%"></span>
                                    </center>
                                    <form id="categoryFormEdit" action="{{route('admin_recipes.category.update',$rec->id)}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-transparent">
                                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                            </p>
                                            <h3>Category</h3>
                                            <div class="relative mt-5">
                                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading"
                                                       for="input-2">Category</label>
                                                <input id="categoryName" type="text" class="form-control mt-2 pt-5" placeholder="Enter text here" name="name" value="{{$rec->name}}">
                                                <span id="nameError" class="text-danger" style="color: #9c1919"></span>
                                            </div>
                                            <div class="relative mt-5">
                                                <label class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading"
                                                       for="input-2">Media</label>
                                                <input  type="file" onchange="loadFileEdit(event)" class="form-control mt-2 pt-5" placeholder="upload media here" name="image">

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Update Category</button>
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
        <div id="addcategory" class="modal" data-animations="fadeInDown, fadeOutUp">
            <div class="modal-dialog modal-dialog_centered max-w-2xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Add Category</h2>
                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                    </div>
                    <center >
                        <span class=" w-10 h-10 rounded-full"><img src="" alt="No image" id="outputAdd" style="display: none; height: 200px; width: 200px; border-radius: 100%"></span>
                    </center>
                    <form id="categoryForm"  action="{{route('admin_recipes.category.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p class="text-transparent">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            </p>
                            <h3>Category</h3>
                            <div class="relative mt-5">
                                <label
                                    class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading"
                                    for="input-2">Category</label>
                                <input id="categoryName" type="text" class="form-control mt-2 mb-1 pt-5" placeholder="Enter text here" name="name">
                                <span id="nameError" class="text-danger" style="color: #9c1919"></span>
                            </div>
                            <div class="relative mt-5">
                                <label
                                    class="label absolute block bg-white dark:bg-gray-900 border rounded border-gray-300 dark:border-gray-700 top-0 ltr:ml-4 rtl:mr-4 px-2 font-heading"
                                    for="input-2">Media</label>

                                <input id="categoryImage" type="file" onchange="loadFile(event)" class="form-control mt-2 mb-1 pt-5" placeholder="upload media here" name="image">
                                <span id="imageError" class="text-danger" style="color: #9c1919"></span>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="flex ltr:ml-auto rtl:mr-auto">
                                <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_primary ltr:ml-2 rtl:mr-2 uppercase">Add Category</button>
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
        var loadFileEdit = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('outputAdd');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
                output.style.display = '';
            }
        };
    </script>
    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            var name = document.getElementById('categoryName').value;
            var image = document.getElementById('categoryImage').files.length;

            var nameError = document.getElementById('nameError');
            var imageError = document.getElementById('imageError');

            nameError.textContent = '';
            imageError.textContent = '';

            if (!name) {
                event.preventDefault();
                nameError.textContent = 'Name is required.';
            }

            if (!image) {
                event.preventDefault();
                imageError.textContent = 'Image is required.';
            }
        });
        document.getElementById('categoryName').addEventListener('input', function() {
            var name = document.getElementById('categoryName').value;
            var nameError = document.getElementById('nameError');

            if (name) {
                nameError.textContent = '';
            }
        });




        document.getElementById('categoryFormEdit').addEventListener('submit', function(event) {
            var name = document.getElementById('categoryName').value;

            var nameError = document.getElementById('nameError');

            nameError.textContent = '';

            if (!name) {
                event.preventDefault();
                nameError.textContent = 'Name is required.';
            }
        });
        document.getElementById('categoryFormEdit').addEventListener('input', function() {
            var name = document.getElementById('categoryName').value;
            var nameError = document.getElementById('nameError');

            if (name) {
                nameError.textContent = '';
            }
        });

    </script>
@endsection


