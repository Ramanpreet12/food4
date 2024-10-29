@extends('restaurantOwner.layout.layout')
@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Hoverable Table rows -->
        @include('alert_messages')
        <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Menu Items</h3>
                    {{-- <small class="text-body float-end">Default label</small> --}}
                    <a class="text-body float-end" href="{{ route('restaurant.menu-items.create') }}">
                        <button class="btn btn-primary"> Add Menu Item</button>
                    </a>
                </div>

            <div class="table-responsive text-wrap">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th>S.No. </th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Food Category Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @if ($menuItems->isNotEmpty())
                            @foreach ($menuItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> <img
                                            src="{{ asset('storage/menuItem/images/' . $item->image) }}"
                                            alt="" srcset="" width="75px" height="60px"></td>
                                            <td>{{ $item->name }}</td>
                                    <td>{{ $item->foodCategory->name }}</td>
                                    @if ($item->status == 'active')
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
                                                <a class="dropdown-item" href="{{ route('restaurant.menu-items.edit', $item->id) }}"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete('{{ route('restaurant.menu-items.destroy', $item->id) }}')"><i
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
