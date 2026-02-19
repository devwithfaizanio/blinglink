@extends('backend.layouts.master')
@section('title','Diet')
@section('content')

    <!-- Workspace -->

    <main class="workspace overflow-hidden relative">
        @include('backend.layouts.toaster')
        <!-- Breadcrumb -->
        <section class="breadcrumb lg:flex items-start">
            <div>
                <h1>Diets</h1>
                <ul>
                    <li><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="{{route('admin.users')}}">Users</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li><a href="#">Diets</a></li>
                    <li class="divider la la-arrow-right"></li>
                    <li>List</li>
                </ul>
            </div>

            <div class="lg:flex items-center ltr:ml-auto rtl:mr-auto mt-5 lg:mt-3">
                <div class="flex mt-5 lg:mt-0">
                    <!-- Add New -->
{{--                    <a href="{{route('admin_recipes.diets.create')}}">--}}
{{--                       <button class="btn btn_primary uppercase ltr:ml-2 rtl:mr-2">Add New</button>--}}
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
                        <th class="text-center uppercase">category</th>
                        <th class="text-center uppercase">calories</th>
                        <th class="text-center uppercase">Estimate time</th>
                        <th class="uppercase">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($diets as $index => $diet)
                        <tr>
                            <td>{{++$index}}</td>
                            <td class="text-center"><img src="{{$diet->image}}" width="50px" height="50px" style="border-radius: 5%"> </td>
                            <td class="">{{$diet->title}}</td>
                            <td class="">{{$diet->category->name}}</td>
                            <td class="">{{$diet->calories. ' cal'}}</td>
                            <td class="">{{$diet->estimate_time . ' min'}}</td>

                            <td class="ltr:text-right rtl:text-left whitespace-nowrap">
                                <div class="inline-flex ltr:ml-auto rtl:mr-auto">
                                    <span >
                                        <a href="{{route('admin_recipes.user.diets.edit',$diet->id)}}">
                                        <button class="btn btn-icon btn_outlined btn_secondary" type="button"  data-placement="left" title="Update Diet"><span class="icon la la-pen-fancy"></span></button>
                                    </a>
                                    </span>
                                    <span class="ml-2">
                                        <button class="btn btn-icon btn_outlined btn_danger" type="button" data-toggle="modal" data-target="#delete{{$diet->id}}" data-toggle="tooltip" data-placement="left" title="Delete"><span class="la la-trash-alt"></span></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        {{--Delete User Modal--}}
                        <div id="delete{{$diet->id}}" class="modal" data-animations="fadeInDown, fadeOutUp">
                            <div class="modal-dialog max-w-2xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title">Delete Diet</h2>
                                        <button type="button" class="close la la-times" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Do you really want to delete these records? This process cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <div class="flex ltr:ml-auto rtl:mr-auto">
                                            <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                                            <form action="{{route('admin_recipes.user.diets.delete',$diet->id)}}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn_danger ltr:ml-2 rtl:mr-2 uppercase">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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



