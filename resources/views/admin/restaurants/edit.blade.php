@extends('admin.layout.layout')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-6">
                    @include('alert_messages')
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Edit Restaurant</h3>
                        <a class="text-body float-end" href="{{ route('admin.restaurants.index') }}">
                            <button class="btn btn-primary"> Back</button>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="{{ $restaurant->id }}" name="id">
                            <h4>1. Basic Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="name">Restaurant Name</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Enter restaurant name" name="name"
                                            value="{{ old('name', $restaurant->name) }}" />
                                        @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="description">Description</label>
                                        <input type="text" class="form-control" id="description"
                                            placeholder="Brief description (e.g., cuisine or specialties)"
                                            name="description"
                                            value="{{ old('description', $restaurant->description) }}" />

                                        @error('description')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="speciality">Speciality</label>
                                        <input type="text" class="form-control" id="speciality" placeholder="speciality"
                                            name="speciality" value="{{ old('speciality', $restaurant->speciality) }}" />
                                    </div>
                                </div>

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="type">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="subscription_based" @if($restaurant->type ==  'subscription_based') selected @endif>Type1</option>
                                            {{-- <option value="active"  @if ($restaurant->type == 'active') selected @endif>Active</option> --}}
                                            <option value="self" @if($restaurant->type ==  'self') selected @endif>Type 2</option>
                                        </select>

                                        @error('type')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="priority">Priority</label>
                                            <select name="priority" id="priority" class="form-control">
                                                <option value="high" @if($restaurant->priority ==  'high') selected @endif>high</option>
                                                <option value="medium" @if($restaurant->priority ==  'medium') selected @endif>medium</option>
                                                <option value="low" @if($restaurant->priority ==  'low') selected @endif>low</option>
                                            </select>
                                    </div>
                                    @error('priority')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                </div>

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="pureVeg">Pure Veg</label>

                                            <select name="pureVeg" id="pureVeg" class="form-control">
                                                <option value="yes" @if($restaurant->pureVeg ==  'yes') selected @endif>Yes</option>
                                                <option value="no" @if($restaurant->pureVeg ==  'no') selected @endif>No</option>
                                            </select>
                                    </div>
                                    @error('pureVeg')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="deliveryIs">Delivery Is</label>

                                            <select name="deliveryIs" id="deliveryIs" class="form-control">
                                                <option value="yes" @if($restaurant->deliveryIs ==  'yes') selected @endif>Yes</option>
                                                <option value="no" @if($restaurant->deliveryIs ==  'no') selected @endif>No</option>
                                            </select>
                                    </div>
                                    @error('deliveryIs')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                </div>

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active"  @if($restaurant->status ==  'active') selected @endif>Active</option>
                                            <option value="inactive"  @if($restaurant->status ==  'inactive') selected @endif>Inactive</option>
                                            <option value="block"  @if($restaurant->status ==  'block') selected @endif>Block</option>
                                        </select>

                                    </div>
                                </div>
                            </div>



                            <hr>

                            <h4>2. Location Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">

                                    <div class="mb-6">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea id="address" class="form-control" placeholder="Enter full address" name="address">{{ old('email', $restaurant->address) }}</textarea>
                                        @error('address')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="city">City</label>
                                        <input type="text" class="form-control" id="city" placeholder="Enter city"
                                            name="city" value="{{ old('city', $restaurant->city) }}" />
                                        @error('city')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="state">State</label>
                                        <input type="text" class="form-control" id="state"
                                            placeholder="Enter state or province" name="state"
                                            value="{{ old('state', $restaurant->state) }}" />
                                        @error('state')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="postal_code">Postal Code/Zip Code</label>
                                        <input type="text" class="form-control" id="postal_code"
                                            placeholder="Enter postal/zip code" name="postal_code"
                                            value="{{ old('postal_code', $restaurant->postal_code) }}" />
                                        @error('postal_code')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="country">Country</label>
                                        <input type="text" class="form-control" id="country"
                                            placeholder="Enter country" name="country"
                                            value="{{ old('country', $restaurant->country) }}" />
                                        @error('country')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                        {{-- <select name="country" class="select2 form-select" id="country"
                                            placeholder="Select country">
                                            <option value="">Select country</option>
                                            <option value="">India</option>

                                        </select> --}}
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="delivery_radius">Delivery Radius <small>In
                                                km</small></label>
                                        <input type="text" class="form-control" id="delivery_radius"
                                            placeholder="Enter delivery radius in km" name="delivery_radius"
                                            value="{{ old('delivery_radius', $restaurant->delivery_radius) }}" />
                                        @error('delivery_radius')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="latitude">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                            placeholder="Enter latitude"
                                            value="{{ old('latitude', $restaurant->latitude) }}" />
                                        @error('latitude')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="longitude">Longitude </label>
                                        <input type="text" class="form-control" id="longitude"
                                            placeholder="Enter delivery radius in km" name="longitude"
                                            value="{{ old('longitude', $restaurant->longitude) }}" />
                                        @error('longitude')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="mb-6">
                          <label class="form-label" for="basic-default-message">Message</label>
                          <textarea
                            id="basic-default-message"
                            class="form-control"
                            placeholder="Hi, Do you have a moment to talk Joe?"></textarea>
                        </div> --}}

                            <hr>

                            <h4>3. Contact Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="phone">Phone Number</label>
                                        <input type="text" class="form-control" id="phone"
                                            placeholder="Enter primary phone number" name="phone"
                                            value="{{ old('phone', $restaurant->phone) }}" />
                                        @error('phone')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="secondary_phone">Secondary Phone
                                            Number</label>
                                        <input type="text" class="form-control" id="secondary_phone"
                                            placeholder="Enter secondary phone number (optional)" name="secondary_phone"
                                            value="{{ old('secondary_phone', $restaurant->secondary_phone) }}" />
                                        @error('secondary_phone')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="email">Email Address</label>
                                        <input type="text" class="form-control" id="email"
                                            placeholder="Enter email address" name="email"
                                            value="{{ old('email', $restaurant->email) }}" />
                                        @error('email')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="website">Website</label>
                                        <input type="text" class="form-control" id="website"
                                            placeholder="Enter website URL (optional)" name="website"
                                            value="{{ old('website', $restaurant->website) }}" />
                                        @error('website')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <h4>4. Restaurant Owner Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="owner_name">Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name"
                                            placeholder="Enter owner name" name="owner_name"
                                            value="{{ old('owner_name', $restaurant->owner_name) }}" />
                                        @error('owner_name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="owner_contact_number">Owner Contact Number</label>
                                        <input type="text" class="form-control" id="owner_contact_number"
                                            placeholder="Enter owner phone number" name="owner_contact_number"
                                            value="{{ old('owner_contact_number', $restaurant->owner_contact_number) }}" />
                                        @error('owner_contact_number')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="owner_email">Owner Email</label>
                                        <input type="text" class="form-control" id="owner_email"
                                            placeholder="Enter owner email" name="owner_email"
                                            value="{{ old('owner_email', $restaurant->owner_email) }}" />
                                        @error('owner_email')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>


                            <hr>

                            <h4>5. Operating Hours:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="opening_time">Opening Time</label>
                                        <input type="text" class="form-control" id="opening_time"
                                            placeholder="Select opening time" name="opening_time"
                                            value="{{ old('opening_time', $restaurant->opening_time) }}" />
                                        @error('opening_time')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="closing_time">Closing Time:</label>
                                        <input type="text" class="form-control" id="closing_time"
                                            placeholder="Select closing time" name="closing_time"
                                            value="{{ old('closing_time', $restaurant->closing_time) }}" />
                                        @error('closing_time')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="days_of_operation">Days of Operation</label>
                                        <input type="text" class="form-control" id="days_of_operation"
                                            placeholder="Select days of operation" name="days_of_operation"
                                            value="{{ old('days_of_operation', $restaurant->days_of_operation) }}" />
                                        @error('days_of_operation')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="form-group col-6">
                                  <div class="mb-6">
                                    <label class="form-label" for="basic-default-company">Logo</label>
                                    <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
                                  </div>
                            </div> --}}
                            </div>



                            <h4>6. Delivery Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="delivery_fee">Delivery fee</label>
                                        <input type="text" class="form-control" id="delivery_fee"
                                            placeholder="Enter delivery fee eg. 50.00" name="delivery_fee"
                                            value="{{ old('delivery_fee', $restaurant->delivery_fee) }}" />
                                        @error('delivery_fee')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="delivery_time">Delivery time</label>
                                        <input type="text" class="form-control" id="delivery_time"
                                            placeholder="Enter delivery time eg. 20mins" name="delivery_time"
                                            value="{{ old('delivery_time', $restaurant->delivery_time) }}" />
                                        @error('delivery_time')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="average_cost_for_per_person">Avg. cost per
                                            person</label>
                                        <input type="text" class="form-control" id="average_cost_for_per_person"
                                            placeholder="eg. 150" name="average_cost_for_per_person"
                                            value="{{ old('average_cost_for_per_person', $restaurant->average_cost_for_per_person) }}" />
                                        @error('average_cost_for_per_person')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="delivery_on_off">Delivery On/Off</label>

                                        <select name="delivery_on_off" id="delivery_on_off" class="form-control">
                                            <option value="on"  @if ($restaurant->delivery_on_off == 'on') selected @endif>On</option>
                                            <option value="off"  @if ($restaurant->delivery_on_off == 'off') selected @endif>Off</option>

                                        </select>


                                        @error('average_cost_for_per_person')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>



                            </div>



                            {{-- Pricing and Offers: add in future if needed --}}

                            <hr>
                            <h4>7. Images and Media:</h4>
                            <div class="row">
                                {{-- <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="restaurant_images">Restaurant Images</label>
                                        <input type="file" class="form-control" id="restaurant_images"
                                            name="restaurant_images" />
                                        @error('restaurant_images')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                        <div class="mt-3"> <img
                                            src="{{ asset('storage/restaurants/images/' . $restaurant->restaurant_images) }}"
                                            alt="" srcset="" width="220px" height="100px">
                                        </div>

                                    </div>
                                </div> --}}
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="featured_image">Featured Image</label>
                                        <input type="file" class="form-control" id="featured_image"
                                            name="featured_image" />
                                        @error('featured_image')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror

                                        <div class="mt-3"> <img
                                            src="{{ asset('storage/restaurants/featured/' . $restaurant->featured_image) }}"
                                            alt="" srcset="" width="220px" height="100px">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>8. Tax and Legal Information:</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="tax_gst_number">Tax ID/GST Number</label>
                                        <input type="text" class="form-control" id="tax_gst_number"
                                            placeholder="Enter tax/GST number" name="tax_gst_number"
                                            value="{{ old('tax_gst_number', $restaurant->tax_gst_number) }}" />
                                        @error('tax_gst_number')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">

                                        <label class="form-label" for="fssai_number">Fssai
                                            Number/Code</label>
                                        <input type="text" class="form-control" id="fssai_number"
                                            placeholder="Enter fssai number" name="fssai_number"
                                            value="{{ old('fssai_number', $restaurant->fssai_number) }}" />
                                            @error('fssai_number')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>9. Bank Details :</h4>
                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="bank_holder_name">Bank Holder's name</label>
                                        <input type="text" class="form-control" id="bank_holder_name"
                                            placeholder="Enter bank holder's name" name="bank_holder_name"
                                            value="{{ old('bank_holder_name' ,$restaurant->bank_holder_name ) }}" />
                                        @error('bank_holder_name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="bank_account_number">Bank Account Number</label>
                                        <input type="text" class="form-control" id="bank_account_number"
                                            placeholder="Enter bank account number" name="bank_account_number"
                                            value="{{ old('bank_account_number' ,$restaurant->bank_account_number) }}" />
                                        @error('bank_account_number')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <div class="mb-6">
                                        <label class="form-label" for="ifsc_code">IFSC Code</label>
                                        <input type="text" class="form-control" id="ifsc_code"
                                            placeholder="Enter IFSC code" name="ifsc_code"
                                            value="{{ old('ifsc_code' , $restaurant->ifsc_code) }}" />
                                        @error('ifsc_code')
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
