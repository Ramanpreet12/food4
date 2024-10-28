@extends('restaurantOwner.layout.layout')
@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Hoverable Table rows -->
        @include('alert_messages')
        <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Food Category</h3>
                    {{-- <small class="text-body float-end">Default label</small> --}}
                    <a class="text-body float-end" href="{{ route('restaurant.food-categories.create') }}">
                        <button class="btn btn-primary"> Add Category</button>
                    </a>
                </div>

            <div class="table-responsive text-wrap">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th>S.No. </th>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Restaurant Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if ($food_categories->isNotEmpty())
                            @foreach ($food_categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> <img
                                            src="{{ asset('storage/foodCategory/images/' . $category->image) }}"
                                            alt="" srcset="" width="75px" height="60px"></td>
                                            <td>{{ $category->name }}</td>
                                    <td>{{ $category->restaurant->name }}</td>
                                    @if ($category->status == 1)
                                        <td><span
                                                class="badge bg-label-primary me-1">Active</span>
                                        </td>
                                    @else
                                        <td><span
                                                class="badge bg-label-danger me-1">Inactive</span>
                                        </td>
                                    @endif


                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('restaurant.food-categories.edit', $category->id) }}"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete('{{ route('restaurant.food-categories.destroy', $category->id) }}')"><i
                                                        class="bx bx-trash me-1"></i>
                                                    Delete</a>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif


                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Hoverable Table rows -->
    </div>
    <!-- / Content -->
@endsection
