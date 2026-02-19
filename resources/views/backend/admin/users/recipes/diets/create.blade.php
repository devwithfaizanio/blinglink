@extends('backend.layouts.master')
@section('title','Create Diet')
@section('content')
    {{--        {{dd($brands)}}--}}
    <main class="workspace">
        @include('backend.layouts.toaster')
        <form action="{{route('admin_recipes.diets.store')}}" method="POST" enctype="multipart/form-data" id="adddiet">
            @csrf
            <!-- Breadcrumb -->
            <section class="breadcrumb">
                <h1>Diets</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="#">Privacy</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>Add Diet</li>
                </ul>
            </section>

            <div class=" lg:-mx-4">

                <div class="lg:w-1/1 xl:w-1/1 lg:px-4">
                    <div class="card p-5">
                        <div class="flex gap-6">

                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2 " for="title">Diet Title</label>
                                <input id="name" type="text" class="form-control " name="title">
                            </div>
                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2 " for="title">Category name</label>
                                <select class="form-control  select2" name="recipes_cat_id"
                                        data-placeholder="Search Category" style="width: 100%">
                                    <option></option>
                                    @foreach($categories as $index => $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="mb-5 flex gap-4 xl:w-1/3">
                                <div class="xl:w-1/2">
                                    <label class="label block mb-2 " for="title">Calories</label>
                                    <input type="number" class="form-control " name="calories">
                                </div>
                                <div class="xl:w-1/2">
                                    <label class="label block mb-2 " for="title">Estimate Time</label>
                                    <input type="number" class="form-control " name="estimate_time">
                                </div>
                            </div>
                            <div class="mb-5 flex gap-4 xl:w-1/3">
                                <div class="xl:w-1/3">
                                    <label class="label block mb-2 " for="title">Nutrients (Pro)</label>
                                    <input type="number" class="form-control" placeholder="Proteins (g)"
                                           name="proteins">
                                </div>
                                <div class="xl:w-1/3">
                                    <label class="label block mb-2 " for="title">Nutrients (Carbs)</label>
                                    <input type="number" class="form-control" placeholder="Carbs (g)" name="carbs">
                                </div>
                                <div class="xl:w-1/3">
                                    <label class="label block mb-2 " for="title">Nutrients (Fat)</label>
                                    <input type="number" class="form-control" placeholder="Fat (g)" name="fats">
                                </div>
                            </div>

                            <div class="mb-5 flex gap-4 xl:w-1/3">
                                <div class="xl:w-1/2">
                                    <label class="label block mb-2" for="image">Image</label>
                                    <input type="file" name="image" onchange="loadFile(event)"
                                           class="block w-full text-sm text-gray-500 file:py-2 file:px-6 file:rounded file:border-1 file:border-primary-400">
                                </div>
                                <div class="xl:w-1/2">
                                    <center>
                                        <span class=""><img src="" width="80px" height="80px" alt="No image"
                                                            id="outputAdd"
                                                            style="display: none; border-radius: 1%"></span>
                                    </center>
                                </div>
                            </div>
                        </div>


                        <div class="flex gap-6">

                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2 " for="title">Ingredients</label>
                                <textarea id="editor" class="form-control ckeditor" name="ingredients"></textarea>
                            </div>
                            <div class="mb-5 xl:w-1/2">
                                <label class="label block mb-2 " for="title">How to coke</label>
                                <textarea id="editor" class="form-control ckeditor" name="how_to_cook"></textarea>
                            </div>
                        </div>


                        <div class="mb-5">
                            <label class="label block mb-2 " for="title">Description</label>
                            <textarea id="editor" class="form-control ckeditor" name="description"></textarea>
                        </div>
                    </div>
                    <div class="card mt-5 p-5">
                        <h3>Add Diet</h3>
                        <button class="mt-5 btn btn_outlined btn_secondary uppercase" type="submit">Submit</button>
                    </div>
                </div>

            </div>
        </form>
        @include('backend.layouts.footer')
    </main>
@endsection
@section('script')

    <script>
        var loadFile = function (event) {
            var output = document.getElementById('outputAdd');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
                output.style.display = '';
            }
        };
    </script>
    <script>
        // Re-initialize Select2 on the newly added select element
        $('.select2').select2({
            minimumInputLength: 0,
            allowClear: true,
            multiple: false,
            placeholder: function () {
                $(this).data('placeholder');
            }
        });
    </script>
    <script src="{{asset('js-validation/jquery.min.js')}}"></script>
    <script src="{{asset('js-validation/bootstrap.min.js')}}"></script>

    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\web\v1\admin\recipe\CreateExerciseRequest', '#adddiet'); !!}

@endsection


