@extends('backend.layouts.master')

@section('main-content')
    <div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
            <!-- /.container-fluid -->
        @endif
    </div>
    <div class="card">
        <h5 class="card-header">Edit Product</h5>
        <div class="card-body">
            <form method="post" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ $product->title }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="summary" name="summary">{{ $product->summary }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="is_featured">Is Featured</label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='{{ $product->is_featured }}'
                        {{ $product->is_featured ? 'checked' : '' }}> Yes
                </div>
                {{-- {{$categories}} --}}

                <div class="form-group">
                    <label for="cat_id">Category <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($categories as $key => $cat_data)
                            <option value='{{ $cat_data->id }}' {{ $product->cat_id == $cat_data->id ? 'selected' : '' }}>
                                {{ $cat_data->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="discount" class="col-form-label">Discount(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100"
                        placeholder="Enter discount" value="{{ $product->discount }}" class="form-control">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" class="form-control">
                        <option value="">--Select Brand--</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">Condition</label>
                    <select name="condition" class="form-control">
                        <option value="">--Select Condition--</option>
                        <option value="default" {{ $product->condition == 'default' ? 'selected' : '' }}>Default</option>
                        <option value="new" {{ $product->condition == 'new' ? 'selected' : '' }}>New</option>
                        <option value="hot" {{ $product->condition == 'hot' ? 'selected' : '' }}>Hot</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photos</label>
                    <div class="input-group">
                        <input class="form-control" type="file" name="photos[]" value='{{ $product->images }}' multiple>
                    </div>
                    <div id="holder" style="margin-top: 15px; max-height: 100px;">
                        @foreach ($product->images as $photo)
                            <img src="{{ asset($photo->path) }}" alt="Banner Photo"
                                style="max-height: 100px; margin-right: 5px;">
                        @endforeach
                    </div>
                    @error('photos')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <div id="size-container">
                        @foreach ($product->sizes as $size)
                            <div class="size-input-group mb-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="sizes[]" class="form-control">
                                            <option value="">--Select Size--</option>
                                            <option value="{{ $size->id }}"
                                                {{ $size->id == $size->id ? 'selected' : '' }}>
                                                {{ $size->name }} ({{ $size->abbreviation }})
                                            </option>
                                        </select>
                                        @error('sizes.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="quantities[]" class="form-control"
                                            placeholder="Enter quantity" value="{{ $size->pivot->quantity }}">
                                        @error('quantities.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="prices[]" class="form-control"
                                            placeholder="Enter price" value="{{ $size->pivot->price }}">
                                        @error('prices.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="discounts[]" class="form-control"
                                            placeholder="Enter discount" value="{{ $size->pivot->discount }}">
                                        @error('discounts.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <button type="button" class="btn btn-danger remove-size">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-success" id="add-size">Add Size</button>
                </div>

                <div id="hidden-inputs-container"></div>

                <!-- ... Other form fields ... -->

                <div class="form-group mb-3">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#add-size').click(function() {
                var sizeInputGroup = $('.size-input-group:first').clone();
                sizeInputGroup.find('select[name="sizes[]"]').val('');
                sizeInputGroup.find('input[name="quantities[]"]').val('');
                sizeInputGroup.find('input[name="prices[]"]').val('');
                sizeInputGroup.find('input[name="discounts[]"]').val('');
                $('#size-container').append(sizeInputGroup);
            });

            $(document).on('click', '.remove-size', function() {
                if ($('.size-input-group').length > 1) {
                    $(this).closest('.size-input-group').remove();
                } else {
                    alert("At least one size is required.");
                }
            });

            function updateHiddenInputs() {
                $('#hidden-inputs-container').empty();

                $('.size-input-group').each(function(index) {
                    var size = $(this).find('select[name="sizes[]"]').val();
                    var quantity = $(this).find('input[name="quantities[]"]').val();
                    var price = $(this).find('input[name="prices[]"]').val();
                    var discount = $(this).find('input[name="discounts[]"]').val();

                    $('#hidden-inputs-container').append(
                        '<div class="hidden-size-row">' +
                        '<input type="hidden" name="hidden_sizes[]" value="' + size + '">' +
                        '<input type="hidden" name="hidden_quantities[]" value="' + quantity + '">' +
                        '<input type="hidden" name="hidden_prices[]" value="' + price + '">' +
                        '<input type="hidden" name="hidden_discounts[]" value="' + discount + '">' +
                        '</div>'
                    );
                });
            }
        });
    </script>
@endpush
