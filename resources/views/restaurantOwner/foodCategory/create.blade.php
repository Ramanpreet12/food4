@extends('restaurantOwner.layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    @include('alert_messages')
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Add Food Category</h3>
                        <a class="text-body float-end" href="{{ route('restaurant.food-categories.index') }}">
                            <button class="btn btn-primary"> Back</button>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('restaurant.food-categories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="name">Category Name</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter restaurant name" name="name"
                                            value="{{ old('name') }}" />
                                        @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="image">Image</label>
                                        <input type="file" class="form-control" id="description"
                                            name="image" value="{{ old('image') }}" />

                                        @error('image')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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
