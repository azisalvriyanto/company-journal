@extends('layouts.app')
@section('title', 'Create Item')

@section('list-separator')
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.items.index') }}">Items</a>
</li>
<li class="list-inline-item">
    <a class="list-separator-link" href="{{ route('items.items.create') }}">Create Item</a>
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

                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label for="product-code" class="form-label">Product Code</label>

                            <input id="product-code" name="product_code" type="text" class="form-control"
                                placeholder="eg. 348121032" aria-label="eg. 348121032">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-4">
                            <label for="unit-of-measurement" class="form-label">Unit of Measurement</label>

                            <div class="tom-select-custom">
                                <select id="unit-of-measurement" name="unit_of_measurement"
                                    class="js-select form-select" autocomplete="off" data-hs-tom-select-options='{
                                        "searchInDropdown": true,
                                        "hideSearch": false,
                                        "placeholder": "Search..."
                                }'>
                                    @foreach($unitOfMeasurements as $unitOfMeasurement)
                                    <option value="{{ $unitOfMeasurement['id'] }}">
                                        {{ $unitOfMeasurement['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <small class="form-text">Used to calculate shipping rates at checkout and label prices
                                during fulfillment.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 mb-lg-5">
            <div class="card-header card-header-content-between">
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

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Organization</h4>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <label for="categpry" class="form-label">Category</label>

                    <div class="tom-select-custom">
                        <select class="js-select form-select" autocomplete="off" id="categpry"
                            data-hs-tom-select-options='{
                                "searchInDropdown": true,
                                "hideSearch": false,
                                "placeholder": "Search..."
                        }'>
                            @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">
                                {{ $category['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="detail-group" class="form-label">Detail Group</label>

                    <div class="tom-select-custom">
                        <select id="detail-group" name="detail_group" class="js-select form-select" autocomplete="off"
                            data-hs-tom-select-options='{
                                "searchInDropdown": true,
                                "hideSearch": false,
                                "placeholder": "Search..."
                            }'>
                            @foreach($detailGroups as $detailGroup)
                            <option value="{{ $detailGroup['id'] }}">
                                {{ $detailGroup['name'] }}
                            </option>
                            @endforeach
                        </select>
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
        // HSCore.components.HSTomSelect.init(`select[name="detail_group"]`, {
		//     "valueField": 'id',
		//     "labelField": 'name',
        //     "searchField": ['name'],
		//     "options": [],
        //     "load": function(query, callback) {
        //         fetch(`{{ route("api.items.items.detail-groups.index") }}?keyword=${encodeURIComponent(query)}`)
        //         .then(response => response.json())
        //         .then(json => {
        //             callback(json.data);
        //         })
        //         .catch(e => {
        //             callback();
        //         });
        //     },
        //     "render": {
        //         option: function(data, escape) {
        //             return `<div>${escape(data.name)}</div>`;
        //         },
        //         item: function(data, escape) {
        //             return `<div>${escape(data.name)}</div>`;
        //         }
        //     }
        // });

        HSCore.components.HSTomSelect.init('.js-select');
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