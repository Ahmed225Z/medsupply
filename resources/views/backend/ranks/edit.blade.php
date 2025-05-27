@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{ translate('Edit Rank') }}</h5>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="{{ route('Ranks.update', $rank->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Rank Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $rank->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                            <img src="{{ static_asset('Web-Images/' . $rank->image) }}" alt="{{ $rank->name }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                        </div>
                        <div class="form-group">
                            <label for="min">Minimum Price</label>
                            <input type="number" name="min" id="min" class="form-control" step="0.01" value="{{ $rank->min }}" required>
                        </div>
                        <div class="form-group">
                            <label for="max">Max Price</label>
                            <input type="number" name="max" id="max" class="form-control" value="{{ $rank->max }}" required>
                        </div>
                        <div class="form-group">
                            <label for="pts">Pts</label>
                            <input type="number" name="pts" id="pts" class="form-control" value="{{ $rank->pts }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
