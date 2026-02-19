@extends('backend.layouts.master')
@section('title','Profile')
@section('content')


    <!-- Workspace -->
    <main class="workspace">
@include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb">
            <h1>Profile</h1>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li class="divider la la-arrow-right"></li>
                <li><a href="#">Profile</a></li>
            </ul>
        </section>

        <div class="lg:flex lg:-mx-4">

            <!-- Categories -->
            <div class="lg:w-1/4 lg:px-4">
                <div class="card p-5">
                    <h3>Photo</h3>
                    <div class="mt-5 leading-normal">
                        <span class=" w-32 h-32 rounded-r-lg"><img src="{{auth()->user()->image}}" alt="No image" id="output"></span>
                        <hr class="my-2">
                        <p class="flex items-center text-gray-700 dark:text-gray-500 hover:text-primary">
                        <h3>{{auth()->user()->name}}</h3>
                        <h4>{{auth()->user()->email}}</h4>
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 xl:w-3/4 lg:px-4">
                <form action="{{route('admin.profile.post')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Column -->
                            <div class="">
                                <div class="mb-5">
                                    <label class="label block mb-2" for="title">Name</label>
                                    <input id="name" type="text" class="form-control" name="fname" value="{{auth()->user()->fname}}">
                                </div>
                            </div>
                            <!-- Second Column -->
                            <div class="">
                                <div class="mb-5">
                                    <label class="label block mb-2" for="title">Name</label>
                                    <input id="name" type="text" class="form-control" name="lname" value="{{auth()->user()->lname}}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-5 xl:w-1/2 ">
                            <label class="label block mb-2" for="image">Image</label>
                            <input type="file" name="image" onchange="loadFile(event)" class="block w-full text-sm text-gray-500 file:py-2 file:px-6 file:rounded file:border-1 file:border-primary-400">
                        </div>
                    </div>
                    <div class="card mt-5 p-5">
                        <h3>Update Profile</h3>
                        <button class="mt-5 btn btn_outlined btn_secondary uppercase" type="submit">Update</button>
                    </div>
                </form>
            </div>

        </div>

        @include('backend.layouts.footer')
    </main>
@endsection
@section('script')
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endsection
