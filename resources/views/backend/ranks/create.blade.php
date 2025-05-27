@extends('backend.layouts.app')

@section('content')
<div class="card-header row gutters-5">
    <div class="col">
        <h5 class="mb-0 h6">{{ translate('Create New Rank') }}</h5>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('Ranks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Rank Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control-file" onchange="previewImage(event)">
                    <img id="image-preview" src="#" alt="Image Preview" class="img-fluid mt-3" style="display:none; max-width: 200px; max-height: 200px;">
                </div>
                <div class="form-group">
                    <label for="price">Minimum Price</label>
                    <input type="text" name="min" id="price" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="product_upload">Max Price</label>
                    <input type="text" name="max" id="product_upload" class="form-control" >
                </div>
                <div class="form-group">
                    <label for="digital_product_upload">Pts</label>
                    <input type="text" name="pts" id="digital_product_upload" class="form-control" >
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    var input = event.target;
    var reader = new FileReader();

    reader.onload = function(){
        var imagePreview = document.getElementById('image-preview');
        imagePreview.src = reader.result;
        imagePreview.style.display = 'block';
    };

    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
