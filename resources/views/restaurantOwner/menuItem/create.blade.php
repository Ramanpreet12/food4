@extends('restaurantOwner.layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    @include('alert_messages')
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Add Food Item</h3>
                        <a class="text-body float-end" href="{{ route('restaurant.menu-items.index') }}">
                            <button class="btn btn-primary"> Back</button>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('restaurant.menu-items.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="name">Item Name</label>
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
                                        <label class="form-label" for="category_id">Food Category</label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            @foreach ($food_categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="price">Price</label>
                                        <input type="text" class="form-control" id="price"
                                            placeholder="Enter price" name="price"
                                            value="{{ old('price') }}" />
                                        @error('price')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="image">Image</label>
                                        <input type="file" class="form-control" id="image"
                                            name="image" value="{{ old('image') }}" />

                                        @error('image')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="description">Description</label>
                                        <input type="text" class="form-control" id="description"
                                        placeholder="Enter description"  name="description" value="{{ old('description') }}" />

                                        @error('description')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="image">Image</label>
                                        <input type="file" class="form-control" id="image"
                                            name="image" value="{{ old('image') }}" />

                                        @error('image')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
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
