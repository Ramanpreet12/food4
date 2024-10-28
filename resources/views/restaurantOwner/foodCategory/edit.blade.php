@extends('restaurantOwner.layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    @include('alert_messages')
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Edit Food Category</h3>
                        <a class="text-body float-end" href="{{ route('restaurant.food-categories.index') }}">
                            <button class="btn btn-primary"> Back</button>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('restaurant.food-categories.update' , $category->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="name">Category Name</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter restaurant name" name="name"
                                            value="{{ old('name', $category->name) }}" />
                                        @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="image">Image</label>
                                        <input type="file" class="form-control" id="description" name="image"
                                            value="{{ old('image') }}" />

                                        @error('image')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" @if ($category->status == '1') selected @endif>Active
                                            </option>
                                            <option value="0" @if ($category->status == '0') selected @endif>
                                                Inactive</option>
                                        </select>

                                        @error('status')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @if ($category->image)

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <img src="{{ asset('storage/foodCategory/images/' . $category->image) }}"
                                            alt="" srcset="" width="220px" height="100px">
                                    </div>
                                </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
{{-- <script>
    document.getElementById('form').onsubmit = function(event) {
        var fileInput = document.getElementById('file-input');
        var file = fileInput.files[0];

        if (file.size > 10 * 1024 * 1024) { // 10MB in bytes
            alert('File size must be less than 10MB');
            event.preventDefault(); // Prevent form submission
        }
    };
</script> --}}
