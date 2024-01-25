@extends('backend.layouts.master')
@section('title','E-SHOP || Size Create')
@section('main-content')

<div class="card">
    <h5 class="card-header">Add Size</h5>
    <div class="card-body">
      <form method="post" action="{{route('size.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputname" class="col-form-label">Name <span class="text-danger">*</span></label>
        <input id="inputname" type="text" name="name" placeholder="Enter name"  value="{{old('name')}}" class="form-control">
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>
        <div class="form-group">
          <label for="inputabbreviation" class="col-form-label">Abbreviation <span class="text-danger">*</span></label>
        <input id="inputabbreviation" type="text" name="abbreviation" placeholder="Enter abbreviation"  value="{{old('abbreviation')}}" class="form-control">
        @error('abbreviation')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#description').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush