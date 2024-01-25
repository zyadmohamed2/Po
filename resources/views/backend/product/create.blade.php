@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Add Product</h5>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="card-body">
            <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ old('title') }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary</label>
                    <textarea class="form-control" id="summary" name="summary">{{ old('summary') }}</textarea>
                    @error('summary')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="is_featured">Is Featured</label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='1' {{old('is_featured')==1?'checked':''}} > Yes
                </div>

                <div class="form-group">
                    <label for="poupular">Is poupular</label><br>
                    <input type="checkbox" name='is_poupular' id='poupular' value='1' {{old('is_poupular')==1?'checked':''}} > Yes
                </div>

                <div class="form-group">
                    <label for="Banner">Is Banner</label><br>
                    <input type="checkbox" name='is_banner' id='Banner' value='1' {{old('is_banner')==1?'checked':''}} > Yes
                </div>
                {{-- {{$categories}} --}}

                <div class="form-group">
                    <label for="cat_id">Category <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach ($categories as $key => $cat_data)
                            <option value='{{ $cat_data->id }}' {{$cat_data->id==old('cat_id')?'selected':''}}>{{ $cat_data->title }}</option>
                        @endforeach
                    </select>
                    @error('cat_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="discount" class="col-form-label">Discount(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100"
                        placeholder="Enter discount" value="{{ old('discount') }}" class="form-control">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" class="form-control">
                        <option value="">--Select Brand--</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"{{$brand->id==old('brand_id')?'selected':''}}>{{ $brand->title }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="condition">Condition</label>
                    <select name="condition" class="form-control">
                        <option selected value="default" >Default</option>
                        <option value="new">New</option>
                        <option value="hot">Hot</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photos</label>
                    <div class="input-group">
                        <input class="form-control" type="file" name="photos[]" multiple>
                    </div>
                    @error('photos')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option selected value="active" {{old('status')== 'active'?'selected':''}}>Active</option>
                        <option value="inactive" {{old('status')== 'inactive'?'selected':''}}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <div id="size-container">
                        <div class="size-input-group mb-2">
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="sizes[]" class="form-control">
                                        <option value="">--Select Size--</option>
                                        @foreach ($sizes as $size)
                                            <option value={{ $size->id }}>
                                                {{ $size->name }}({{ $size->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                    @error('sizes.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="quantities[]" class="form-control"
                                        placeholder="Enter quantity">
                                    @error('quantities.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="prices[]" class="form-control"
                                        placeholder="Enter price">
                                    @error('prices.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="discounts[]" class="form-control"
                                        placeholder="Enter discount">
                                    @error('discounts.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3 mt-2">
                                    <button type="button" class="btn btn-danger remove-size">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="add-size">Add Size</button>
                </div>

                <div id="hidden-inputs-container"></div>
                <div class="form-group mb-3">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#add-size').click(function() {
                if ($('.size-input-group').length === 1) {
                    // If there is only one row, clone it for a new size
                    var sizeInputGroup = $('.size-input-group:first').clone();
                    sizeInputGroup.find('select[name="sizes[]"]').val(''); // Clear selected value
                    sizeInputGroup.find('input[name="quantities[]"]').val(''); // Clear quantity value
                    sizeInputGroup.find('input[name="prices[]"]').val(''); // Clear price value
                    sizeInputGroup.find('input[name="discounts[]"]').val(''); // Clear discount value
                    $('#size-container').append(sizeInputGroup);
                } else {
                    // If there are multiple rows, add a new row
                    var newRow = $('.size-input-group:first').clone();
                    newRow.find('select[name="sizes[]"]').val(''); // Clear selected value
                    newRow.find('input[name="quantities[]"]').val(''); // Clear quantity value
                    newRow.find('input[name="prices[]"]').val(''); // Clear price value
                    newRow.find('input[name="discounts[]"]').val(''); // Clear discount value
                    newRow.find('.remove-size').click(function() {
                        newRow.remove();
                        updateHiddenInputs();
                    });
                    $('#size-container').append(newRow);
                }
            });

            $(document).on('click', '.remove-size', function() {
                if ($('.size-input-group').length > 1) {
                    // Only remove rows if there are more than one
                    $(this).closest('.size-input-group').remove();
                    updateHiddenInputs();
                }
            });

            function updateHiddenInputs() {
                $('#hidden-inputs-container').empty();

                $('.size-input-group').each(function(index) {
                    var size = $(this).find('select[name="sizes[]"]').val();
                    var quantity = $(this).find('input[name="quantities[]"]').val();
                    var price = $(this).find('input[name="prices[]"]').val();
                    var discount = $(this).find('input[name="discounts[]"]').val();

                    // Add hidden inputs for each dynamic size
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
