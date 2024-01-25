@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Edit Category</h5>
        <div class="card-body">
            <form method="post" action="{{ route('category.update', $category->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"
                        value="{{ $category->title }}" class="form-control">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo</label>
                    <div class="input-group">
                        <input class="form-control" type="file" name="photo">
                    </div>
                    <div id="holder" style="margin-top: 15px; max-height: 100px;">
                        @if ($category->photo)
                            <img src="{{ asset($category->photo) }}" alt="Category Photo" style="max-height: 100px;">
                        @else
                            <p>No photo available</p>
                        @endif
                    </div>
                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
