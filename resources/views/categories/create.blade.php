@extends('layouts.app')
@section('title', 'Create Item')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.categories.index') }}">Categories</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.items.create') }}">Create Category</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <h4 class="card-header-title">Product information</h4>
            </div>

            <div class="card-body">

                <div class="mb-4">
                    <label class="row form-check form-switch" for="is-enable">
                        <span class="col-8 col-sm-9 ms-0">
                            <span class="text-dark">
                                Availability
                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Product availability switch toggler."></i>
                            </span>
                        </span>
                        <span class="col-4 col-sm-3 text-end">
                            <input id="is-enable" name="is_enable" type="checkbox" class="form-check-input" checked="">
                        </span>
                    </label>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label for="name" class="form-label">
                        Name
                        <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Items are the goods or services you sell."></i>
                    </label>

                    <input id="name" name="item_name" type="text" class="form-control"
                        placeholder="Shirt, t-shirts, etc." aria-label="Shirt, t-shirts, etc." value="">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Media</h4>
            </div>

            <div class="card-body">
                <div id="image-url" name="image_url" class="js-dropzone dz-dropzone dz-dropzone-card">
                    <div class="dz-message">
                        <img class="avatar avatar-xl avatar-4x3 mb-3"
                            src="{{ asset('assets/svg/illustrations/oc-browse.svg') }}" alt="Browse File"
                            data-hs-theme-appearance="default">
                        <img class="avatar avatar-xl avatar-4x3 mb-3"
                            src="{{ asset('assets/svg/illustrations-light/oc-browse.svg') }}" alt="Browse File"
                            data-hs-theme-appearance="dark">

                        <h5>Drag and drop your file here</h5>

                        <p class="mb-2">or</p>

                        <span class="btn btn-white btn-sm">Browse files</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
@endsection

@section('javascript')
<script src="{{ asset('assets/vendor/dropzone/dist/min/dropzone.min.js') }}"></script>

<script>
    (function () {
        HSCore.components.HSDropzone.init('.js-dropzone', {
            maxFiles: 1,
            uploadMultiple: false,
            acceptedFiles: ".jpeg,.jpg,.png",
            init: function() {
                this.on("maxfilesexceeded", function(file) {
                    this.removeFile(file);
                });
            }
        });
    })();
</script>
@endsection